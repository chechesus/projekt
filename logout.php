<?php
session_start();
// Check if a session exists
if(isset($_SESSION['username'])) {
  session_unset();
  session_destroy();
  //redirect
  header("Location: reg_form.php");
  exit();
} else {
  header("Location: index.php");
  exit();
}
?>
