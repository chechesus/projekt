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
    $pdo = //Database::getInstance()->getConnection();
    
    // Check if user already disliked this image
    $stmt = $pdo->prepare("SELECT * FROM image_dislikes WHERE user_id = :userId AND image_id = :imageId");
    $stmt->execute([':userId' => $_SESSION['user_id'], ':imageId' => $imageId]);
    
    if ($stmt->rowCount() > 0) {
        // User already disliked, remove dislike
        $stmt = $pdo->prepare("DELETE FROM image_dislikes WHERE user_id = :userId AND image_id = :imageId");
        $stmt->execute([':userId' => $_SESSION['user_id'], ':imageId' => $imageId]);
        
        $stmt = $pdo->prepare("UPDATE images SET dislikes = dislikes - 1 WHERE id = :imageId");
        $stmt->execute([':imageId' => $imageId]);
    } else {
        // Add new dislike
        $stmt = $pdo->prepare("INSERT INTO image_dislikes (user_id, image_id) VALUES (:userId, :imageId)");
        $stmt->execute([':userId' => $_SESSION['user_id'], ':imageId' => $imageId]);
        
        $stmt = $pdo->prepare("UPDATE images SET dislikes = dislikes + 1 WHERE id = :imageId");
        $stmt->execute([':imageId' => $imageId]);
    }
    
    // Get updated dislike count
    $stmt = $pdo->prepare("SELECT dislikes FROM images WHERE id = :imageId");
    $stmt->execute([':imageId' => $imageId]);
    $dislikes = $stmt->fetchColumn();
    
    echo json_encode(['success' => true, 'dislikes' => $dislikes]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
