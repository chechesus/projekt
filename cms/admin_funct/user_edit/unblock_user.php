<?php
require "../../../api/session.php";

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(["status" => "error", "message" => "Neplatný CSRF token"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $role_id = $_POST['role_id'];

    if (!$user_id || !$role_id) {
        echo json_encode(["status" => "error", "message" => "Chýbajúce údaje"]);
        exit;
    }

    // Determine the table based on the role_id
    $table = '';
    switch ($role_id) {
        case 1: // Admin
            $table = 'acces.admins';
            break;
        case 2: // User
            $table = 'data.users';
            break;
        case 3: // Moderator
            $table = 'acces.moderators';
            break;
        default:
            echo json_encode(["status" => "error", "message" => "Neznáma rola"]);
            exit;
    }

    // Prepare the SQL statement based on the determined table
    $stmt = $conn->prepare("UPDATE $table SET blocked = False WHERE ID = ? AND role_id = ?");
    $stmt->bind_param("ii", $user_id, $role_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Chyba pri vkladaní do databázy"]);
    }

    $stmt->close();
}
?>