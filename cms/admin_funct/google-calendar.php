<?php
session_start();
require_once 'C:\xampp\htdocs\projekt\vendor\autoload.php';

// Set up Google Client
$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\projekt\vlaky_jsonisko.json');//API kulčš
$client->setRedirectUri('http://localhost/projekt/cms/admin_funct/google-callback.php');  // Replace with your redirect URI

// Check if the user is authenticated
if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
    header('Location: google-login.php');
    exit();
}

try {
    $client->setAccessToken($_SESSION['access_token']);
    
    // Verify access token is still valid
    if ($client->isAccessTokenExpired()) {
        throw new Exception('Access token has expired');
    }
} catch (Exception $e) {
    error_log('Google Calendar access error: ' . $e->getMessage());
    header('Location: google-login.php?error=token_expired');
    exit();
}


// Create Google Calendar service
$calendarService = new Google_Service_Calendar($client);

try {
    // Get the list of calendars
    $calendarList = $calendarService->calendarList->listCalendarList();
    
    // Verify we got calendar data
    if (empty($calendarList->getItems())) {
        throw new Exception('No calendars found for this account');
    }
    
    // Get the ID of the first calendar in the list
    $calendarId = $calendarList->getItems()[0]->getId();
} catch (Exception $e) {
    error_log('Google Calendar error: ' . $e->getMessage());
    $_SESSION['calendar_error'] = 'Failed to load calendar data. Please try again.';
    header('Location: google-login.php');
    exit();
}


// Generate the embed URL with error handling
if (empty($calendarId)) {
    $_SESSION['calendar_error'] = 'Invalid calendar ID. Please try again.';
    header('Location: google-login.php');
    exit();
}

$embedUrl = "https://calendar.google.com/calendar/embed?src=" . urlencode($calendarId) . "&ctz=CET";  // Change timezone if needed


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embedded Google Calendar</title>
    <style>
        iframe {
            width: 100%;
            height: 800px;
            border: none;
        }
    </style>
</head>
<body>
    <h2>Môj kalendár</h2>
    <iframe src="<?php echo $embedUrl; ?>" frameborder="0" scrolling="no"></iframe>
</body>
</html>
