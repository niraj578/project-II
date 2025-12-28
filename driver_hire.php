<?php
session_start(); // Start the session

// Check if the user is logged in (optional)
$isLoggedIn = isset($_SESSION['login']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Hire - Car Rental Service</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Light background color */
        }

        .container {
            max-width: 800px; /* Max width for the content */
            margin: 20px auto; /* Center the container */
            padding: 20px; /* Padding inside the container */
            background-color: white; /* White background for content */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        h1 {
            color: #333; /* Darker text color for headings */
        }

        p {
            line-height: 1.6; /* Line height for better readability */
        }

        .back-link {
            display: inline-block; /* Make it a block element */
            margin-top: 20px; /* Space above the link */
            padding: 10px 15px; /* Padding for the link */
            background-color: #007BFF; /* Button color */
            color: white; /* Button text color */
            border-radius: 5px; /* Rounded corners */
            text-decoration: none; /* Remove underline */
        }

        .back-link:hover {
            background-color: #0056b3; /* Darker button color on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Driver Hire</h1>
        <p>Our Car Rental Service offers professional driver hire to enhance your travel experience. Whether you need a driver for a day or for a longer duration, we have qualified and experienced drivers ready to assist you.</p>
        
        <p>Our drivers are knowledgeable about the local area and can provide you with valuable insights during your journey. They are committed to ensuring your safety and comfort while you travel.</p>
        
        <p>Contact us for more details on our driver hire services and to book a driver for your next trip!</p>
        
        <a href="index.php" class="back-link">Back to Home</a> <!-- Link to go back to the home page -->
    </div>
</body>
</html> 