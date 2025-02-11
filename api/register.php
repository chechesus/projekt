<?php
$success = false;
require_once 'session.php';

global $conn; // Ensure $conn is accessible

$name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$nick = filter_input(INPUT_POST, 'nick', FILTER_SANITIZE_STRING);
$mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING); 
$password_check = filter_input(INPUT_POST, 'password_check', FILTER_SANITIZE_STRING);
$tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_NUMBER_INT);

// Check passwords if they match - redirect to match or not match screen
if ($password !== $password_check) {
    echo '<script>
    window.location.href = "not_reg.html";
    </script>';
    echo '<p style="color: red;">Heslá sa nezhodujú.</p>';
    exit;
}

// Check if the nickname already exists in the database
$checkNickSql = "SELECT COUNT(*) FROM data.users WHERE nick = ? UNION SELECT COUNT(*) FROM acces.moderators WHERE nick = ? UNION SELECT COUNT(*) FROM acces.admins WHERE nick = ?";
$stmt = $conn->prepare($checkNickSql);
$stmt->bind_param('sss', $nick, $nick, $nick);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    echo '<script>
    alert("Tento nick je už obsadený. Prosím, vyberte si iný.");
    window.location.href = "register_form.php"; // Redirect to the registration form
    </script>';
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// INSERT into database
$sql = "INSERT INTO users (name, nick, email, tel, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssis', $name, $nick, $mail, $tel, $hashed_password);
$success = $stmt->execute(); // Capture the success of the execution

// Check success
if ($success) {
    $_SESSION["loggedin"] = true;
    $_SESSION["role"] = "user";
    echo 
    '<script>
        window.location.href = "/projekt/message_handlers/true_reg.html";
    </script>';
    exit;
} else {
    echo '<p>Registrácia zlyhala. Skontrolujte, či sú všetky údaje správne.</p>';
    session_destroy();
    echo 
    '<script>
        window.location.href = "/projekt/message_handlers/not_reg.html";
    </script>';
}

$stmt->close();
$conn->close();
?>
