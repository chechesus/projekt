<?php
session_start(); // No whitespace before <?php

require_once 'config.php';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log the connection error
    die("Connection failed: " . $conn->connect_error);
}


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and has a valid role
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['role'])) {
    $_SESSION['loggedin'] = false;
    $_SESSION['role'] = 'guest'; // Default role
} else {
    // Optionally, you can add logic to refresh session or check role validity
}

try {
    $maxRetries = 3; // Maximum number of retries
    $retryDelay = 2; // Delay in seconds
    $attempt = 0;

    do {
        $result = mysqli_query($conn, "SELECT count(*) FROM users");

        if (!$result) {
            $attempt++;
            if ($attempt < $maxRetries) {
                // Log the error or handle it as needed
                error_log("Chyba pri spúšťaní dotazu: " . mysqli_error($conn) . ". Pokus číslo: $attempt. Čakám $retryDelay sekúnd pred ďalším pokusom.");
                sleep($retryDelay); // Wait for 2 seconds before retrying
            } else {
                throw new Exception("Chyba pri spúšťaní dotazu: " . mysqli_error($conn));
            }
        }
    } while (!$result && $attempt < $maxRetries);

    // If $result is true, you can proceed with your logic
    if ($result) {
        // Process the result
        $count = mysqli_fetch_array($result)[0];
        //echo "Number of users: " . $count;
    }

} catch (Exception $e) {
    echo "Chyba: " . $e->getMessage();
    $_SESSION["loggedin"] = false;
}

// Close the database connection
?>
