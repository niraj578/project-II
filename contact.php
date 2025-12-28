<?php
session_start(); // Start the session

// Check if the user is logged in (optional)
$isLoggedIn = isset($_SESSION['login']);

// Check for success status
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Car Rental Service</title>
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

        .success-message {
            background-color: #d4edda; /* Light green background */
            color: #155724; /* Dark green text */
            padding: 15px; /* Padding for the message */
            border: 1px solid #c3e6cb; /* Border for the message */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 20px; /* Space below the message */
            text-align: center; /* Center text */
        }

        form {
            display: flex;
            flex-direction: column; /* Stack form elements vertically */
        }

        input, textarea {
            margin-bottom: 15px; /* Space below inputs */
            padding: 10px; /* Padding for inputs */
            border: 1px solid #ccc; /* Border for inputs */
            border-radius: 5px; /* Rounded corners */
            font-size: 1em; /* Font size for inputs */
        }

        .submit-button {
            padding: 12px 20px; /* Padding for the button */
            background-color: #007BFF; /* Button color */
            color: white; /* Button text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Transition for hover effect */
        }

        .submit-button:hover {
            background-color: #0056b3; /* Darker button color on hover */
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        
        <?php if ($status === 'success'): ?>
            <div class="success-message">Message sent successfully!</div>
        <?php endif; ?>
        
        <p>If you have any questions or need assistance, feel free to reach out to us using the form below or contact us directly.</p>
        
        <form action="submit_contact.php" method="POST"> <!-- Action to handle form submission -->
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit" class="submit-button">Send Message</button>
        </form>

        <p>You can also reach us at:</p>
        <p>Email: support@carrentalservice.com</p>
        <p>Phone: +1 (234) 567-890</p>
        
        <a href="index.php" class="back-link">Back to Home Page</a> <!-- Link to go back to the home page -->
    </div>
</body>
</html> 