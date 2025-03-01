<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
header('Content-Type: application/json');

require_once 'C:\xampp\htdocs\projekt\api\config.php'; // DB pripojenie

// Overenie, či je POST požiadavka
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Neplatná metóda požiadavky."]);
    exit;
}

// Získanie údajov z POST
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$postedToken = $_POST['csrf_token'] ?? '';

if ($userId <= 0 || empty($postedToken)) {
    echo json_encode(["status" => "error", "message" => "Neplatné údaje."]);
    exit;
}

// Overenie CSRF tokenu – predpokladáme, že token je uložený v session
if ($postedToken !== $_SESSION['csrf_token']) {
    echo json_encode(["status" => "error", "message" => "Neplatný CSRF token."]);
    exit;
}

$deleted = false;
$errorMessage = "";

// Funkcia na vykonanie vymazania
function executeDelete($conn, $table, $userId) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE ID = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    return $affected > 0;
}

// Pokusíme sa najprv vyhľadať používateľa v tabuľke data.users
$stmt = $conn->prepare("SELECT role_id FROM data.users WHERE ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Používateľ je v data.users
    $row = $result->fetch_assoc();
    $roleId = (int)$row['role_id'];
    $stmt->close();
    if ($roleId === 2) {
        // Normálny používateľ
        $deleted = executeDelete($conn, "data.users", $userId);
    } elseif ($roleId === 1) {
        // Admin – predpokladáme, že admin účty sú v acces.admins
        $deleted = executeDelete($conn, "acces.admins", $userId);
    } elseif ($roleId === 3) {
        // Moderátor – predpokladáme, že moderátorské účty sú v acces.moderators
        $deleted = executeDelete($conn, "acces.moderators", $userId);
    } else {
        $errorMessage = "Neznámy role_id.";
    }
} else {
    $stmt->close();
    // Ak nie je v data.users, skúšame v acces.admins
    $stmtAdmin = $conn->prepare("SELECT ID FROM acces.admins WHERE ID = ?");
    $stmtAdmin->bind_param("i", $userId);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();
    if ($resultAdmin->num_rows > 0) {
        $stmtAdmin->close();
        $deleted = executeDelete($conn, "acces.admins", $userId);
    } else {
        $stmtAdmin->close();
        // Skúšame v acces.moderators
        $stmtMod = $conn->prepare("SELECT ID FROM acces.moderators WHERE ID = ?");
        $stmtMod->bind_param("i", $userId);
        $stmtMod->execute();
        $resultMod = $stmtMod->get_result();
        if ($resultMod->num_rows > 0) {
            $stmtMod->close();
            $deleted = executeDelete($conn, "acces.moderators", $userId);
        } else {
            $stmtMod->close();
            $errorMessage = "Používateľ s daným ID sa nenašiel.";
        }
    }
}

if ($deleted) {
    echo json_encode(["status" => "success"]);
} else {
    if (empty($errorMessage)) {
        $errorMessage = "Vymazávanie sa nepodarilo.";
    }
    echo json_encode(["status" => "error", "message" => $errorMessage]);
}

$conn->close();
?>
