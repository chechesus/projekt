<?php
header("Location: /auth_check.php");
require_once '../api/session.php';

$sql = "SELECT country, count(country)as pocet  FROM user_locations
        group by country;";
$result = $conn->query($sql);

$visitorsData = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $visitorsData[$row['country']] = $row['pocet'];
    }
}
echo json_encode($visitorsData);
$conn->close();
?>