<?php
$success = false;
require_once 'config.php';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo '<p>Problem s pripojením k databáze</p>';
}
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION["loggedin"] = true;
}
?>