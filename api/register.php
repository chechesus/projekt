<?php
$success = false;
require_once 'config.php';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo '<p>Problem s pripojením k databáze</p>';
}
//POST-y z formulára
$name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$nick = filter_input(INPUT_POST, 'nick', FILTER_SANITIZE_STRING);
$mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING); 
$password_check = filter_input(INPUT_POST, 'password_check', FILTER_SANITIZE_STRING);
$tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_NUMBER_INT);

// Check if all required fields are filled
if (empty($name) || empty($nick) || empty($mail) || empty($password) || empty($password_check)) {
    echo '<p>Vyplňte všetky povinné polia.</p>';
}
// Check paswords if they match - redirect to match or not match screen
if ($password !== $password_check) {
    echo '<script>
    window.location.href = "not_reg.html";
    </script>';
    echo '<p style="color: red;">Heslá sa nezhodujú.</p>';
    exit;
}
//openssl_encrypt()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// INSERT do databázy
$sql = "INSERT INTO users (name, nick, mail, tel, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssis', $name, $nick, $mail, $tel, $hashed_password);

// Execute the prepared statement
if ($stmt->execute()) {
    $success = true;
}


// Kontrola
if ($success) {
    echo 
    '<script>
        window.location.href = "/projekt/message_handlers/true_reg.html";
    </script>';
    exit;
} else {
    echo 
    '<script>
        window.location.href = "/projekt/message_handlers/not_reg.html";
        alert("BAD request");
    </script>';
}
//Zavretie príkazov a spojenia
$stmt->close();
$conn->close();
?>
