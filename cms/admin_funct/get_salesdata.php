<?php
require_once '../api/session.php';

$sql = "
    SELECT DATE_FORMAT(created, '%Y-%m-%d') AS date, COUNT(*) AS registrations
    FROM data.users
    GROUP BY DATE(created)
    UNION ALL
    SELECT DATE_FORMAT(last_logg, '%Y-%m-%d') AS date, COUNT(*) AS logins
    FROM data.users
    WHERE last_logg IS NOT NULL
    GROUP BY DATE(last_logg)
    ORDER BY date ASC
";

$result = $conn->query($sql);

// Debugging: Overenie, či SQL dotaz funguje
if (!$result) {
    die("SQL error: " . $conn->error);
}

$data = [];
$categories = [];
$registrations = [];
$logins = [];

// Debugging: Načítané dáta
//echo "<pre>SQL Result Data:\n";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        print_r($row); // Debugging: Výpis riadkov zo SQL
        $date = $row['date'];
        $count = (int)$row['registrations'] ?: (int)$row['logins'];

        if (!isset($data[$date])) {
            $data[$date] = ['registrations' => 0, 'logins' => 0];
        }

        if (isset($row['registrations'])) {
            $data[$date]['registrations'] = $count;
        } else {
            $data[$date]['logins'] = $count;
        }
    }
} else {
    echo "No data found in SQL query.\n";
}
//echo "</pre>";

// Vyplnenie medzier medzi dátami
$startDate = new DateTime(array_key_first($data) ?? date('Y-m-d'));
$endDate = new DateTime(array_key_last($data) ?? date('Y-m-d'));

$dateInterval = new DateInterval('P1D'); // Denný interval
$dateRange = new DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day'));

foreach ($dateRange as $date) {
    $formattedDate = $date->format('Y-m-d');
    $categories[] = $formattedDate;

    $registrations[] = $data[$formattedDate]['registrations'] ?? 0;
    $logins[] = $data[$formattedDate]['logins'] ?? 0;
}

// Debugging: Skontrolovať dáta pre graf
echo "<pre>Processed Data for Chart:\n";
echo "Categories (X-Axis):\n";
print_r($categories);
echo "Registrations (Series 1):\n";
print_r($registrations);
echo "Logins (Series 2):\n";
print_r($logins);
echo "</pre>";

// Formátovanie výsledného objektu
$sales_chart_options = [
    "series" => [
        ["name" => "Registrations", "data" => $registrations],
        ["name" => "Logins", "data" => $logins],
    ],
    "chart" => [
        "height" => 300,
        "type" => "area",
        "toolbar" => ["show" => false],
    ],
    "legend" => ["show" => false],
    "colors" => ["#0d6efd", "#20c997"],
    "dataLabels" => ["enabled" => false],
    "stroke" => ["curve" => "smooth"],
    "xaxis" => [
        "type" => "datetime",
        "categories" => $categories,
    ],
    "tooltip" => [
        "x" => ["format" => "MMMM yyyy"],
    ],
];

// Debugging: Výsledný JSON objekt
global $sales_chart_options;
echo "<pre>Final JSON:\n";
print_r($sales_chart_options);
echo "</pre>";

// Poslanie JSON odpovede
header('Content-Type: application/json');
echo json_encode($sales_chart_options);
echo '<script>alert(' . json_encode($sales_chart_options) . ');</script>';
$conn->close();
?>