<?php
require "../../../api/session.php";
require_once __DIR__ . '/../../../vendor/autoload.php';
use WebSocket\Client;
date_default_timezone_set('Europe/Bratislava');

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(["status" => "error", "message" => "Neplatný CSRF token"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;
    $blocked_by_role = isset($_POST['blocked_by_role']) ? intval($_POST['blocked_by_role']) : 0;
    $blocked_by_id = isset($_POST['blocked_by_id']) ? intval($_POST['blocked_by_id']) : 0;
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    $duration = isset($_POST['duration']) ? trim($_POST['duration']) : ''; 
    if (!$user_id || !$role_id || !$blocked_by_id || empty($reason)) {
        die(json_encode(["status" => "error", "message" => "Neplatné vstupné údaje $user_id, $role_id, $blocked_by_role, $reason, $duration"]));
    }
    
    if ($duration === "permanent") {
        $unblocked_at = null; // Trvalý bann
    } elseif ($duration === "custom") {
        // Ak je zadaný vlastný dátum cez datetime-local
        if (isset($_POST['custom_date']) && strtotime($_POST['custom_date'])) {
            $datetime = new DateTime($_POST['custom_date']);
            $unblocked_at = $datetime->format('Y-m-d H:i:s');
        } else {
            $unblocked_at = null; // Ak nie je zadaný vlastný dátum
        }
    } elseif (is_numeric($duration)) {
        // Ak je duration počet dní
        $datetime = new DateTime(); // Aktuálny dátum
        $datetime->modify("+$duration days"); // Pridanie dní
        $unblocked_at = $datetime->format('Y-m-d H:i:s');
    } elseif (strtotime($duration)) {
        // Ak je duration vo formáte časového reťazca
        $datetime = new DateTime($duration);
        $unblocked_at = $datetime->format('Y-m-d H:i:s');
    } else {
        echo json_encode(["status" => "error", "message" => "Neplatný formát trvania blokácie", "duration" => $duration]);
        exit;
    }
    
    // Kontrola
    error_log("user_id: " . var_export($user_id, true));
    error_log("role_id: " . var_export($role_id, true));
    error_log("blocked_by_role: " . var_export($blocked_by_role, true));
    error_log("blocked_by_id: " . var_export($blocked_by_id, true));
    error_log("reason: " . var_export($reason, true));
    error_log("unblocked_at: " . var_export($unblocked_at, true));

    function getUnique($user_id, $role_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT unified_id FROM acces.all_entry WHERE user_id = ? AND role_id = ?");
        $stmt->bind_param("ii", $user_id, $role_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        // Return the integer value of the 'ID' column
        return isset($row['ID']) ? (int)$row['ID'] : null;
    }
    $unique = getUnique($user_id,$role_id);
    $stmt = $conn->prepare("INSERT INTO acces.blocked_overview (user_id, role_id, blocked_by_role, blocked_by_id, reason, created_at, unblocked_at) 
                            VALUES (?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("iisiss", $user_id, $role_id, $blocked_by_role, $blocked_by_id, $reason, $unblocked_at);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
        
    } else {
        echo json_encode(["status" => "error", "message" => "Chyba pri vkladaní do databázy"]);
    }
    try {
        $admin_user_id = $_SESSION['userid'];  // Ensure this is set in your admin session.
        $admin_role_id = $_SESSION['role_id'];   // Replace with the appropriate role if needed.

        $wsUrl = "ws://localhost:50000/?user_id={$admin_user_id}&role_id={$admin_role_id}";
        $ws = new Client($wsUrl);
        $ws->send(json_encode([
            "action" => "blockUser",
            "user_id" => $user_id,
            "role_id" => $role_id,
            "reason" => $reason
        ]));

        $response = $ws->receive();
        error_log("Odpoveď od WS servera: " . $response);

        // Zatvoríme spojenie
        $ws->close();
    } catch (Exception $e) {
        error_log("Chyba pri odosielaní správy cez WS: " . $e->getMessage());
    }
    $stmt->close();
}
?>