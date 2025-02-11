<?php
require_once '../api/session.php';

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

// Extract article data
$title = $data['title'];
$elements = $data['elements'];
$scheduleDateTime = $data['scheduleDateTime'];

// Insert article into `articles` table
$stmt = $conn->prepare("INSERT INTO articles (title, scheduled_date_time) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $scheduleDateTime);
$stmt->execute();
$articleId = $stmt->insert_id;
$stmt->close();

// Loop through elements and save them in their respective tables
$order = 1;
foreach ($elements as $element) {
    switch ($element['type']) {
        case 'paragraph':
            $stmt = $conn->prepare("INSERT INTO articles.article_paragraphs (article_id, content, position) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $articleId, $element['content'], $order);
            break;
        case 'poll':
            $stmt = $conn->prepare("INSERT INTO articles.article_polls (article_id, question, position) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $articleId, $element['question'], $order);
            $stmt->execute();
            $pollId = $stmt->insert_id;
            foreach ($element['options'] as $option) {
                $stmt = $conn->prepare("INSERT INTO articles.poll_options (poll_id, option_text) VALUES (?, ?)");
                $stmt->bind_param("is", $pollId, $option);
                $stmt->execute();
            }
            break;
        case 'image':
            $stmt = $conn->prepare("INSERT INTO articles.article_images (article_id, url, title, position) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $articleId, $element['url'], $element['title'], $order);
            break;
    }
    $stmt->execute();
    $stmt->close();
    $order++;
}

echo json_encode(['success' => true, 'article_id' => $articleId]);
$conn->close();
?>
