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
    <title>Safe & Secure - Car Rental Service</title>
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
        <h1>Safe & Secure</h1>
        <p>At our Car Rental Service, your safety and security are our top priorities. We ensure that all our vehicles are regularly maintained and thoroughly inspected before each rental.</p>
        
        <p>All our vehicles are fully insured, providing you with peace of mind during your rental experience. We also implement strict safety protocols to ensure that you and your passengers are safe on the road.</p>
        
        <p>Our customer support team is available 24/7 to assist you with any concerns or emergencies that may arise during your rental period.</p>
        
        <a href="index.php" class="back-link">Back to Home</a> <!-- Link to go back to the home page -->
    </div>
</body>
</html> 