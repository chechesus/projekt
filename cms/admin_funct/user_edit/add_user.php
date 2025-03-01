<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
header('Content-Type: application/json');

// Získame odoslaný CSRF token a ID používateľa
$postedToken = $_POST['csrf_token'] ?? '';
$userId = $_SESSION['userid'] ?? null;
$roleId = $_SESSION['role_id'] ?? null;

if (!$userId || empty($postedToken)) {
    echo json_encode(["status" => "error", "message" => "Neplatný token alebo používateľ nie je prihlásený."]);
    exit;
}

require_once 'C:\xampp\htdocs\projekt\api\config.php'; // DB pripojenie

// Načítame CSRF token a dátum expirácie z DB pre daného používateľa
$stmtToken = $conn->prepare("SELECT token, expires_at FROM acces.session_tokens WHERE entry_id = 
                            (Select unified_id from acces.all_entry where user_id = ? and role_id = ?) order by id desc
                            LIMIT 1;");
$stmtToken->bind_param("ii", $userId, $roleId);
$stmtToken->execute();
$resultToken = $stmtToken->get_result();

if ($resultToken->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Token pre používateľa nebol nájdený."]);
    exit;
}

$rowToken = $resultToken->fetch_assoc();
$dbToken = $rowToken['token'];
$expireDate = $rowToken['expires_at'];
$stmtToken->close();

// Overíme, či sa odoslaný token zhoduje s tokenom z DB
if ($postedToken !== $dbToken) {
    echo json_encode(["status" => "error", "message" => "Neplatný CSRF token "]);
    exit;
}

// Skontrolujeme, či token nevypršal
$currentTime = new DateTime();
$tokenExpiry = new DateTime($expireDate);
if ($currentTime > $tokenExpiry) {
    echo json_encode(["status" => "error", "message" => "CSRF token vypršal."]);
    exit;
}

// Načítame údaje z formulára
$name     = trim($_POST['name'] ?? '');
$nick     = trim($_POST['nick'] ?? '');
$email    = trim($_POST['email'] ?? '');
$tel      = trim($_POST['tel'] ?? '');
$role_id  = (int)($_POST['role_id'] ?? 2);
$password = trim($_POST['password'] ?? '');

// Jednoduché validácie
if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Názov, email a heslo sú povinné."]);
    exit;
}

// Overiť, či email ešte neexistuje
$stmtCheck = $conn->prepare("SELECT ID FROM data.users WHERE email = ?");
$stmtCheck->bind_param("s", $email);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
if ($resultCheck->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Používateľ s týmto e-mailom už existuje."]);
    exit;
}
$stmtCheck->close();

// Hash hesla
$hashedPass = password_hash($password, PASSWORD_DEFAULT);

// Vložíme do databázy
$stmt = $conn->prepare("
    INSERT INTO data.users (name, nick, email, tel, role_id, password, blocked, created)
    VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
");
$stmt->bind_param("ssssis", $name, $nick, $email, $tel, $role_id, $hashedPass);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Chyba pri vkladaní do databázy."]);
}

$stmt->close();
$conn->close();
?>
