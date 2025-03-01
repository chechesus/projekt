<?php
$success = false;
require_once 'session.php';
require 'C:\xampp\htdocs\projekt\vendor\autoload.php'; // Path to Composer autoload

global $conn; // Ensure $conn is accessible

$name = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : null;
$nick = isset($_POST['nick']) ? htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8') : null;
$mail = isset($_POST['mail']) ? htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8') : null;
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : null;
$password_check = isset($_POST['password_check']) ? htmlspecialchars($_POST['password_check'], ENT_QUOTES, 'UTF-8') : null;
$tel = isset($_POST['tel']) ? htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8') : null;


// Check passwords if they match
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
    window.location.href = "../reg_form.php";
    </script>';
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// INSERT into database
$sql = "INSERT INTO users (name, nick, email, tel, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssis', $name, $nick, $mail, $tel, $hashed_password);
$success = $stmt->execute(); // Execute len raz!

if ($success) {
    $_SESSION["loggedin"] = true;
    $_SESSION["role"] = "user";

    $mailer = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        // SMTP Configuration
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'jankoferjancik@gmail.com';
        $mailer->Password = 'taqt hjjc trvn tosj';
        $mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port = 587;

        // Sender and recipient
        $mailer->setFrom('jankoferjancik@gmail.com', 'Vlaky prihlasenie');
        $mailer->addAddress($mail, $name);

        // Email content
        $mailer->isHTML(true);
        $mailer->Subject = 'Welcome to Our Site!';
        $mailer->Body = "Ahoj $name,<br><br>Ďakujeme že ste sa zaregistrovali na Vlaky.sk!";
        $mailer->AltBody = "Ahoj $name,\n\nVaša registrácia prebehla úspešne!";
        // SMTPDebug nastavujeme len pre debugovanie, v produkcii odporúčame vypnúť
        $mailer->SMTPDebug = 0;
        $mailer->send();
    } catch (Exception $e) {
        error_log("Registration email error: " . $e->getMessage());
    }

    echo '<script>window.location.href = "/projekt/message_handlers/true_reg.html";</script>';
    exit;
} else {
    try {
        $mailer = new PHPMailer\PHPMailer\PHPMailer(true);
        // SMTP Configuration
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'jankoferjancik@gmail.com';
        $mailer->Password = 'taqt hjjc trvn tosaj';
        $mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port = 587;

        // Sender and recipient
        $mailer->setFrom('jankoferjancik@gmail.com', 'Vlaky prihlasenie');
        $mailer->addAddress($mail, $name);

        // Email content
        $mailer->isHTML(true);
        $mailer->Subject = 'Welcome to Our Site!';
        $mailer->Body = "Ahoj $name,<br><br>:( Ľutujeme ale vaša registrácia na Vlaky.sk nebola úspešná!";
        $mailer->AltBody = "Ahoj $name,\n\nProsím skúste to ešte raz ";
        $mailer->SMTPDebug = 0;
        $mailer->send();
    } catch (Exception $e) {
        error_log("Registration email error: " . $e->getMessage());
    }
    echo '<p>Registrácia zlyhala. Skontrolujte, či sú všetky údaje správne.</p>';
    session_destroy();
    echo '<script>window.location.href = "/message_handlers/not_reg.html";</script>';
}

$stmt->close();
$conn->close();
?>
