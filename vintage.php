<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Car - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ff7e5f, #feb47b); /* Gradient background */
            color: #333; /* Default text color */
        }
        h1 {
            font-size: 2.5em; /* Larger font size for the heading */
            text-align: center; /* Center the heading */
            margin-bottom: 20px; /* Space below the heading */
            color: #fff; /* White color for contrast */
        }
        p {
            font-size: 1.2em; /* Slightly larger font size for paragraphs */
            line-height: 1.6; /* Increased line height for readability */
            margin: 10px 0; /* Space above and below paragraphs */
            text-align: center; /* Center the paragraphs */
            color: #fff; /* White color for contrast */
        }
        form {
            display: flex;
            flex-direction: column; /* Stack form elements vertically */
            max-width: 400px; /* Set a maximum width for the form */
            margin: 20px auto; /* Center the form */
            padding: 20px; /* Padding inside the form */
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Shadow effect */
        }
        label {
            margin-bottom: 5px; /* Space below labels */
            font-weight: bold; /* Bold labels */
            color: #333; /* Dark text color */
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
    <h1>Vintage Car</h1>
    <form>
        <label for="carDetails">Details about the Vintage Car:</label>
        <textarea id="carDetails" rows="4" readonly>Details about the Vintage Car.</textarea>

        <label for="carPrice">Price:</label>
        <input type="text" id="carPrice" value="5000NRS/day" readonly>

        <a href="book_car.php?car=Vintage" class="book-now">Book Now</a>
    </form>
</body>
</html> 