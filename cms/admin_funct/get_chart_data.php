<?php
require_once __DIR__ . '/../../api/session.php';
header('Content-Type: application/json');

$range = $_GET['range'] ?? 'all';
$start = $_GET['start'] ?? '';
$end   = $_GET['end']   ?? '';
error_log("Query range: " . $range . ", Start date: " . $start . ", End date: " . $end);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $timeRange = $_POST['timeRange'] ?? 'not set';
    error_log("Selected time range: " . $timeRange);
}

// 1) Zistíme časové rozmedzie $startDate, $endDate podľa filtra
switch ($range) {
  case 'today':
    $startDate = date('Y-m-d');
    $endDate   = date('Y-m-d');
    break;
  case 'week':
    // Posledných 7 dní vrátane dneška
    $startDate = date('Y-m-d', strtotime('-6 days'));
    $endDate   = date('Y-m-d');
    break;
  case 'year':
    // Od začiatku tohto roka
    $startDate = date('Y-01-01');
    $endDate   = date('Y-m-d');
    break;
  case 'all':
    // Od "začiatku vekov" do dneška
    $startDate = '1970-01-01';
    $endDate   = date('Y-m-d');
    break;
  case 'custom':
    // Používateľ zadal vlastný interval
    if ($start && $end) {
      $startDate = $start;
      $endDate   = $end;
    } else {
      // Ak nič nevyplnil, fallback na "all"
      $startDate = '1970-01-01';
      $endDate   = date('Y-m-d');
    }
    break;
  default:
    // Fallback
    $startDate = '1970-01-01';
    $endDate   = date('Y-m-d');
    break;
}

// 2) Vyberieme počet registrácií za každý deň
$sqlReg = "SELECT DATE(created) as datum, COUNT(*) as cnt
           FROM data.users
           WHERE DATE(created) BETWEEN ? AND ?
           GROUP BY DATE(created)
           ORDER BY datum ASC";
$stmt1 = $conn->prepare($sqlReg);
$stmt1->bind_param("ss", $startDate, $endDate);
$stmt1->execute();
$result1 = $stmt1->get_result();

$registeredArray = [];
while ($row = $result1->fetch_assoc()) {
  $registeredArray[$row['datum']] = (int)$row['cnt'];
}
$stmt1->close();

// 3) Vyberieme počet prihlásení za každý deň
$sqlLog = "SELECT DATE(last_logg) as datum, COUNT(*) as cnt
           FROM data.users
           WHERE DATE(last_logg) BETWEEN ? AND ?
           GROUP BY DATE(last_logg)
           ORDER BY datum ASC";
$stmt2 = $conn->prepare($sqlLog);
$stmt2->bind_param("ss", $startDate, $endDate);
$stmt2->execute();
$result2 = $stmt2->get_result();
error_log("Registrácie - počet riadkov: " . $result1->num_rows);
error_log("Prihlásenia - počet riadkov: " . $result2->num_rows);

$loggedArray = [];
while ($row = $result2->fetch_assoc()) {
  $loggedArray[$row['datum']] = (int)$row['cnt'];
}
$stmt2->close();

// 4) Vybudujeme timeline od $startDate po $endDate, aby boli dáta súvislé
$period = new DatePeriod(
  new DateTime($startDate),
  new DateInterval('P1D'),
  (new DateTime($endDate))->modify('+1 day') // +1 day aby sme zahrnuli aj endDate
);

$categories = [];
$registeredData = [];
$loggedData = [];

foreach ($period as $dateObj) {
  $d = $dateObj->format('Y-m-d');
  $categories[]       = $d;
  $registeredData[]   = $registeredArray[$d] ?? 0;
  $loggedData[]       = $loggedArray[$d] ?? 0;
}

// 5) Výstup v JSON formáte
echo json_encode([
  "categories" => $categories,
  "registered" => $registeredData,
  "logged"     => $loggedData
]);

$conn->close();
