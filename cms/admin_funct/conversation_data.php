<?php
// Connect to the database
require_once '../api/session.php';

// Accept sender and receiver IDs from the request
$sender_id = isset($_GET['sender_id']) ? (int)$_GET['sender_id'] : 0;
$receiver_id = isset($_GET['receiver_id']) ? (int)$_GET['receiver_id'] : 0;

// Validate IDs
if ($sender_id > 0 && $receiver_id > 0) {
    $sql = "SELECT * FROM conversations WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Create an array to store the conversation data
    $conversation_data = array();
    while ($row = $result->fetch_assoc()) {
        $conversation_data[] = array(
            "id" => $row["id"],
            "sender_id" => $row["sender_id"],
            "receiver_id" => $row["receiver_id"],
            "message" => $row["message"],
            "timestamp" => $row["timestamp"]
        );
    }
    
    // Close the database connection
    $conn->close();
    
    // Encode and output the conversation data in JSON format
    echo json_encode($conversation_data, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Invalid sender or receiver ID"]);
}
?>
