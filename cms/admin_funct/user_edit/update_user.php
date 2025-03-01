<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
header('Content-Type: application/json');

// Overenie CSRF tokenu
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(["status" => "error", "message" => "Neplatný CSRF token"]);
    exit;
}

// Skontrolovať, či máme potrebné polia
$user_id = $_POST['user_id'] ?? null;
$name    = trim($_POST['name'] ?? '');
$nick    = trim($_POST['nick'] ?? '');
$email   = trim($_POST['email'] ?? '');
$tel     = trim($_POST['tel'] ?? '');
$role_id = (int)($_POST['role_id'] ?? 2);
$password= trim($_POST['password'] ?? '');

// Jednoduché kontroly
if (!$user_id || !$name || !$email) {
    echo json_encode(["status" => "error", "message" => "Neúplné údaje."]);
    exit;
}

require_once 'C:\xampp\htdocs\projekt\api\config.php'; // DB pripojenie

// Ak nie je zadané nové heslo, načítame aktuálny hash zo správnej tabuľky
if ($password === "") {
    if ($role_id == 3) {
        $table = "acces.moderators";
    } elseif ($role_id == 1) {
        $table = "acces.admins";
    } else { // predpokladáme role_id = 2
        $table = "data.users";
    }
    $stmt = $conn->prepare("SELECT password FROM $table WHERE ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($currentHash);
    $stmt->fetch();
    $stmt->close();
    // Použijeme aktuálny hash, prípadne ak nie je nájdený, môžeš ho nastaviť na prázdny reťazec alebo iné predvolené hodnoty.
    $hashedPass = $currentHash;
} else {
    // Ak je zadané nové heslo, zahashujeme ho
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
}

// Na základe novej hodnoty role_id vykonáme update v príslušnej tabuľke
if ($role_id == 3) {
    $stmt = $conn->prepare("UPDATE acces.moderators 
        SET name = ?, nick = ?, email = ?, role_id = ?, password = ?
        WHERE ID = ?");
    $stmt->bind_param("sssisi", $name, $nick, $email, $role_id, $hashedPass, $user_id);
} elseif ($role_id == 1) {
    $stmt = $conn->prepare("UPDATE acces.admins 
        SET name = ?, nick = ?, email = ?, tel = ?, role_id = ?, password = ?
        WHERE ID = ?");
    $stmt->bind_param("ssssisi", $name, $nick, $email, $tel, $role_id, $hashedPass, $user_id);
} else { // pre role_id = 2
    $stmt = $conn->prepare("UPDATE data.users 
        SET name = ?, nick = ?, email = ?, tel = ?, role_id = ?, password = ?
        WHERE ID = ?");
    $stmt->bind_param("ssssisi", $name, $nick, $email, $tel, $role_id, $hashedPass, $user_id);
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Chyba pri update."]);
}
$stmt->close();
$conn->close();
?>
