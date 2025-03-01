<?php
require_once '/xampp/htdocs/projekt/api/session.php';
$sql = "SELECT country, COUNT(*) as count
        FROM data.user_locations
        GROUP BY country
        ORDER BY count DESC";

$result = $conn->query($sql);

$series = [];
$labels = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['country'];
        $series[] = (int)$row['count'];
    }
}

header('Content-Type: application/json');
echo json_encode(['labels' => $labels, 'series' => $series]);
?>

