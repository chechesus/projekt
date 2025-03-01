<?php
require_once '/xampp/htdocs/projekt/api/session.php';

// Získanie používateľského mena z GET parametra
$username = isset($_GET['username']) ? trim($_GET['username']) : '';

// Pripravíme odpoveď
$response = ['exists' => false];

if ($username) {
    // Použijeme prepared statement na bezpečné vykonanie dotazu
    $stmt = $conn->prepare("SELECT COUNT(*) FROM data.users WHERE nick = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Ak nájdeme aspoň jeden záznam, nastavíme "exists" na true
    $response['exists'] = $count > 0;
}

// Nastavíme Content-Type na JSON
header('Content-Type: application/json');

// Odošleme odpoveď
echo json_encode($response);
