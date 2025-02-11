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
?>