<?php
require '../../api/session.php'; // Uisti sa, že tu máš pripojenie k DB

if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['userid']; // ID aktuálne prihláseného používateľa
$role_id = $_SESSION['role_id'];

$query = "SELECT * FROM acces.moderators";
$stmt = $conn->prepare($query);
//$stmt->bind_param("ii", $user_id, $role_id);
$stmt->execute();
$result = $stmt->get_result();

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

echo json_encode($contacts);
?>
