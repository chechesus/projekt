<?php
require_once 'session.php';
setcookie("loggedin", "", time() - 3600, "/"); // Expire the cookie
session_destroy();// clearing session tokens

header("Location: /projekt/index.php");// redirect to homepage
?>
