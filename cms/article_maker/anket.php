<?php
require_once '../api/session.php';

$data = json_decode(file_get_contents('php://input'), true);

$question = $data['question'];
$options = $data['options'];

$sql =("INSERT INTO polls (question) VALUES ('$question')");
$stmt = $conn->prepare($sql);
$stmt->execute();

$conn->query("INSERT INTO polls (question) VALUES ('$question')");
$poll_id = $conn->insert_id;

foreach ($options as $option) {
    $option = trim($option);
    $conn->query("INSERT INTO poll_options (poll_id, option_text) VALUES ('$poll_id', '$option')");
}

echo json_encode(['message' => 'Anketa pridaná!']);
?>
