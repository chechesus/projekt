<?php
// Prevent this script from running more than once per request
if (defined('AUTH_CHECKED')) {
    return; // Exit if auth has already been checked
}

define('AUTH_CHECKED', true); // Mark auth as checked

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // User is already logged in
    // Do nothing
}

$currentFile = basename($_SERVER['PHP_SELF']);

switch ($_SESSION['role_id']) {
    case 1:
        // Full access
        return;
        break;
        
    case '3':
        if (!in_array($currentFile, ['index.php', 'moderator_dashboard.php', 'user_edit/blocking.php','blocking.php'])) {
            if ($currentFile !== 'moderator_dashboard.php') {
                header('Location: /projekt/moderator_dashboard.php');
                exit;
            }
        }
        break;
    
    case 'user':
        if (!in_array($currentFile, ['index.php', 'user_dashboard.php', 'show_articles.php', 'show_profile.php'])) {
            if ($currentFile !== 'user_dashboard.php') {
                header('Location: /projekt/user_dashboard.php');
                exit;
            }
        }
        break;
    
    case 'guest':
        if (!in_array($currentFile, ['index.php', 'cms/login.php', 'cms/register.php'])) {
            if ($currentFile !== 'cms/login.php' && $currentFile !== 'cms/register.php') {
                header('Location: /projekt/login_form.php');
                exit;
            }
        }
        break;
    
    default:
        header('HTTP/1.1 403 Forbidden');
        exit;
}
?>