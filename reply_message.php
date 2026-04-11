<?php
session_start();
if (!isset($_SESSION['login'])) {
    exit('Unauthorized');
}
include 'connection.php';

if (isset($_POST['id']) && isset($_POST['reply'])) {
    $id = $_POST['id'];
    $reply = $_POST['reply'];
    
    $stmt = $conn->prepare("UPDATE messages SET admin_reply = ?, status = 'read' WHERE id = ?");
    $stmt->bind_param("si", $reply, $id);
    
    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>
