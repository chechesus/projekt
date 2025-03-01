<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
header('Content-Type: application/json');

require_once 'C:\xampp\htdocs\projekt\api\config.php'; // Pripojenie k databáze

// Načítanie prezývky z GET parametru
$nick = isset($_GET['nick']) ? trim($_GET['nick']) : '';

if (empty($nick)) {
    echo json_encode(['exists' => false]);
    exit;
}

// Pripravený dotaz na kontrolu existencie prezývky
$stmt = $conn->prepare("SELECT ID FROM data.users WHERE nick = ?");
$stmt->bind_param("s", $nick);
$stmt->execute();
$result = $stmt->get_result();

$exists = $result->num_rows > 0;

$stmt->close();
$conn->close();

echo json_encode(['exists' => $exists]);
?>
