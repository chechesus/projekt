<?php
session_start();
require_once '../api/session.php'; // Ensure session is started

// Sample notifications data (this should be replaced with actual data fetching logic)
$notifications = [
    ['message' => 'You have a new message.'],
    ['message' => 'Your profile has been updated.'],
    ['message' => 'New comment on your post.']
];

// Return notifications as JSON
header('Content-Type: application/json');
echo json_encode($notifications);
?>
