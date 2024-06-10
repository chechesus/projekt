<?php
include 'config.php';//config fie

session_start();
session_unset();
session_destroy();// clearing session tokens

header("Location: /projekt/index.php");// redirect to homepage
?>
