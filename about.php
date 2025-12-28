<?php
session_start(); // Start the session

// Include database connection if needed
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "car_rental_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in (optional)
$isLoggedIn = isset($_SESSION['login']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Car Rental Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Light background color */
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007bff; /* Primary color */
        }

        p {
            line-height: 1.6;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff; /* Primary color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>About Us</h1>
        <p>Welcome to our Car Rental Service! We are dedicated to providing you with the best car rental experience possible. Our fleet includes a wide range of vehicles to suit your needs, whether you're looking for a compact car for city driving or a spacious SUV for family trips.</p>
        <p>Our mission is to offer reliable, affordable, and convenient car rental services to our customers. We pride ourselves on our excellent customer service and strive to make your rental experience as smooth as possible.</p>
        <p>If you have any questions or need assistance, feel free to contact our customer support team. Thank you for choosing us for your car rental needs!</p>
        <a href="index.php" class="cta-button">Back to Home</a>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?> 