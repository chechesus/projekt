<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$imageId = $data['imageId'] ?? null;
$text = $data['text'] ?? null;

if (!$imageId || !$text) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Image ID and comment text required']);
    exit;
}

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Insert new comment
    $stmt = $pdo->prepare("INSERT INTO image_comments (user_id, image_id, text) VALUES (:userId, :imageId, :text)");
    $stmt->execute([
        ':userId' => $_SESSION['user_id'],
        ':imageId' => $imageId,
        ':text' => $text
    ]);
    
    // Get username for response
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :userId");
    $stmt->execute([':userId' => $_SESSION['user_id']]);
    $username = $stmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'username' => $username,
        'comment' => $text
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
