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
    $status = 'pending'; // Set initial status to pending

    // Insert booking into the database
    $stmt = $conn->prepare("INSERT INTO bookings (name, carid, email, phone, pickup_location, dropoff_location, booking_from, booking_to, booking_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $username, $carId, $email, $phone, $pickupLocation, $dropoffLocation, $bookingFrom, $bookingTo, $bookingTime, $status);
    $stmt->execute();

    // Redirect with success message
    $_SESSION['success_message'] = "Successfully booked the car."; // Set success message in session
    header("Location: dashboard.php"); // Redirect to dashboard
    exit();
}

$conn->close();
?> 