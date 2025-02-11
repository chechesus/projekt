<?php
require_once 'api\session.php';

// Define the admin's details
$admin_name = 'janik1'; // Replace with the admin's username
$admin_email = 'ferjancik12@gmail.com'; // Replace with the admin's email
$admin_password = '2005skhunteRS;'; // Replace with the desired admin password
$nick = 'Janco';
// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Insert the admin into the database
try {
    $stmt = $conn->prepare("INSERT INTO acces.moderators (name, nick , email, password, role_id) VALUES (?, ?, ?, ?, ?)");
    $role_id = 3; // Role ID for admin
    $stmt->bind_param("ssssi", $admin_name, $nick, $admin_email, $hashed_password, $role_id);
    $stmt->execute();
    echo '<script> alert("Vložené úspešne")</script>';
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
