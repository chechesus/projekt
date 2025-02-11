<?php
require_once '../api/session.php';
$data = json_decode(file_get_contents('php://input'), true);
$content = $data['content'];
$category = $data['category'];
$title = $data['title'];

$sql =("INSERT INTO articles (DEFAULT, content, title, category) VALUES ('$content','$title', '$category')");
$stmt = $conn->prepare($sql);
$stmt->execute();
echo json_encode(['message' => 'Odsek pridaný!']);
?>
