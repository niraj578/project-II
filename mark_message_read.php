<?php
include 'connection.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "UPDATE messages SET status = 'read' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
