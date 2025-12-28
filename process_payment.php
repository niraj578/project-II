<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['login']);
if (!$isLoggedIn) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Process the payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Here you would typically process the payment with a payment gateway

    // For demonstration, just echo the details
    echo "Payment of $$amount has been processed successfully!";
    echo "<br>Card Number: $card_number";
    echo "<br>Expiry Date: $expiry_date";
    echo "<br>CVV: $cvv";
} else {
    echo "Invalid request.";
}
?> 