<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // User is already logged in
    // Do nothing
}

$currentFile = basename($_SERVER['PHP_SELF']);

switch ($_SESSION['role']) {
    case 'admin':
        // full acces
        break;
    
    case 'moderator':
        if (!in_array($currentFile, ['index.php', 'moderator_dashboard.php'])) {
           echo 'You do not have permission to access this page.';
           header('HTTP/1.1 403 Forbidden');
            exit;
        }
        break;
    
    case 'user':
        if (!in_array($currentFile, ['index.php', 'user_dashboard.php'])) {
            echo 'You do not have permission to access this page.';

            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        break;
    case 'guest':
            if (!in_array($currentFile, ['index.php', 'cms/login.php', 'cms/register.php'])) {
                //echo 'You do not have permission to access this pagexd.';
                header('HTTP/1.1 403 Forbidden');
                exit;
            }
            break;
    default:
        // If the role is not recognized, deny access
        //echo 'You do not have permission to access this page.';
        header('HTTP/1.1 403 Forbidden');
       
        exit;
}
?>
