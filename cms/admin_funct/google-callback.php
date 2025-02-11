<?php
require_once 'C:\xampp\htdocs\projekt\vendor\autoload.php';

// Set up Google Client
$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\projekt\vlaky_jsonisko.json');//API kulčš
$client->setRedirectUri('http://localhost/projekt/cms/admin_funct/google-callback.php');  // Replace with your redirect URI

// Check if authorization code is present
if (isset($_GET['code'])) {
    // Exchange authorization code for access token
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();

    // Redirect to the page where you will display the calendar
    header('Location: google-calendar.php');
    exit();
}
?>
