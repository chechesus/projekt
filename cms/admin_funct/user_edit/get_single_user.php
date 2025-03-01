<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "Chýba parameter user_id"]);
    exit;
}

$user_id = (int)$_GET['user_id'];
require_once 'C:\xampp\htdocs\projekt\api\config.php'; // spojenie s DB

$stmt = $conn->prepare("SELECT ID, name, nick, email, tel, role_id FROM data.users WHERE ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(["error" => "Používateľ nenájdený"]);
}
?>
