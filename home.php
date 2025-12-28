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
    <title>Home - Car Rental Service</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('pictures/—Pngtree—vector car collection_16114096.png'); /* Set the background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            color: #333; /* Default text color */
        }

        .container {
            max-width: 900px; /* Max width for the content */
            margin: 40px auto; /* Center the container */
            padding: 30px; /* Padding inside the container */
            background-color: rgba(255, 255, 255, 0.8); /* Optional: Add a semi-transparent background for readability */
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

        p {
            line-height: 1.8; /* Line height for better readability */
            margin-bottom: 15px; /* Space below paragraphs */
            font-size: 1.1em; /* Slightly larger font size */
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

            p {
                font-size: 1em; /* Smaller font size on mobile */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Our Car Rental Service</h1>
        <p>At our Car Rental Service, we provide a wide range of vehicles to meet your needs. Whether you're looking for a compact car for city driving or a spacious SUV for family trips, we have the perfect vehicle for you.</p>
        
        <p>Our mission is to offer reliable, affordable, and convenient car rental services. We pride ourselves on our customer service and strive to make your rental experience as smooth as possible.</p>
        
        <p>All our vehicles are fully insured and regularly maintained to ensure your safety and comfort on the road. Our dedicated support team is available 24/7 to assist you with any inquiries or concerns.</p>
        
        <a href="index.php" class="back-link">Back to Home Page</a> <!-- Link to go back to the home page -->
       
    </div>
    
</body>
</html> 