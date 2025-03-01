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

if (!$imageId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Image ID required']);
    exit;
}

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Check if user already liked this image
    $stmt = $pdo->prepare("SELECT * FROM image_likes WHERE user_id = :userId AND image_id = :imageId");
    $stmt->execute([':userId' => $_SESSION['user_id'], ':imageId' => $imageId]);
    
    if ($stmt->rowCount() > 0) {
        // User already liked, remove like
        $stmt = $pdo->prepare("DELETE FROM image_likes WHERE user_id = :userId AND image_id = :imageId");
        $stmt->execute([':userId' => $_SESSION['user_id'], ':imageId' => $imageId]);
        
        $stmt = $pdo->prepare("UPDATE images SET likes = likes - 1 WHERE id = :imageId");
        $stmt->execute([':imageId' => $imageId]);
    } else {
        // Add new like
        $stmt = $pdo->prepare("INSERT INTO image_likes (user_id, image_id) VALUES (:userId, :imageId)");
        $stmt->execute([':userId' => $_SESSION['user_id'], ':imageId' => $imageId]);
        
        $stmt = $pdo->prepare("UPDATE images SET likes = likes + 1 WHERE id = :imageId");
        $stmt->execute([':imageId' => $imageId]);
    }
    
    // Get updated like count
    $stmt = $pdo->prepare("SELECT likes FROM images WHERE id = :imageId");
    $stmt->execute([':imageId' => $imageId]);
    $likes = $stmt->fetchColumn();
    
    echo json_encode(['success' => true, 'likes' => $likes]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
