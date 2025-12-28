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
    <title>Tour Packages - Car Rental Service</title>
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
        <h1>Tour Packages</h1>
        <p>Explore our exciting tour packages designed to enhance your travel experience. Whether you're looking for a scenic drive through the mountains or a cultural tour of the city, we have something for everyone.</p>
        
        <p>Our tour packages include:</p>
        <ul>
            <li>City Sightseeing Tour: Experience the best attractions in the city.</li>
            <li>Mountain Adventure: Enjoy breathtaking views and outdoor activities.</li>
            <li>Cultural Heritage Tour: Discover the rich history and culture of the region.</li>
        </ul>
        
        <p>Contact us for more details on our tour packages and to customize your own adventure!</p>
        
        <a href="index.php" class="back-link">Back to Home</a> <!-- Link to go back to the home page -->
    </div>
</body>
</html> 