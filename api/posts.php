<?php
require_once 'session.php';
// Function to retrieve user input from a form
function getUserInput() {
    $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING); // Get the username from the form
    $comment_text = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING); // Get the comment from the form
    return [$name, $comment_text];
}

// Function to get the user ID based on the provided username
function getUserID($conn, $name) {
    $stmt = $conn->prepare("SELECT ID FROM users WHERE name =?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $user_id = $stmt->get_result();
    $stmt->fetch();
    $stmt->close();
    return $user_id;
}

// Function to insert a new comment into the database
function insertComment($conn, $user_id, $comment_text) {
    $sql = "INSERT into comments (user_id, comment_text) values(?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $user_id, $comment_text);
    $stmt->execute();
    if ($stmt->error) {
        throw new Exception("Error inserting or updating data: ". $stmt->error);
    }
    $stmt->close();
}

try {
    [$name, $comment] = getUserInput(); // Get the user input
    $user_id = getUserID($conn, $name); // Get the user ID
    if ($user_id > 0) {
        insertComment($conn, $user_id, $comment); // Insert the comment
    } else {
        echo '<p>User does not exist</p>';
    }
} catch (Exception $e) {
    echo '<p>Error: '. $e->getMessage(). '</p>';
} finally {
    $conn->close(); // Close the database connection
}
?>