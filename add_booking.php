<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// // Database connection
// $servername = "localhost"; // Your database server
// $username = "root"; // Your database username
// $password = ""; // Your database password
// $dbname = "carentaldb"; // Your database name

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carId = $_POST['carid']; // Get the car ID from the form
    $username = $_POST['username']; // Get the username from the form
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pickupLocation = $_POST['pickup_location'];
    $dropoffLocation = $_POST['dropoff_location'];
    $bookingFrom = $_POST['booking_from'];
    $bookingTo = $_POST['booking_to'];
    $bookingTime = $_POST['booking_time'];
    $amount = $_POST['amount']; // Get amount
    $paymentMethod = $_POST['payment_method']; // Get payment method
    $status = 'pending'; // Set initial status to pending

    // Check Availability Algorithm
    include_once 'algorithms/booking_algorithm.php';
    
    if (!isCarAvailable($conn, $carId, $bookingFrom, $bookingTo)) {
        // If not available -> show "Not Available"
        echo "<script>alert('Not Available for the selected dates.'); window.history.back();</script>";
        exit();
    }

    // Insert booking into the database
    // Note: Column name is `total money` with a space
    $stmt = $conn->prepare("INSERT INTO bookings (name, carid, email, phone, pickup_location, dropoff_location, booking_from, booking_to, booking_time, status, `total money`, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssds", $username, $carId, $email, $phone, $pickupLocation, $dropoffLocation, $bookingFrom, $bookingTo, $bookingTime, $status, $amount, $paymentMethod);
    $stmt->execute();
    
    // Get the inserted booking ID
    $bookingId = $conn->insert_id;

    // Store booking details in session for payment page
    $_SESSION['pending_payment_booking_id'] = $bookingId;
    $_SESSION['pending_payment_amount'] = $amount;
    
    // Redirect based on payment method
    if ($paymentMethod === 'online') {
        // Redirect to payment page for online payment
        header("Location: booking_payment.php");
    } else {
        // Redirect to dashboard for cash on delivery
        $_SESSION['success_message'] = "Successfully booked the car. Payment will be collected on delivery.";
        header("Location: dashboard.php");
    }
    exit();
}

$conn->close();
?> 