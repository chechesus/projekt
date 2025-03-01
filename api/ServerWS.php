<?php 
require_once 'C:\xampp\htdocs\projekt\vendor\autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class NotificationServer implements MessageComponentInterface {
    protected $clients;
    protected $users = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        error_log("[".date('Y-m-d H:i:s')."] Notifikačný server spustený.");
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        error_log("[".date('Y-m-d H:i:s')."] Nové spojenie: ResourceID " . $conn->resourceId);

        $queryParams = [];
        parse_str($conn->httpRequest->getUri()->getQuery(), $queryParams);
    
        if (!isset($queryParams['user_id']) || !isset($queryParams['role_id'])) {
            error_log("[".date('Y-m-d H:i:s')."] Chýbajúce query parametre pre ResourceID " . $conn->resourceId);
            $conn->close();
            return;
        }
    
        $user_id = (int) $queryParams['user_id'];
        $role_id = (int) $queryParams['role_id'];
    
        $this->users[$conn->resourceId] = [
            'user_id' => $user_id,
            'role_id' => $role_id
        ];
        
        error_log("[".date('Y-m-d H:i:s')."] Používateľ $user_id s rolou $role_id sa pripojil. (ResourceID " . $conn->resourceId . ")");
        echo("Používateľ $user_id s rolou $role_id sa pripojil.\n");
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        error_log("[".date('Y-m-d H:i:s')."] Prijatá správa od ResourceID " . $from->resourceId . ": " . $msg);
        echo("[".date('Y-m-d H:i:s')."] Správa prijatá od ResourceID " . $from->resourceId . ": " . $msg . "\n");
        
        try {
            $data = json_decode($msg, true);
            if (!$data) {
                throw new \Exception("Neplatný JSON: " . $msg);
            }
            
            switch ($data['action']) {
                case 'identify':
                    error_log("[".date('Y-m-d H:i:s')."] Identifikácia od ResourceID " . $from->resourceId . ": user_id = {$data['user_id']}, role_id = {$data['role_id']}");
                    break;
                case 'blockUser':
                    $this->handleBlockUser($from, $data);
                    break;
                case 'getCounts':
                    $this->handleGetCounts($from, $data);
                    break;
                default:
                    error_log("[".date('Y-m-d H:i:s')."] Neznáma akcia: " . $data['action'] . " od ResourceID " . $from->resourceId);
            }
        } catch (\Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."] Chyba v onMessage pre ResourceID " . $from->resourceId . ": " . $e->getMessage());
            $from->send(json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]));
        }
    }

    protected function handleBlockUser(ConnectionInterface $from, $data) {
        require_once __DIR__ . '/config.php';
        $dbConn = new mysqli("127.0.0.1:3307", "root", "", "data");
        if ($dbConn->connect_error) {
            error_log("[".date('Y-m-d H:i:s')."] Pripojenie zlyhalo: " . $dbConn->connect_error);
            echo("[".date('Y-m-d H:i:s')."] Pripojenie zlyhalo: " . $dbConn->connect_error);
            return;
        }
        error_log("[".date('Y-m-d H:i:s')."] Spracovávam blockUser akciu pre user_id: {$data['user_id']} (ResourceID " . $from->resourceId . ")");
                    
        $sql = "SELECT blocked FROM data.users WHERE id = {$data['user_id']}";
        error_log("[".date('Y-m-d H:i:s')."] Vykonávam dopyt: $sql");
        $result = mysqli_query($dbConn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $blocked = $row['blocked'];
            error_log("[".date('Y-m-d H:i:s')."] Výsledok dopytu: blocked = $blocked pre user_id = {$data['user_id']}");
            
            if ($blocked == 1) {
                $sql2 = "SELECT reason FROM acces.blocked_overview WHERE user_id = {$data['user_id']} AND role_id = {$data['role_id']} ORDER BY created_at DESC LIMIT 1";
                error_log("[".date('Y-m-d H:i:s')."] Vykonávam dopyt: $sql2");
                $result2 = mysqli_query($dbConn, $sql2);
                if ($result2 && mysqli_num_rows($result2) > 0) {
                    $row2 = mysqli_fetch_assoc($result2);
                    $reason = $row2['reason'];
                } else {
                    $reason = "";
                }
                $response = json_encode([
                    'status' => 'blocked',
                    'sprava' => 'check',
                    'reason' => $reason
                ]);
                error_log("[".date('Y-m-d H:i:s')."] Používateľ {$data['user_id']} je blokovaný. Dôvod: " . $reason);
            } else {
                $response = json_encode([
                    'status' => 'open'
                ]);
                error_log("[".date('Y-m-d H:i:s')."] Používateľ {$data['user_id']} nie je blokovaný.");
            }
            
            error_log("[".date('Y-m-d H:i:s')."] Odosielam odpoveď pre ResourceID " . $from->resourceId . ": " . $response);
            $from->send($response);
        } else {
            error_log("[".date('Y-m-d H:i:s')."] Nenašiel sa záznam v data.users pre user_id: {$data['user_id']}");
            echo ("[".date('Y-m-d H:i:s')."] Nenašiel sa záznam v data.users pre user_id: {$data['user_id']}");
        }
        
        mysqli_close($dbConn);
    }
    
    protected function handleGetCounts(ConnectionInterface $from, $data) {
        require_once __DIR__ . '/config.php';
        
        $user_id = (int)$data['user_id'];
        $role_id = (int)$data['role_id'];
        
        // Vyber správnu databázu na základe role_id:
        // Admini a moderátori sú v databáze 'acces', bežní používatelia v 'data'.
        if ($role_id === 1 || $role_id === 3) {
            $dbname = "acces";
        } else {
            $dbname = "data";
        }
        
        // Pripojenie k vybranej databáze
        $dbConn = new mysqli("127.0.0.1:3307", "root", "", $dbname);
        if ($dbConn->connect_error) {
            error_log("[".date('Y-m-d H:i:s')."] Pripojenie zlyhalo v handleGetCounts: " . $dbConn->connect_error);
            $from->send(json_encode(["status" => "error", "message" => "Pripojenie zlyhalo"]));
            return;
        }
        
        // Získanie počtu notifikácií
        $sqlNotif = "SELECT COUNT(*) as count FROM notifications WHERE receiver_id = ? AND receiver_role = ?";
        $stmtNotif = $dbConn->prepare($sqlNotif);
        if (!$stmtNotif) {
            error_log("[".date('Y-m-d H:i:s')."] SQL prepare error (notifications): " . $dbConn->error);
        }
        $stmtNotif->bind_param("ii", $user_id, $role_id);
        $stmtNotif->execute();
        $resultNotif = $stmtNotif->get_result();
        $notifCount = 0;
        if ($resultNotif && $row = $resultNotif->fetch_assoc()) {
             $notifCount = $row['count'];
        }
        $stmtNotif->close();
        
        // Získanie počtu správ
        $sqlMsg = "SELECT COUNT(*) as count FROM conversations WHERE receiver_id = ? AND receiver_role = ?";
        $stmtMsg = $dbConn->prepare($sqlMsg);
        if (!$stmtMsg) {
            error_log("[".date('Y-m-d H:i:s')."] SQL prepare error (conversations): " . $dbConn->error);
        }
        $stmtMsg->bind_param("ii", $user_id, $role_id);
        $stmtMsg->execute();
        $resultMsg = $stmtMsg->get_result();
        $msgCount = 0;
        if ($resultMsg && $row = $resultMsg->fetch_assoc()) {
             $msgCount = $row['count'];
        }
        $stmtMsg->close();
        $dbConn->close();
        
        $response = json_encode([
             'action' => 'getCounts',
             'notificationCount' => $notifCount,
             'messageCount' => $msgCount
        ]);
        error_log("[".date('Y-m-d H:i:s')."] Odosielam counts pre user_id: $user_id: notif=$notifCount, msg=$msgCount");
        $from->send($response);
    }
    
    
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        if (isset($this->users[$conn->resourceId])) {
            $userData = $this->users[$conn->resourceId];
            error_log("[".date('Y-m-d H:i:s')."] Používateľ {$userData['user_id']} (roľa: {$userData['role_id']}) sa odpojil. (ResourceID " . $conn->resourceId . ")");
            echo("[".date('Y-m-d H:i:s')."] Používateľ {$userData['user_id']} (roľa: {$userData['role_id']}) sa odpojil.\n");
            unset($this->users[$conn->resourceId]);
        } else {
            error_log("[".date('Y-m-d H:i:s')."] Spojenie zavreté pre ResourceID " . $conn->resourceId . " (neznámy používateľ)");
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        error_log("[".date('Y-m-d H:i:s')."] Error occurred for ResourceID " . $conn->resourceId . ": " . $e->getMessage());
        $conn->close();
    }
}

$notificationServer = new NotificationServer();
$server = IoServer::factory(
    new HttpServer(new WsServer($notificationServer)),
    50000
);

error_log("[".date('Y-m-d H:i:s')."] WebSocket server listening on port 50000...");
echo("WebSocket server listening on port 50000...\n");
$server->run();
