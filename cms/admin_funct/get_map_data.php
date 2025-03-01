<?php
require_once __DIR__ . '/../../api/session.php';
header('Content-Type: application/json');

$days = isset($_GET['days']) ? (int)$_GET['days'] : 7; // default 7 dní

$sql = "SELECT country, COUNT(*) AS cnt
        FROM data.user_locations
        WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
        GROUP BY country";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $days);
$stmt->execute();
$result = $stmt->get_result();

$mapData = [];
while ($row = $result->fetch_assoc()) {
    // Predpokladáme, že 'country' už obsahuje dvojpísmenový kód (napr. 'US')
    $countryCode = $row['country']; 
    $count = (int)$row['cnt'];
    $mapData[$countryCode] = $count;
}
echo json_encode($mapData);
