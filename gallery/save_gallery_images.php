<?php
// save_gallery_image.php
require_once '../api/session.php';

// Overenie, či je používateľ prihlásený
if (!isset($_SESSION['userid']) && !isset($_SESSION['role_id'])) {
    die(json_encode(['success' => false, 'error' => 'Musíte byť prihlásený na upload obrázkov.']));
}

$userId = $_SESSION['userid'];
$role_id = $_SESSION['userid'];


// Overenie, či bol súbor odoslaný
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die(json_encode(['success' => false, 'error' => 'Chyba pri nahrávaní súboru.']));
}

// Voliteľné dáta z formulára
$category = trim($_POST['category'] ?? '');
$vehicle  = trim($_POST['vehicle'] ?? '');
$isBest   = isset($_POST['is_best']) && $_POST['is_best'] == 1 ? 1 : 0;

// Uložte súbor na server do dočasného priečinka
$uploadDir = __DIR__ . '/uploads_temp/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
$filename = basename($_FILES['image']['name']);
$tempFilePath = $uploadDir . uniqid('img_', true) . "." . pathinfo($filename, PATHINFO_EXTENSION);

if (!move_uploaded_file($_FILES['image']['tmp_name'], $tempFilePath)) {
    die(json_encode(['success' => false, 'error' => 'Nepodarilo sa uložiť dočasný súbor.']));
}

// Spustenie Python skriptu na nahratie obrázka na Google Drive
$pythonExecutable = "C:\\Users\\janko\\AppData\\Local\\Programs\\Python\\Python313\\python.exe"; // prispôsobte cestu
$pythonScriptPath = "gall_upload.py"; // cesta k python skriptu
if (!file_exists($pythonScriptPath)) {
    die(json_encode(['success' => false, 'error' => 'Python script nebol nájdený. '. $pythonScriptPath]));
}

// Príkaz na spustenie python skriptu s cestou k dočasnému súboru
$command = escapeshellcmd($pythonExecutable . " " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($tempFilePath));

// Otvorenie procesu
$descriptorspec = [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
];
$process = proc_open($command, $descriptorspec, $pipes);
if (!is_resource($process)) {
    die(json_encode(['success' => false, 'error' => 'Nepodarilo sa spustiť Python proces.']));
}
fclose($pipes[0]);
$output = stream_get_contents($pipes[1]);
$errorOutput = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$return_value = proc_close($process);

// Odstránenie dočasného súboru
unlink($tempFilePath);

// Spracovanie výstupu
$driveUrl = trim($output);
if (empty($driveUrl)) {
    die(json_encode(['success' => false, 'error' => "Chyba pri nahrávaní na Google Drive. Python output: $output; Error output: $errorOutput"]));
}

// Vygenerovanie unikátnej URL pre obrázok
$uniqueUrl = uniqid('gallery_', true);

// Uloženie informácií do databázy
$stmt = $conn->prepare("INSERT INTO gallery.gallery_images (user_id, role_id, image_url, unique_url, category, vehicle, is_best)
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissssi", $userId, $role_id, $driveUrl, $uniqueUrl, $category, $vehicle, $isBest);
if ($stmt->execute()) {
    //echo json_encode(['success' => true, 'message' => 'Obrázok bol úspešne nahratý.', 'drive_url' => $driveUrl]);
} else {
    echo json_encode(['success' => false, 'error' => 'Chyba pri ukladaní obrázka: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>
