<?php
session_start(); // Start the session

// Check if the user is logged in as admin


// // Database connection
// $servername = "localhost"; // Your database server
// $username = "root"; // Your database username
// $password = ""; // Your database password
// $dbname = "carrentaldb"; // Your database name

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['booking_id']) && isset($_POST['status'])) {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];
        
        // Validate status
        $valid_statuses = array('approved', 'declined', 'cancelled', 'pending');
        if (in_array($status, $valid_statuses)) {
            // Apply FCFS Algorithm before approving (REMOVED)
            if ($status == 'approved') {
                // FCFS check removed as per requirement.
                // Just proceed to update booking status.
            }

            // Update booking status
            $sql = "UPDATE bookings SET status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $booking_id);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "Booking status updated successfully.";
            } else {
                $_SESSION['message'] = "Error updating booking status.";
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Invalid status provided.";
        }
    } else {
        $_SESSION['message'] = "Missing required parameters.";
    }
}

$conn->close();

// Redirect back to manage bookings page
header("Location: managebooking.php");
exit();
?>
