<?php
require_once __DIR__ .'/config.php';
$conn = new mysqli($host, $username, $password, $dbname);
if (!$conn) {
    $conn = var_dump(__DIR__ .'/config.php');
    echo $conn;
    die("Pripojenie zlyhalo: " . mysqli_connect_error());

}
