<?php
$host = 'localhost';
$port = 50000;

$user_id = $_POST['user_id'];
$reason = $_POST['reason'];

$socket = fsockopen($host, $port, $errno, $errstr, 2);
if (!$socket) {
    echo json_encode(["status" => "error", "message" => "Chyba pri pripájaní k WebSocket serveru"]);
    exit;
}

$data = json_encode(["user_id" => $user_id, "reason" => $reason, "action" => "block"]);
fwrite($socket, $data . "\n");
fclose($socket);

echo json_encode(["status" => "success"]);
?>
