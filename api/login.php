<?php
require_once 'session.php';

$identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$remember_me = filter_input(INPUT_POST, 'remember_me', FILTER_SANITIZE_NUMBER_INT);

// Function to get user role based on the identifier
function get_user_role($identifier)
{
    global $conn;

    // Query each role table separately
    $tables = [
        ['table' => 'acces.admins', 'role_id' => 1],
        ['table' => 'acces.moderators', 'role_id' => 3],
        ['table' => 'data.users', 'role_id' => 2]
    ];

    foreach ($tables as $entry) {
        $role_id = null;
        $stmt = $conn->prepare("SELECT role_id FROM {$entry['table']} WHERE name = ? OR email = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $stmt->bind_result($role_id);
        if ($stmt->fetch()) {
            $stmt->close();
            return $role_id;
        }
        $stmt->close();
    }
    return null; // Role not found
}

// Function to get user data from the relevant table
function get_user_data($identifier)
{
    global $conn;

    $tables = [
        ['table' => 'acces.admins'],
        ['table' => 'acces.moderators'],
        ['table' => 'data.users']
    ];

    foreach ($tables as $entry) {
        $stmt = $conn->prepare("SELECT id, password, name, role_id FROM {$entry['table']} WHERE name = ? OR email = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Check if the table has a 'blocked' column
            $stmt = $conn->prepare("SHOW COLUMNS FROM {$entry['table']} LIKE 'blocked'");
            $stmt->execute();
            $result = $stmt->get_result();
            $blocked_column_exists = $result->num_rows > 0;
            $stmt->close();

            if ($blocked_column_exists) {
                $stmt = $conn->prepare("SELECT blocked FROM {$entry['table']} WHERE id = ?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $blocked_status = $result->fetch_assoc();
                $stmt->close();
                $user['blocked'] = $blocked_status['blocked'];

                if ($user['blocked'] === 1) {
                    $stmt = $conn->prepare("SELECT reason FROM acces.blocked_overview WHERE user_id = ?");
                    $stmt->bind_param("i", $user['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $reason_for_block = $result->fetch_assoc();
                    $stmt->close();
                    $user['reason'] = $reason_for_block['reason'];
                }
            }
            return $user;
        }
    }

    // If no user is found in any table
    return null;
}

// Check user role and set session
function set_user_session($role_id, $user)
{
    $_SESSION['loggedin'] = true;
    $_SESSION['userid'] = $user['id'];
    $_SESSION['username'] = $user['name'];

    switch ($role_id) {
        case 1:
            $_SESSION['role'] = 'admin';
            break;
        case 3:
            $_SESSION['role'] = 'moderator';
            break;
        case 2:
            $_SESSION['role'] = 'user';
            break;
        default:
            $_SESSION['role'] = 'guestss';
    }
}

// Main logic
if ($identifier && $password) {
    $user = get_user_data($identifier);

    if ($user && $user['role_id'] !== null) {

        if ($user) {
            if (!empty($user['reason'])) {
                echo "<script>alert('Your account is blocked. Reason: {$user['reason']}');</script>";
                echo '<script>window.location.href = "/projekt/index.php";</script>';
                exit;
            }

            if (password_verify($password, $user['password'])) {
              
                set_user_session($user['role_id'], $user);

                // Update last login time
                $stmt = $conn->prepare("UPDATE data.users SET last_logg = NOW() WHERE id = ?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $stmt->close();
                $_SESSION['logged_in'] = true;

                $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate CSRF token
                // Remember login if requested
                if ($remember_me == 1) { // Ensure checkbox is checked
                    setcookie('remember_me', $user['id'], time() + 31536000, '/projekt/'); // 1 year
                    setcookie('identifier', $identifier, time() + 31536000, '/projekt/');
                } else {
                    setcookie('remember_me', '', time() - 3600, '/projekt/'); // Remove cookie
                    setcookie('identifier', '', time() - 3600, '/projekt/');
                }
                
                switch ($user['role_id']) {
                    case 1:
                        header('Location: ../cms/admin.php');
                        exit; 
                    case 2:
                        header('Location: ../cms/user_dashboard.php');
                        "<pre>
                        $user
                        </pre>";
                        exit;
                    case 3:
                        header('Location: ../cms/moderator_dashboard.php');
                        exit;
                    default:
                        header('Location: /projekt/index.php');
                        exit;
                }
                
            } else {
                echo '<script>alert("Invalid password!");</script>';
                echo '<script>setTimeout(() => window.location.href = "/projekt/Login_form.php", 2000);</script>';
            }
        } else {
            echo '<script>alert("User not found!");</script>';
            echo '<script>setTimeout(() => window.location.href = "/projekt/Login_form.php", 2000);</script>';
        }
    } else {
        echo '<p>No record found for this account, please register!</p>';
        echo '<p>You will be redirected to registration shortly.</p>';
        echo '<script>setTimeout(() => window.location.href = "/projekt/reg_form.php", 2000);</script>';
    }
} else {
    echo '<script>alert("Please fill in all required fields!");</script>';
    echo '<script>setTimeout(() => window.location.href = "/projekt/Login_form.php", 2000);</script>';
}
$conn->close();
exit;
