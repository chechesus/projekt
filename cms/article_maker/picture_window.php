<?php
require_once '../api/session.php';
$data = json_decode(file_get_contents('php://input'), true);
$url = $data['url'];

$conn->query("INSERT INTO images (url) VALUES ('$url')");
echo json_encode(['message' => 'Obrázok pridaný!']);
?>
