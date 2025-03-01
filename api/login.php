<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
require_once '/xampp/htdocs/projekt/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$identifier  = isset($_POST['identifier']) ? htmlspecialchars($_POST['identifier'], ENT_QUOTES, 'UTF-8') : null;
$password    = isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : null;
$remember_me = isset($_POST['remember_me']) ? (int) $_POST['remember_me'] : 0;
$adminEmail  = 'ferjancik1@spsjm.sk';

// Send logs via email
function sendAdminLog($subject, $body)
{
    global $adminEmail;
    $mailer = new PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Port = 587;
        $mailer->Username = 'jankoferjancik@gmail.com';
        $mailer->Password = 'taqt hjjc trvn tosj';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->CharSet = 'UTF-8';

        $mailer->setFrom('jankoferjancik@gmail.com', 'Vlaky prihlasenie');
        $mailer->addAddress($adminEmail);

        $mailer->isHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body = $body;
        $mailer->AltBody = strip_tags($body);

        $mailer->send();
    } catch (Exception $e) {
        error_log("Admin log email error: " . $mailer->ErrorInfo);
    }
}

// Get user data from multiple tables
function get_user_data($identifier)
{
    global $conn;

    $tables = [
        ['table' => 'acces.admins', 'role_id' => 1],
        ['table' => 'acces.moderators', 'role_id' => 3],
        ['table' => 'data.users', 'role_id' => 2]
    ];

    foreach ($tables as $entry) {
        $stmt = $conn->prepare("SELECT id, name, password FROM {$entry['table']} WHERE name = ? OR email = ?");
        if (!$stmt) {
            continue;
        }
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            $user['role_id'] = $entry['role_id'];
            return $user;
        }
    }
    return null;
}

// Main login logic
if ($identifier && $password) {
    $user = get_user_data($identifier);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Get entry_id and role_id from acces.all_entry
            $stmt = $conn->prepare("SELECT unified_id FROM acces.all_entry WHERE user_id = ? AND role_id = ?");
            $stmt->bind_param("ii", $user['id'], $user['role_id']);
            $stmt->execute();
            $stmt->bind_result($entry_id);
            $stmt->fetch();
            $stmt->close();

            if (!$entry_id) {
                die("Chyba: entry_id sa nenašlo.");
            }

            // Generate CSRF token
            $csrf_token = bin2hex(random_bytes(32));

            // Determine expiration time based on "remember me"
            $expires_at = new DateTime();
            $expires_at->modify($remember_me ? '+30 days' : '+24 hours');

            // Save CSRF token to database
            $stmt = $conn->prepare("INSERT INTO acces.session_tokens (entry_id, token, expires_at) VALUES (?, ?, ?)");
            $expires_at_string = $expires_at->format('Y-m-d H:i:s');
            $stmt->bind_param("iss", $entry_id, $csrf_token, $expires_at_string);
            $stmt->execute();
            $stmt->close();

            // Save CSRF token to session
            $_SESSION['csrf_token'] = $csrf_token;

            // Set user session
            $_SESSION['loggedin'] = true;
            $_SESSION['userid']   = $user['id'];
            $_SESSION['role_id']  = $user['role_id'];
            $_SESSION['name']     = $user['name'];


            // Handle remember me
            if ($remember_me) {
                setcookie('remember_me', $user['id'], time() + 2592000, '/projekt/'); // 30 days
                setcookie('identifier', $identifier, time() + 2592000, '/projekt/');
            } else {
                setcookie('remember_me', '', time() - 3600, '/projekt/');
                setcookie('identifier', '', time() - 3600, '/projekt/');
            }

            // Log successful login
            sendAdminLog("Úspešné prihlásenie používateľa", "Meno: {$identifier}");

            // Redirect based on role
            switch ($user['role_id']) {
                case 1:
                    header('Location: /projekt/cms/admin.php');
                    break;
                case 2:
                    header('Location: /projekt/index.php');
                    break;
                default:
                    header('Location: /projekt/cms/moderator_dashboard.php');
                    break;
            }
            exit;
        } else {
            // Invalid password
            sendAdminLog("Neúspešný pokus o prihlásenie", "Neplatné heslo pre identifikátor: {$identifier}");
            header('Location: /projekt/Login_form.php?error=InvalidPassword');
        }
    } else {
        // User not found
        sendAdminLog("Neúspešný pokus o prihlásenie", "Používateľ s identifikátorom: {$identifier} nebol nájdený.");
        header('Location: /projekt/Login_form.php?error=UserNotFound');
    }
} else {
    header('Location: /projekt/Login_form.php?error=IncompleteFields');
}

$conn->close();
exit;
