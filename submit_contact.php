<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare the message to be saved
    $fullMessage = "Name: $name\nEmail: $email\nMessage: $message\n\n";

    // Save the message to a text file
    file_put_contents('messages.txt', $fullMessage, FILE_APPEND);

    // Redirect back to the contact page or a thank you page
    header('Location: contact.php?status=success');
    exit();
}
?> 