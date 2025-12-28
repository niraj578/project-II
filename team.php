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
    <title>Our Teams - Car Rental Service</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Light background color */
            color: #333; /* Default text color */
        }

        .container {
            max-width: 900px; /* Max width for the content */
            margin: 40px auto; /* Center the container */
            padding: 30px; /* Padding inside the container */
            background-color: #ffffff; /* White background for content */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
            transition: transform 0.3s; /* Transition for hover effect */
        }

        .container:hover {
            transform: translateY(-5px); /* Lift effect on hover */
        }

        h1 {
            font-size: 2.5em; /* Larger heading */
            margin-bottom: 20px; /* Space below heading */
            color: #007BFF; /* Primary color for headings */
            text-align: center; /* Center text */
        }

        h2 {
            font-size: 1.8em; /* Subheading size */
            margin-top: 30px; /* Space above subheading */
            color: #333; /* Darker color for subheadings */
        }

        p {
            line-height: 1.8; /* Line height for better readability */
            margin-bottom: 15px; /* Space below paragraphs */
            font-size: 1.1em; /* Slightly larger font size */
        }

        .team-member {
            display: flex; /* Flexbox for team member layout */
            align-items: center; /* Center items vertically */
            margin-bottom: 20px; /* Space below each team member */
            padding: 15px; /* Padding for each member */
            background-color: #f9f9f9; /* Light background for team member */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        .team-member img {
            border-radius: 50%; /* Circular image */
            width: 80px; /* Fixed width for images */
            height: 80px; /* Fixed height for images */
            margin-right: 15px; /* Space between image and text */
        }

        .back-link {
            display: inline-block; /* Make it a block element */
            margin-top: 30px; /* Space above the link */
            padding: 12px 20px; /* Padding for the link */
            background-color: #007BFF; /* Button color */
            color: white; /* Button text color */
            border-radius: 5px; /* Rounded corners */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Bold text */
            transition: background-color 0.3s; /* Transition for hover effect */
        }

        .back-link:hover {
            background-color: #0056b3; /* Darker button color on hover */
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px; /* Adjust padding for smaller screens */
            }

            h1 {
                font-size: 2em; /* Smaller heading on mobile */
            }

            h2 {
                font-size: 1.5em; /* Smaller subheading on mobile */
            }

            p {
                font-size: 1em; /* Smaller font size on mobile */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Meet Our Teams</h1>
        <h2>Management Team</h2>
        <div class="team-member">
            <img src="pictures/manager.jpg" alt="Manager">
            <div>
                <strong>John Doe</strong><br>
                CEO
            </div>
        </div>
        <h2>Support Team</h2>
        <div class="team-member">
            <img src="pictures/support.jpg" alt="Support">
            <div>
                <strong>Jane Smith</strong><br>
                Customer Support Manager
            </div>
        </div>
        <h2>Mechanics Team</h2>
        <div class="team-member">
            <img src="pictures/mechanic.jpg" alt="Mechanic">
            <div>
                <strong>Mike Johnson</strong><br>
                Head Mechanic
            </div>
        </div>
        <a href="index.php" class="back-link">Back to Home Page</a> <!-- Link to go back to the home page -->
    </div>
</body>
</html> 