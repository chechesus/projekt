<?php
$success = false;
require_once 'config.php';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
    echo '<p>Problem s pripojením k databáze</p>';
}

// Set the cookie expiration time (e.g., 1 hour)
$cookieExpiration = time() + 3600;

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"]!== true) {
    $_SESSION["loggedin"] = true;

    // Set a cookie to store the user's login status
    setcookie("loggedin", true, $cookieExpiration, "/");
}

// Check if the cookie is set
if (isset($_COOKIE["loggedin"]) && $_COOKIE["loggedin"] === true) {
    $_SESSION["loggedin"] = true;
}
?>