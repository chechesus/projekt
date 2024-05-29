<?php

// Function to establish a connection to the database
function connectToDatabase() {
    require_once 'config.php'; // Include the configuration file
    $conn = new mysqli($host, $username, $password, $dbname); // Create a new mysqli object
    if ($conn->connect_error) {
        throw new Exception("Connection failed: ". $conn->connect_error);
    }
    return $conn;
}

// Function to retrieve user input from a form
function getUserInput() {
    $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING); // Get the username from the form
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING); // Get the comment from the form
    return [$name, $comment];
}

// Function to get the user ID based on the provided username
function getUserID($conn, $name) {
    $stmt = $conn->prepare("SELECT ID FROM uzivatelia WHERE name =?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
    return $user_id;
}

// Function to insert a new comment into the database
function insertComment($conn, $user_id, $comment) {
    $sql = "INSERT into comments (user_id, comment) values(?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $user_id, $comment);
    $stmt->execute();
    if ($stmt->error) {
        throw new Exception("Error inserting or updating data: ". $stmt->error);
    }
    $stmt->close();
}

try {
    $conn = connectToDatabase(); // Connect to the database
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