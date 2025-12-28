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
// $dbname = "carrentaldb"; // Your database name

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include 'connection.php';


// Check if the car ID is set
if (isset($_GET['id'])) {
    $carid = $_GET['id'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM cars WHERE carid = ?");
    $stmt->bind_param("s", $carid); // "s" means string

    // Execute the statement
    if ($stmt->execute()) {
        echo "Car deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to available cars page
    header('Location: available_cars.php');
    exit();
} else {
    echo "No car ID specified.";
}
?> 