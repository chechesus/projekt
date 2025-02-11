<?php
header("Location: /auth_check.php");
require_once '../api/session.php';
function insertArticle($title, $content) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO articles (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    if ($stmt->execute()) {
        echo '
        <script>
            function alertinsert() {
                alert("Article inserted successfully.");
            }
            alertinsert();
        </script>';
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Function to update an existing article
function updateArticle($id, $title, $content) {
    global $conn;
    $stmt = $conn->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);
    if ($stmt->execute()) {
        echo '
        <script>
            function alertinsert() {
                alert("Article updated successfully.");
            }
            alertinsert();
        </script>';
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Function to delete an article
function deleteArticle($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo '
        <script>
            function alertinsert() {
                alert("Article deleted successfully.");
            }
            alertinsert();
        </script>';
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Example usage
// Insert a new article
if (isset($_POST['insert'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    insertArticle($title, $content);
}

// Update an article
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    updateArticle($id, $title, $content);
}

// Delete an article
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    deleteArticle($id);
}

$conn->close();
?>
