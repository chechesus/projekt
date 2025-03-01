<?php
require_once 'api/session.php';
header('Content-Type: application/json');

// Overíme, či je požiadavka typu POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Neplatná metóda požiadavky.']);
    exit;
}

// Získame comment_id a vote typ z POST dát
$commentId = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
$vote = isset($_POST['vote']) ? $_POST['vote'] : '';

if (!$commentId) {
    echo json_encode(['error' => 'Neplatné ID komentára.']);
    exit;
}

if ($vote !== 'like' && $vote !== 'dislike') {
    echo json_encode(['error' => 'Neplatný typ hlasu.']);
    exit;
}

// Určíme, ktorý stĺpec budeme aktualizovať
$column = $vote === 'like' ? 'likes' : 'dislikes';

// Aktualizácia hlasu v databáze
$sql = "UPDATE comments SET $column = $column + 1 WHERE comment_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Chyba v príprave SQL: " . $conn->error);
    echo json_encode(['error' => 'Chyba na serveri.']);
    exit;
}
$stmt->bind_param("i", $commentId);
if (!$stmt->execute()) {
    error_log("Chyba pri vykonávaní SQL: " . $stmt->error);
    echo json_encode(['error' => 'Chyba pri aktualizácii hlasu.']);
    exit;
}
$stmt->close();

// Načítanie aktuálnych počtov hlasov
$sql = "SELECT likes, dislikes FROM comments WHERE comment_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Chyba v príprave SQL: " . $conn->error);
    echo json_encode(['error' => 'Chyba na serveri.']);
    exit;
}
$stmt->bind_param("i", $commentId);
if (!$stmt->execute()) {
    error_log("Chyba pri vykonávaní SQL: " . $stmt->error);
    echo json_encode(['error' => 'Chyba pri načítaní hlasov.']);
    exit;
}
$result = $stmt->get_result();
if ($result->num_rows < 1) {
    echo json_encode(['error' => 'Komentár nebol nájdený.']);
    exit;
}
$row = $result->fetch_assoc();
$stmt->close();

echo json_encode([
    'success' => true,
    'likes' => (int)$row['likes'],
    'dislikes' => (int)$row['dislikes']
]);

$conn->close();
?>
