<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['login']);
if (!$isLoggedIn) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['login']['full_name']; // Get the username
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Car Rental Service</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8; /* Grayish background for the page */
            display: flex;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
        }

        .sidebar {
            width: 250px; /* Width of the sidebar */
            background-color: #007bff; /* Solid blue background */
            color: white; /* White text color */
            padding: 20px; /* Padding inside the sidebar */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        .sidebar h2 {
            margin: 0 0 20px; /* Margin for the heading */
        }

        .sidebar ul {
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove padding */
        }

        .sidebar ul li {
            margin: 10px 0; /* Margin between items */
        }

        .sidebar ul li a {
            color: white; /* Link color */
            text-decoration: none; /* Remove underline */
            display: block; /* Make the link fill the list item */
            padding: 10px; /* Padding for the link */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }

        .sidebar ul li a:hover {
            background-color: #0056b3; /* Darker background on hover */
        }

        .main-content {
            flex: 1; /* Take the remaining space */
            padding: 20px; /* Padding for the main content */
            background-color: white; /* White background for main content */
            margin-left: 20px; /* Space between sidebar and main content */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        h1 {
            color: #333; /* Darker text color for headings */
            font-size: 2.5rem; /* Larger font size for the main heading */
            margin-bottom: 20px; /* Space below the heading */
            text-align: center; /* Center the heading */
        }

        .payment-form {
            max-width: 600px; /* Limit the width of the form */
            margin: 0 auto; /* Center the form */
            padding: 20px; /* Padding inside the form */
            border: 1px solid #007BFF; /* Border for the form */
            border-radius: 8px; /* Rounded corners */
            background-color: #f9f9f9; /* Light background for the form */
        }

        .payment-form label {
            display: block; /* Make labels block elements */
            margin-bottom: 5px; /* Space below labels */
        }

        .payment-form input {
            width: 100%; /* Full width for inputs */
            padding: 10px; /* Padding inside inputs */
            margin-bottom: 15px; /* Space below inputs */
            border: 1px solid #ccc; /* Border for inputs */
            border-radius: 4px; /* Rounded corners */
        }

        .payment-form button {
            background-color: #007BFF; /* Button color */
            color: white; /* Button text color */
            padding: 10px 15px; /* Padding for button */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            font-size: 16px; /* Font size for button */
        }

        .payment-form button:hover {
            background-color: #0056b3; /* Darker button color on hover */
        }

        .hidden {
            display: none; /* Hide elements with this class */
        }
    </style>
    <script>
        function showPaymentForm(method) {
            document.getElementById('payment-options').classList.add('hidden');
            if (method === 'cash') {
                document.getElementById('cash-form').classList.remove('hidden');
            } else if (method === 'online') {
                document.getElementById('online-form').classList.remove('hidden');
            }
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="dashboard.php">My Bookings</a></li>
                <li><a href="my_profile.php">My Profile</a></li>
                <li><a href="payments.php">Payments</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div id="payment-options">
                <h1>Choose Payment Method</h1>
                <div>
                    <label>
                        <input type="radio" name="payment_method" value="cash" onclick="showPaymentForm('cash')" required>
                        Cash on Delivery
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="payment_method" value="online" onclick="showPaymentForm('online')" required>
                        Online Payment
                    </label>
                </div>
            </div>

            <div id="cash-form" class="hidden payment-form">
                <h1>Cash on Delivery</h1>
                <p>Please have the exact amount ready for the delivery.</p>
                <button onclick="alert('Cash on Delivery selected!')">Confirm Cash on Delivery</button>
            </div>

            <div id="online-form" class="hidden payment-form">
                <h1>Online Payment</h1>
                
              <?php
                include 'payment.php';
                        ?>
            </div>
    
            <div>
      
    </div>
        </div>
        
    </div>
 
</body>
</html>  

