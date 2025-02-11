<?php
session_start();
require_once 'C:\xampp\htdocs\projekt\vendor\autoload.php';

// Set up Google Client
$client = new Google_Client();
$client->setAuthConfig('C:\xampp\htdocs\projekt\vlaky_jsonisko.json');//API kulčš
$client->setRedirectUri('http://localhost/projekt/cms/admin_funct/google-callback.php');  // Replace with your redirect URI

// Check if the user is authenticated
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    header('Location: google-login.php');
    exit();
}

// Create Google Calendar service
$calendarService = new Google_Service_Calendar($client);

// Get the list of calendars
$calendarList = $calendarService->calendarList->listCalendarList();

// You can fetch the ID of the first calendar in the list
$calendarId = $calendarList->getItems()[0]->getId();  // Get the first calendar

// Generate the embed URL
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
    <h2>My Google Calendar</h2>
    <iframe src="<?php echo $embedUrl; ?>" frameborder="0" scrolling="no"></iframe>
</body>
</html>
