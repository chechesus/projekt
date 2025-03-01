<?php
require "../../../api/session.php";

$stmt = $conn->prepare("SELECT * FROM users ORDER BY ID ASC");
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
echo json_encode($users);
?>
