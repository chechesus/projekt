<?php
session_start();
require_once 'C:\xampp\htdocs\projekt\vendor\autoload.php';

// Handle error messages from callback
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
    if ($error === 'auth_failed') {
        $errorMessage = 'Authentication failed. Please try again.';
    } elseif ($error === 'no_code') {
        $errorMessage = 'Authorization code missing. Please try again.';
    }
}

// Set up Google Client

$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\projekt\vlaky_jsonisko.json');//API kulčš
$client->setRedirectUri('http://localhost/projekt/cms/admin_funct/google-callback.php');  // Replace with your redirect URI
$client->addScope(Google_Service_Calendar::CALENDAR_READONLY);  // Access Google Calendar

// Create the login URL and redirect user to it
$authUrl = $client->createAuthUrl();
if (isset($errorMessage)) {
    // Store error message in session for display after redirect
    $_SESSION['google_auth_error'] = $errorMessage;
}
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();

?>
