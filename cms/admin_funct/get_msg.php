<?php
require "../../api/session.php";

$sender_id = $_SESSION['userid'];
$receiver_id = $_GET['receiver_id'];
//echo var_dump($_POST);
$stmt = $conn->prepare("SELECT * FROM acces.messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC");
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode($messages);
?>
