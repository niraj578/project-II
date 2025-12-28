<?php
session_start(); // Start the session

// // Include database connection
// $servername = "localhost"; // Your database server
// $username = "root"; // Your database username
// $password = ""; // Your database password
// $dbname = "carrentaldb"; // Your database name

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include 'connection.php';
// Check if the user is logged in
$isLoggedIn = isset($_SESSION['login']);
if (!$isLoggedIn) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$email = $_SESSION['login']['email']; // Get the email from session

// Fetch booking details with car names based on email
$sql = "SELECT bookings.*, cars.name AS car_name 
        FROM bookings 
        JOIN cars ON bookings.carid = cars.carid 
        WHERE bookings.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email); // Bind the email parameter
$stmt->execute();
$bookingsResult = $stmt->get_result();
$bookings = $bookingsResult->fetch_all(MYSQLI_ASSOC); // Fetch all bookings for the user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Car Rental Service</title>
    <style>
        body {
            background: linear-gradient(to bottom, #e6f7ff, #ffffff); /* Change this line to your preferred option */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Light background color */
        }

        .dashboard-container {
            display: flex; /* Use flexbox for layout */
            height: 100vh; /* Full height */
        }

        .sidebar {
            width: 250px; /* Width of the sidebar */
            background-color: #007bff; /* Sidebar background color */
            color: white; /* Text color */
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
            overflow-y: auto; /* Allow scrolling if content overflows */
        }

        .content {
            margin-top: 20px; /* Margin for the content section */
        }

        .booking-details {
            display: none; /* Initially hidden */
            margin-top: 20px; /* Margin for booking details */
            padding: 15px;
            border: 1px solid #007bff; /* Border for booking details */
            border-radius: 5px; /* Rounded corners */
            background-color: #e9f5ff; /* Light blue background */
        }

        .bookings-container {
            margin: 20px auto;
            max-width: 1200px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bookings-table {
            width: 100%;
            border-collapse: collapse; /* Remove space between borders */
            margin-top: 20px;
        }

        .bookings-table th, .bookings-table td {
            padding: 12px; /* Padding for table cells */
            text-align: left; /* Align text to the left */
            border-bottom: 1px solid #ddd; /* Bottom border for rows */
        }

        .bookings-table th {
            background-color: #007bff; /* Header background color */
            color: white; /* Header text color */
            font-weight: bold; /* Bold text for headers */
        }

        .bookings-table tr:hover {
            background-color: #f1f1f1; /* Highlight row on hover */
        }

        .cancel-btn {
            background-color: #dc3545; /* Red background for cancel button */
            color: white; /* White text color */
            padding: 8px 12px; /* Padding for button */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            text-decoration: none; /* Remove underline */
            transition: background-color 0.3s; /* Smooth transition */
        }

        .cancel-btn:hover {
            background-color: #c82333; /* Darker red on hover */
        }
    </style>
    <script>
        function showBookings() {
            // Toggle the visibility of the booking details
            var bookingDetails = document.getElementById('booking-details');
            if (bookingDetails.style.display === 'none' || bookingDetails.style.display === '') {
                bookingDetails.style.display = 'block';
            } else {
                bookingDetails.style.display = 'none';
            }
        }
        function showSection(section) {
        if (section === 'user-profile') {
            window.location.href = 'my_profile.php'; // Redirect to my_profile.php
        } else {
            // Handle other sections if needed
        }
    }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            
            <h2>Dashboard</h2>
            <ul>
                <li><a href="my_profile.php">My Profile</a></li>
                <li><a href="index.php">Back to Home</a></li>
               
            </ul>
        </aside>
        <main class="main-content">
            <h1>Welcome to Your Dashboard!</h1>
            <p>Here you can manage your bookings and view your profile.</p>
            <div class="content">
                <div id="booking-details" class="booking-details">
                    <div class="bookings-container">
                        <h2>My Bookings</h2>
                        <table class="bookings-table">
                            <thead>
                                <tr>
                                    <th>Car Name</th>
                                    <th>Booking Date</th>
                                    <th>Pickup Location</th>
                                    <th>Drop-off Location</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['dropoff_location']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                                        <td>
                                            <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>" class="cancel-btn">Cancel</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?> 