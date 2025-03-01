<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Načítanie údajov z formulára
$id       = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title    = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$vehicle  = trim($_POST['vehicle'] ?? '');
$is_best  = isset($_POST['is_best']) ? 1 : 0;

if ($id <= 0 || empty($title)) {
    echo json_encode(["status" => "error", "message" => "Neplatné údaje."]);
    exit;
}

require_once 'C:\xampp\htdocs\projekt\api\config.php'; // DB pripojenie

// Spracovanie nahrávania nového obrázka (ak je odoslaný)
$newImageUrl = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $uploadDir  = __DIR__ . '/uploads_temp/';
        // Použijeme unikátny názov, aby sa predišlo konfliktom
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid('img_', true) . "." . $extension;
    $targetFile = $uploadDir . $uniqueName;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        // Predpokladáme, že obrázok bude prístupný cez URL
        $newImageUrl = "/projekt/gallery/uploads/" . $uniqueName;
    } else {
        echo json_encode(["status" => "error", "message" => "Chyba pri nahrávaní obrázku."]);
        exit;
    }
}

// Pripravíme SQL dotaz – ak bol nahratý nový obrázok, aktualizujeme aj stĺpec image_url
if ($newImageUrl !== null) {
    $sql = "UPDATE gallery.gallery_images 
            SET title = ?, category = ?, vehicle = ?, is_best = ?, image_url = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL príprava zlyhala: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("sssisi", $title, $category, $vehicle, $is_best, $newImageUrl, $id);
} else {
    $sql = "UPDATE gallery.gallery_images 
            SET title = ?, category = ?, vehicle = ?, is_best = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL príprava zlyhala: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("sssii", $title, $category, $vehicle, $is_best, $id);
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Chyba pri aktualizácii: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
