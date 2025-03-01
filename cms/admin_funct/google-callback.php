<?php
session_start();
require_once 'C:\xampp\htdocs\projekt\vendor\autoload.php';

// Set up Google Client

$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\projekt\vlaky_jsonisko.json');//API kulčš
$client->setRedirectUri('http://localhost/projekt/cms/admin_funct/google-callback.php');  // Replace with your redirect URI

// Check if authorization code is present
if (isset($_GET['code'])) {
    try {
        // Validate authorization code
        $authCode = filter_var($_GET['code'], FILTER_SANITIZE_STRING);
        if (empty($authCode)) {
            throw new Exception('Invalid authorization code');
        }

        // Verify credentials file exists and is readable
        $credentialsFile = 'C:\xampp\htdocs\projekt\vlaky_jsonisko.json';
        if (!file_exists($credentialsFile) || !is_readable($credentialsFile)) {
            throw new Exception('Invalid credentials file');
        }

        // Exchange authorization code for access token
        $accessToken = $client->authenticate($authCode);
        if (!is_array($accessToken)) {
            throw new Exception('Invalid access token format');
        }

        
        // Validate and store access token
        if (!isset($accessToken['access_token']) || empty($accessToken['access_token'])) {
            throw new Exception('Invalid access token received');
        }
        $_SESSION['access_token'] = $accessToken;
        $_SESSION['token_expires'] = time() + $accessToken['expires_in'];

        
        // Redirect to the page where you will display the calendar
        header('Location: google-calendar.php');
        exit();
    } catch (Exception $e) {
        // Log error and redirect to login
        error_log('Google authentication error: ' . $e->getMessage());
        header('Location: google-login.php?error=auth_failed');
        exit();
    }
} else {
    // No authorization code received
    header('Location: google-login.php?error=no_code');
    exit();
}

?>
