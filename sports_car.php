<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Car - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #1a2a6c, #b21f1f, #fdbb2d); /* Gradient background */
            color: #fff; /* White text for better contrast */
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Optional: Add a semi-transparent black background for readability */
            padding: 30px; /* Padding inside the container */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Shadow effect */
        }
        form {
            display: flex;
            flex-direction: column; /* Stack form elements vertically */
            max-width: 400px; /* Set a maximum width for the form */
            margin: 20px auto; /* Center the form */
            padding: 20px; /* Padding inside the form */
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Shadow effect */
        }
        label {
            margin-bottom: 5px; /* Space below labels */
            font-weight: bold; /* Bold labels */
            color: #fff; /* White color for contrast */
        }
        textarea,
        input[type="text"] {
            padding: 10px; /* Padding inside input fields */
            margin-bottom: 15px; /* Space below input fields */
            border: 1px solid #ccc; /* Light border */
            border-radius: 5px; /* Rounded corners */
            font-size: 1em; /* Font size */
            background-color: #f9f9f9; /* Light background for inputs */
            color: #333; /* Dark text color */
        }
        .book-now {
            display: inline-block; /* Make the link a block element */
            padding: 10px 20px; /* Padding for the button */
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            text-decoration: none; /* Remove underline */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s; /* Smooth transition */
            text-align: center; /* Center the text */
        }
        .book-now:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <h1>Sports Car</h1>
    <form>
        <label for="carDetails">Details about the Sports Car:</label>
        <textarea id="carDetails" rows="4" readonly>Details about the Sports Car.</textarea>

        <label for="carPrice">Price:</label>
        <input type="text" id="carPrice" value="$800/day" readonly>

        <a href="book_car.php?car=Sports%20Car" class="book-now">Book Now</a>
    </form>
</body>
</html> 