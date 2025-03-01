<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
require_once '../auth/auth.php';
header('Content-Type: application/json');

// Overenie, či ide o POST požiadavku
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Neplatná metóda požiadavky."]);
    exit;
}

// Overenie CSRF tokenu
$postedToken = $_POST['csrf_token'] ?? '';
if ($postedToken !== $_SESSION['csrf_token']) {
    echo json_encode(["status" => "error", "message" => "Neplatný CSRF token."]);
    exit;
}

// Načítanie ID obrázku, ktorý chceme vymazať
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "Neplatné ID."]);
    exit;
}

require_once 'C:\xampp\htdocs\projekt\api\config.php'; // DB pripojenie

$stmt = $conn->prepare("DELETE FROM gallery.gallery_images WHERE id = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL príprava zlyhala: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Chyba pri vymazávaní: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
