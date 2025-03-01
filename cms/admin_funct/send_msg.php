<?php

require "../../api/session.php"; // Súbor na pripojenie k databáze

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(["status" => "csrf_error"]);
        exit;
    }
    $sender_id = $_SESSION['userid']; // ID prihláseného používateľa
    $sender_role = $_SESSION['role_id'];
    $receiver_id = $_POST['receiver_id'];
    $receiver_role = $_POST['receiver_role'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO acces.messages (sender_id, receiver_id, sender_role, receiver_role, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiis", $sender_id, $receiver_id, $sender_role, $receiver_role, $message);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    } else {
        echo json_encode(["status" => "empty"]);
    }
}
?>
