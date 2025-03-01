<?php
header("Content-Type: application/json");

// Skontrolujeme, či máme parameter 'id'
if (!isset($_GET['id'])) {
    error_log("Profile.php: ID používateľa nebolo zadané.");
    echo json_encode(["error" => "Neplatné ID používateľa."]);
    exit;
}
$userId = (int) $_GET['id'];

require_once 'api/session.php';
if ($conn->connect_error) {
    error_log("Profile.php: Pripojenie k databáze zlyhalo: " . $conn->connect_error);
    echo json_encode(["error" => "Pripojenie k databáze zlyhalo: " . $conn->connect_error]);
    exit;
}

// Načítanie údajov o používateľovi
$sqlUser = "SELECT * FROM users WHERE ID = ?";
$stmtUser = $conn->prepare($sqlUser);
if (!$stmtUser) {
    error_log("Profile.php: SQL prepare error pre používateľa: " . $conn->error);
    echo json_encode(["error" => "Chyba na serveri."]);
    exit;
}
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows === 0) {
    error_log("Profile.php: Používateľ s ID $userId neexistuje.");
    echo json_encode(["error" => "Používateľ s ID $userId neexistuje."]);
    exit;
}

$user = $resultUser->fetch_assoc();

// Fallback logika pre profilovú fotku
$profilePic = $user['profile_picture'];
if (empty($profilePic)) {
    $profilePic = 'C:\xampp\htdocs\projekt\images\user_ico.png';
}
$imageContent = @file_get_contents($profilePic);
if ($imageContent === false) {
    error_log("Profile.php: Nepodarilo sa načítať obrázok z cesty: " . $profilePic);
    $fallbackPic = 'C:\xampp\htdocs\projekt\images\user_ico.png';
    $imageContent = @file_get_contents($fallbackPic);
    if ($imageContent === false) {
        error_log("Profile.php: Nepodarilo sa načítať fallback obrázok z cesty: " . $fallbackPic);
        $imageContent = '';
    }
}
if (!empty($imageContent)) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($imageContent);
    $base64Image = base64_encode($imageContent);
    $dataUri = "data:" . $mimeType . ";base64," . $base64Image;
} else {
    $dataUri = '';
}
$user['profile_picture'] = $dataUri;

// Načítanie komentárov používateľa
$sqlComments = "
    SELECT c.comment_id,
           c.article_id,
           c.comment_text,
           c.created_at,
           c.fk_user_ID ,
           c.status,
           u.nick AS username,
           u.profile_picture
    FROM comments c
    JOIN users u ON c.fk_user_ID = u.ID
    WHERE c.fk_user_ID = ?
    ORDER BY c.created_at DESC
";
$stmtComments = $conn->prepare($sqlComments);
if (!$stmtComments) {
    error_log("Profile.php: SQL prepare error pre komentáre: " . $conn->error);
    echo json_encode(["error" => "Chyba na serveri."]);
    exit;
}
$stmtComments->bind_param("i", $userId);
$stmtComments->execute();
$resultComments = $stmtComments->get_result();

$comments = [];
while ($row = $resultComments->fetch_assoc()) {
    $pic = $row['profile_picture'];
    if (empty($pic)) {
        $pic = 'C:\xampp\htdocs\projekt\images\user_ico.png';
    }
    $imgContent = @file_get_contents($pic);
    if ($imgContent === false) {
        error_log("Profile.php: Nepodarilo sa načítať obrázok pre komentár ID " . $row['comment_id'] . " z cesty: " . $pic);
        $fallbackPic = 'C:\xampp\htdocs\projekt\images\user_ico.png';
        $imgContent = @file_get_contents($fallbackPic);
        if ($imgContent === false) {
            error_log("Profile.php: Nepodarilo sa načítať fallback obrázok pre komentár ID " . $row['comment_id']);
            $imgContent = '';
        }
    }
    if (!empty($imgContent)) {
        $mimeType = $finfo->buffer($imgContent);
        $base64   = base64_encode($imgContent);
        $row['profile_picture'] = "data:" . $mimeType . ";base64," . $base64;
    } else {
        $row['profile_picture'] = '';
    }
    $comments[] = $row;
}

error_log("Profile.php: Úspešne načítaný profil a " . count($comments) . " komentárov pre používateľa ID " . $userId);

echo json_encode([
    "user" => $user,
    "comments" => $comments
]);

$conn->close();
?>
