<?php
require "../../../api/session.php";

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(["status" => "error", "message" => "Neplatný CSRF token"]);
    exit;
}

$searchQuery = $_POST['searchQuery'] ?? '';
$roleFilter = $_POST['roleFilter'] ?? '';
$blockFilter = $_POST['blockFilter'] ?? '';

// Vytvorenie UNION ALL query
$sql = "
    SELECT * FROM (
    SELECT ID, name, email, created, last_logg, role_id, blocked FROM data.users
    UNION ALL
    SELECT ID, name, email, created, last_logg, role_id, blocked FROM acces.moderators
    UNION ALL
    SELECT ID, name, email, created, last_logg, role_id, blocked FROM acces.admins
    ) AS all_users
    WHERE 1=1
";

// Pole pre podmienky
$conditions = [];
$params = [];
$types = "";

// 🔎 Filtrovanie podľa vyhľadávania
if (!empty($searchQuery)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR ID LIKE ?)";
    $searchTerm = '%' . $searchQuery . '%';
    array_push($params, $searchTerm, $searchTerm, $searchTerm);
    $types .= "sss";
}

// 🔎 Filtrovanie podľa role
if (!empty($roleFilter)) {
    $sql .= " AND role_id = ?";
    $params[] = $roleFilter;
    $types .= "i";
}

// 🔎 Filtrovanie podľa blokovania
if ($blockFilter !== '') {
    $sql .= " AND blocked = ?";
    $params[] = ($blockFilter === 'blocked') ? 1 : 0;
    $types .= "i";
}
if (!empty($conditions)) {
    $sql .= " HAVING " . implode(" AND ", $conditions);
}

// Priprav SQL dotaz
$stmt = $conn->prepare($sql);

// Ak máme parametre, pridaj ich
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Spusť dotaz
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);

// Zavri pripojenie
$stmt->close();
$conn->close();
?>
