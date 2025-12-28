<?php
session_start();

// // Database connection
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "carrentaldb";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include 'connection.php';

// Check if booking ID is provided
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    
    // Update booking status to cancelled
    $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Booking has been cancelled successfully.";
    } else {
        $_SESSION['message'] = "Error cancelling booking.";
    }
    
    $stmt->close();
} else {
    $_SESSION['message'] = "No booking ID provided.";
}

$conn->close();

// Redirect back to my_bookings.php
header("Location: my_bookings.php");
exit();
?> 