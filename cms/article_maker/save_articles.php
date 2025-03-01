<?php
// save_articles.php
header('Content-Type: application/json');
require_once '../../api/session.php';
require_once 'parsedown/Parsedown.php';

// Pomocná funkcia na bezpečné ukončenie so JSON odpoveďou
function jsonErrorExit($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// 1) Získanie údajov z formulára
$title         = trim($_POST['title'] ?? '');
$schedule_date = $_POST['schedule_date'] ?? null;
$schedule_time = $_POST['schedule_time'] ?? null;
$composer_json = $_POST['composer_json'] ?? '';
$content       = $_POST['content'] ?? '';
$category      = trim($_POST['category'] ?? '');
$excerpt       = trim($_POST['excerpt'] ?? '');

// Thumbnail: buď ako URL, alebo ako nahraný súbor
$thumbnailLink = trim($_POST['thumbnail_link'] ?? '');
$thumbnailFile = $_FILES['thumbnail_file'] ?? null;

// Základná validácia
if (!$title) {
    jsonErrorExit('Chýba názov článku.');
}
if (empty($composer_json) && empty($content)) {
    jsonErrorExit('Článok je prázdny.');
}

$userId = $_SESSION['userid'] ?? -1;  // upravte podľa reálnej logiky
$role_id = $_SESSION['role_id'] ?? -1;  // upravte podľa reálnej logiky

// 2) Spracovanie thumbnailu (ak prišiel ako súbor)
if ($thumbnailFile && $thumbnailFile['error'] === UPLOAD_ERR_OK) {
    // Môžeš validovať typ súboru, veľkosť atď.
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($thumbnailFile['type'], $allowedTypes)) {
        jsonErrorExit('Neplatný formát obrázka (thumbnail).');
    }

    // Vytvor dočasný názov
    $extension = pathinfo($thumbnailFile['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid('thumbnail_', true) . '.' . $extension;

    // Ulož súbor do dočasného adresára (napr. /uploads)
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $targetFilePath = $uploadDir . $newFileName;

    if (!move_uploaded_file($thumbnailFile['tmp_name'], $targetFilePath)) {
        jsonErrorExit('Chyba pri nahrávaní thumbnail súboru.');
    }

    // Tu voláš Python skript, podobne ako pri profilePic
    // Napr.:
    $pythonExecutable = "C:/Users/janko/AppData/Local/Programs/Python/Python313/python.exe"; 
    $pythonScriptPath = "C:/xampp/htdocs/projekt/cms/user_funct/drive_upload.py";

    if (!file_exists($pythonScriptPath)) {
        error_log("ERROR: Python script not found: " . $pythonScriptPath);
        jsonErrorExit('Python script not found.');
    }

    // Skontroluj, či sa súbor skutočne nahrali
    if (!file_exists($targetFilePath)) {
        error_log("ERROR: Target file not found: " . $targetFilePath);
        jsonErrorExit('Target file not found.');
    }

    // Vytvor príkaz
    $command = $pythonExecutable . " " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($targetFilePath);

    $descriptorspec = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ];
    $process = proc_open($command, $descriptorspec, $pipes);

    if (!is_resource($process)) {
        error_log("ERROR: Could not start Python process.");
        jsonErrorExit('Could not start Python process.');
    }

    fclose($pipes[0]); // nebudeme posielať nič na stdin
    $output = stream_get_contents($pipes[1]);
    $errorOutput = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $return_value = proc_close($process);

    // Výstup z Pythonu je link na Drive
    $driveUrl = trim($output);
    if (!$driveUrl) {
        error_log("ERROR: driveUrl is empty. Python output: " . $output . " Error output: " . $errorOutput);
        jsonErrorExit('Chyba pri nahrávaní obrázka na Google Drive (thumbnail).');
    }
    // Nastavíme $thumbnailLink na URL z Drive
    $thumbnailLink = $driveUrl;
}

// 3) Najprv vytvoríme prázdny článok v DB (aby sme získali articleId)
$stmtA = $conn->prepare("
    INSERT INTO articles.articles (
        title,
        created_at,
        updated_at,
        scheduled_date,
        scheduled_time,
        user_id,
        role_id
    )
    VALUES (?, NOW(), NOW(), ?, ?, ?, ?)
");
if (!$stmtA) {
    jsonErrorExit("Chyba pri príprave dotazu: " . $conn->error);
}
$stmtA->bind_param("sssii", $title, $schedule_date, $schedule_time, $userId, $role_id);
if (!$stmtA->execute()) {
    jsonErrorExit("Chyba pri ukladaní článku: " . $conn->error);
}
$articleId = $stmtA->insert_id;
$stmtA->close();

// 4) Spracujeme composer data alebo single content
$composerData = [];
if (empty($composer_json)) {
    // Ak composer_json nie je poslaný, využijeme obsah z textarea ako jediný odsek
    $composerData = [
        [
            'type' => 'paragraph',
            'payload' => ['content' => $content],
            'order' => 1
        ]
    ];
} else {
    $composerData = json_decode($composer_json, true);
    if (!is_array($composerData)) {
        jsonErrorExit('Neplatné dáta kompozície (composer_json).');
    }
}

// Inicializácia Parsedown (na konverziu Markdown do HTML)
$parsedown = new Parsedown();

// Budeme kumulovať HTML aj kompletný Markdown
$finalHtml = '';
$fullMarkdown = '';

// 5) Pre každý element z composerData vytvoríme príslušné záznamy
foreach ($composerData as $element) {
    $type      = $element['type']   ?? '';
    $payload   = $element['payload']?? [];
    $order_id  = (int)($element['order'] ?? 0);

    switch ($type) {
        case 'paragraph':
            $mdContent   = $payload['content'] ?? '';
            $htmlContent = $parsedown->text($mdContent);

            // Kumulujeme do finálneho HTML a Markdown
            $finalHtml   .= $htmlContent . "\n";
            $fullMarkdown .= $mdContent . "\n";

            // Uložíme do `paragraphs` (ak to používaš)
            $stmtP = $conn->prepare("
                INSERT INTO articles.paragraphs (article_id, content, html_content, order_id)
                VALUES (?, ?, ?, ?)
            ");
            if (!$stmtP) {
                jsonErrorExit("Chyba: " . $conn->error);
            }
            $stmtP->bind_param("issi", $articleId, $mdContent, $htmlContent, $order_id);
            if (!$stmtP->execute()) {
                jsonErrorExit("Chyba pri ukladaní odseku: " . $conn->error);
            }
            $paragraphId = $stmtP->insert_id;
            $stmtP->close();

            // Uložíme aj do composer
            $stmtC = $conn->prepare("
                INSERT INTO articles.composer (article_id, element_type, element_id, order_id)
                VALUES (?, 'paragraph', ?, ?)
            ");
            $stmtC->bind_param("iii", $articleId, $paragraphId, $order_id);
            $stmtC->execute();
            $stmtC->close();
            break;

        case 'poll':
            // ... (podobne ako doteraz)
            // Môžeš pridať do finalHtml napr. "Anketa: Otázka" atď.
            break;

        case 'image_box':
            // ... (podobne ako doteraz)
            // Kľudne kumuluj do finalHtml <img src="..." alt="...">, ak chceš
            break;

        default:
            // Neznámy typ elementu, ignorujeme
            break;
    }
}

// 6) Update článku o zvyšné polia (thumbnail, excerpt, category, content, html_content)
$stmtU = $conn->prepare("
    UPDATE articles.articles
    SET
      thumbnail = ?,
      excerpt = ?,
      category = ?,
      content = ?,
      html_content = ?
    WHERE id = ?
");
if (!$stmtU) {
    jsonErrorExit("Chyba pri príprave UPDATE dotazu: " . $conn->error);
}

$stmtU->bind_param("sssssi", $thumbnailLink, $excerpt, $category, $fullMarkdown, $finalHtml, $articleId);
if (!$stmtU->execute()) {
    jsonErrorExit("Chyba pri aktualizácii článku: " . $conn->error);
}
$stmtU->close();

// 7) Odošleme JSON odpoveď
echo json_encode([
    'success' => true,
    'message' => 'Článok bol úspešne uložený.',
    'article_id' => $articleId
]);
?>
