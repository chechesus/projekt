<?php
require_once 'api/session.php';

// Log začiatku požiadavky
error_log("comments.php accessed with method: " . $_SERVER['REQUEST_METHOD']);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Filtrovanie podľa article_id (ak je zadané)
        $articleId = isset($_GET['article_id']) ? (int)$_GET['article_id'] : null;
        error_log("GET request received. Article ID filter: " . ($articleId ? $articleId : "none"));

        if ($articleId) {
            $sql = "
                SELECT c.comment_id,
                    c.article_id,
                    c.comment_text,
                    c.created_at,
                    c.fk_user_ID,
                    c.status,
                    c.parent_comment_id,
                    u.nick AS user_nick,
                    u.profile_picture AS user_picture
                FROM comments c
                JOIN users u ON c.fk_user_ID = u.ID
                WHERE c.article_id = ?
                AND c.parent_comment_id IS NULL
                ORDER BY c.created_at DESC
            ";;
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("SQL prepare error: " . $conn->error);
                echo json_encode(["error" => "Chyba na serveri."]);
                exit;
            }
            $stmt->bind_param("i", $articleId);
            if (!$stmt->execute()) {
                error_log("SQL execute error: " . $stmt->error);
                echo json_encode(["error" => "Chyba pri načítaní komentárov."]);
                exit;
            }
            $result = $stmt->get_result();
        } else {
            $sql = "
                    SELECT c.comment_id,
                        c.article_id,
                        c.comment_text,
                        c.created_at,
                        c.fk_user_ID,
                        c.status,
                        c.parent_comment_id,
                        u.nick AS user_nick,
                        u.profile_picture AS user_picture
                    FROM comments c
                    JOIN users u ON c.fk_user_ID = u.ID
                    WHERE c.article_id = ?
                    AND c.parent_comment_id IS NULL
                    ORDER BY c.created_at DESC
                ";

            $result = $conn->query($sql);
            if (!$result) {
                error_log("SQL query error: " . $conn->error);
                echo json_encode(["error" => "Chyba pri načítaní komentárov."]);
                exit;
            }
        }

        $comments = [];
        if ($result) {
            $comments = $result->fetch_all(MYSQLI_ASSOC);
            error_log("Počet načítaných komentárov: " . count($comments));
        } else {
            error_log("Žiadne komentáre boli nájdené alebo chyba pri načítaní výsledkov.");
        }

        // Pre každý komentár načítame profilovú fotku a skonvertujeme ju na Base64 (dataURI)
        foreach ($comments as &$comment) {
            $profilePic = !empty($comment['user_picture']) ? $comment['user_picture'] : 'C:\xampp\htdocs\projekt\images\user_ico.png';
            $imageContent = @file_get_contents($profilePic);
            if ($imageContent === false) {
                error_log("Nepodarilo sa načítať obrázok z cesty: " . $profilePic);
                $fallbackPic = 'C:\xampp\htdocs\projekt\images\user_ico.png';
                $imageContent = @file_get_contents($fallbackPic);
                if ($imageContent === false) {
                    error_log("Nepodarilo sa načítať fallback obrázok z cesty: " . $fallbackPic);
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
            $comment['user_picture'] = $dataUri;
        }
        unset($comment);

        echo json_encode($comments);
        break;

    case 'POST':
        error_log("POST request received for adding a comment.");
        $articleId        = isset($_POST['article_id'])     ? (int)$_POST['article_id'] : null;
        $fk_userId        = isset($_POST['fk_user_ID'])       ? (int)$_POST['fk_user_ID'] : null;
        $commentText      = isset($_POST['comment_text'])     ? trim($_POST['comment_text']) : '';
        $status           = isset($_POST['status'])           ? $_POST['status'] : 'pending';
        $parent_comment_id = isset($_POST['parent_comment_id']) ? (int)$_POST['parent_comment_id'] : null;

        if (!$articleId || !$fk_userId || empty($commentText)) {
            error_log("Invalid input: articleId=$articleId, fk_userId=$fk_userId, commentText=" . substr($commentText, 0, 50));
            echo json_encode(["error" => "Chýbajúce alebo neplatné údaje."]);
            exit;
        }

        $sql = "
                INSERT INTO comments (article_id, comment_text, created_at, fk_user_ID, status, parent_comment_id)
                VALUES (?, ?, NOW(), ?, ?, ?)
            ";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("SQL prepare error (POST): " . $conn->error);
            echo json_encode(["error" => "Chyba na serveri."]);
            exit;
        }
        // Používame 'isisi' kde posledný parameter môže byť aj null
        $stmt->bind_param("isisi", $articleId, $commentText, $fk_userId, $status, $parent_comment_id);
        if ($stmt->execute()) {
            error_log("Nový komentár pridaný úspešne. Comment ID: " . $stmt->insert_id);
            echo json_encode(["success" => true]);
        } else {
            error_log("Chyba pri vkladaní komentára: " . $stmt->error);
            echo json_encode(["error" => $stmt->error]);
        }
        $stmt->close();
        break;


    default:
        error_log("Nepodporovaná HTTP metóda: " . $method);
        echo json_encode(["error" => "Nepodporovaná HTTP metóda"]);
        break;
}

$conn->close();
