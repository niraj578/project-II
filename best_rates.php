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
    <title>Best Rates - Car Rental Service</title>
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
        <h1>Best Rates</h1>
        <p>At our Car Rental Service, we offer the best rates in the market, ensuring you get the most value for your money. Our pricing is transparent, with no hidden charges.</p>
        
        <p>Here are some examples of our competitive rates:</p>
        <ul>
            <li>Compact Car: NRS 2,500 per day</li>
            <li>SUV: NRS 4,000 per day</li>
            <li>Luxury Car: NRS 6,500 per day</li>
        </ul>
        
        <p>We strive to provide affordable options for all our customers, whether you're renting for a day or a month. Contact us for special long-term rental rates!</p>
        
        <a href="index.php" class="back-link">Back to Home</a> <!-- Link to go back to the home page -->
    </div>
</body>
</html> 