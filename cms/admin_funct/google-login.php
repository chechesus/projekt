<?php
require_once 'C:\xampp\htdocs\projekt\vendor\autoload.php';

// Set up Google Client
$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\projekt\vlaky_jsonisko.json');//API kulčš
$client->setRedirectUri('http://localhost/projekt/cms/admin_funct/google-callback.php');  // Replace with your redirect URI
$client->addScope(Google_Service_Calendar::CALENDAR_READONLY);  // Access Google Calendar

// Create the login URL and redirect user to it
$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();
?>
