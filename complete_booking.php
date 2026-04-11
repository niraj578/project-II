<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Check if there's a pending payment booking
if (!isset($_SESSION['pending_payment_booking_id'])) {
    header("Location: dashboard.php");
    exit();
}

$bookingId = $_SESSION['pending_payment_booking_id'];
$paymentSuccess = isset($_GET['payment_success']) && $_GET['payment_success'] == '1';
$transactionId = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;

// Update booking status if payment was successful
if ($paymentSuccess && $transactionId) {
    // Update booking with payment information
    $sql = "UPDATE bookings SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    
    $_SESSION['success_message'] = "Booking confirmed! Payment successful. Transaction ID: " . htmlspecialchars($transactionId);
} else {
    // Booking created but payment skipped or pending
    $_SESSION['success_message'] = "Booking created successfully. You can complete payment later.";
}

// Clear pending payment session variables
unset($_SESSION['pending_payment_booking_id']);
unset($_SESSION['pending_payment_amount']);

$conn->close();

// Redirect to dashboard
header("Location: dashboard.php");
exit();
?>
