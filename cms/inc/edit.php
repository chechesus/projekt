<?php
require_once '../api/session.php'; // Ensure session is started

// Check if user is logged in and has the correct role
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

header("Location: /auth_check.php");

function profile_pic_size_check($file_size) {
    $max_file_size = 2 * 1024 * 1024; // 2 MB

    if ($file_size > $max_file_size) {
        echo "File is too large. Maximum size allowed is 2 MB.";
        exit;
    }
}
?>
