<?php
include 'connection.php';

header('Content-Type: application/json');

// Check for unread messages
$sql = "SELECT * FROM messages WHERE status = 'unread' ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['new_message' => true, 'data' => $row]);
} else {
    echo json_encode(['new_message' => false]);
}

$conn->close();
?>
