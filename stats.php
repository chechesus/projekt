<?php    
require_once 'api/session.php'; 

function loged() {

    global $conn;
    global $countis;
    $stmt = $conn->prepare("SELECT COUNT(*) as user_count FROM users WHERE DATE(last_logg) = CURRENT_DATE;");    
    $stmt->execute();
    $stmt->bind_result($countis);
    $stmt->fetch();
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

   

    // Output the result
    echo $countis;
}

function registrated() {
    global $conn;
    global $countis;
    $stmt = $conn->prepare("SELECT COUNT(*) as user_count FROM users WHERE DATE(created) = CURRENT_DATE;");    
    $stmt->execute();
    $stmt->bind_result($countis);
    $stmt->fetch();
    $conn->close();
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

   

    // Output the result
    echo $countis;
    
}
function getRamUsagePercentage() {
    $wmi = new COM('WinMgmts:\\\\.');
    $memory = $wmi->ExecQuery('SELECT TotalVisibleMemorySize, FreePhysicalMemory FROM Win32_OperatingSystem');

    foreach ($memory as $mem) {
        $totalMemory = $mem->TotalVisibleMemorySize; // Total RAM in KB
        $freeMemory = $mem->FreePhysicalMemory; // Free RAM in KB
    }

    $usedMemory = $totalMemory - $freeMemory;
    $ramUsagePercentage = ($usedMemory / $totalMemory) * 100;

    return round($ramUsagePercentage, 2); // Return percentage rounded to 2 decimal places
}

// Function to get CPU load percentage
function getCpuLoadPercentage() {
    $wmi = new COM('WinMgmts:\\\\.');
    $cpus = $wmi->ExecQuery('SELECT LoadPercentage FROM Win32_Processor');

    $totalLoad = 0;
    $cpuCount = 0;

    foreach ($cpus as $cpu) {
        $totalLoad += $cpu->LoadPercentage; // CPU load percentage for each core
        $cpuCount++;
    }

    $cpuLoadPercentage = $totalLoad / $cpuCount; // Average CPU load across all cores

    return round($cpuLoadPercentage, 2); // Return percentage rounded to 2 decimal places
}

?>