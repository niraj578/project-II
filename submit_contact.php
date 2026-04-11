<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'connection.php'; // Include database connection

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Also save to text file as backup (optional, keeping original behavior)
        $fullMessage = "Name: $name\nEmail: $email\nMessage: $message\n\n";
        file_put_contents('messages.txt', $fullMessage, FILE_APPEND);

        header('Location: contact.php?status=success');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}

?> 