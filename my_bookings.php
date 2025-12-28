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

// Debug email value
echo "<!-- Debug: User email = " . htmlspecialchars($email) . " -->";

// Add this after session_start()
if (isset($_SESSION['message'])) {
    echo '<div class="alert" style="background-color: #4CAF50; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">' . 
         htmlspecialchars($_SESSION['message']) . 
         '</div>';
    unset($_SESSION['message']);
}

// Fetch booking details with car names based on email
$sql = "SELECT b.*, c.name as car_name, c.model as car_model, c.carid, 
        b.booking_from, b.booking_to, c.price as car_price
        FROM bookings b 
        LEFT JOIN cars c ON b.carid = c.carid 
        WHERE b.email = ?";

// Debug the SQL query
echo "<!-- Debug: SQL Query = " . htmlspecialchars($sql) . " -->";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$bookingsResult = $stmt->get_result();
$bookings = $bookingsResult->fetch_all(MYSQLI_ASSOC);

// Debug the results
echo "<!-- Debug: Number of bookings found = " . count($bookings) . " -->";
if (!empty($bookings)) {
    echo "<!-- Debug: First booking data = " . print_r($bookings[0], true) . " -->";
}

// Add debugging to check if we're getting data
if (empty($bookings)) {
    echo '<div style="color: red; padding: 10px;">No bookings found for email: ' . htmlspecialchars($email) . '</div>';
} else {
    echo '<div style="display:none;">Found ' . count($bookings) . ' bookings</div>';
}

// Show notification for approved bookings
foreach ($bookings as $booking) {
    if ($booking['status'] === 'approved'): ?>
        <div class="notification" id="notification">
            <p>Note: Before delivering, you will receive a call, so stay tuned!</p>
        </div>
        <script>
            // Hide the notification after 5 seconds
            setTimeout(function() {
                document.getElementById('notification').style.display = 'none';
            }, 5000); // 5000 milliseconds = 5 seconds
        </script>
    <?php break; endif;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Car Rental Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Light background color */
        }

        .dashboard-container {
            display: flex; /* Use flexbox for layout */
            height: 100vh; /* Full height of the viewport */
        }

        .sidebar {
            width: 250px; /* Width of the sidebar */
            background-color: #007bff; /* Sidebar background color */
            color: white; /* Text color */
            padding: 20px; /* Padding inside the sidebar */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Shadow effect */
            display: flex;
            flex-direction: column; /* Stack items vertically */
            justify-content: flex-start; /* Align items to the top */
        }

        .sidebar h2 {
            margin: 0 0 20px; /* Margin for the heading */
        }

        .sidebar ul {
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove padding */
            margin: 0; /* Remove margin */
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

        .bookings-container {
            margin: 20px auto;
            max-width: 1200px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1; /* Take the remaining space */
            overflow-y: auto; /* Allow scrolling if content overflows */
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

        /* Date column styling */
        .bookings-table td:nth-child(2),
        .bookings-table td:nth-child(3) {
            min-width: 100px;
            white-space: nowrap;
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

        .notification {
            background-color: #ffcc00; /* Yellow background */
            color: #333; /* Dark text color */
            padding: 15px; /* Padding around the text */
            text-align: center; /* Center the text */
            border-radius: 5px; /* Rounded corners */
            margin: 20px 0; /* Space above and below */
            font-weight: bold; /* Bold text */
        }

        /* Add status badge styles */
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .status-approved {
            background-color: #28a745;
            color: white;
        }
        .status-declined {
            background-color: #dc3545;
            color: white;
        }
        .status-pending {
            background-color: #ffc107;
            color: black;
        }
        .status-cancelled {
            background-color: #6c757d;
            color: white;
        }

        /* Total amount styling */
        .total-amount {
            font-weight: bold;
            color: #28a745;
            font-size: 1.1em;
        }

        /* Add a subtle background to highlight the total amount */
        td.total-amount {
            background-color: #f8fff9;
        }

        .booking-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #28a745;
            color: white;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .success-message {
            max-width: 800px;
            margin: 0 auto;
        }

        .success-message p {
            margin: 5px 0;
            font-size: 1.1em;
        }

        .success-message p:first-child {
            font-weight: bold;
            font-size: 1.2em;
        }

        /* Add padding to prevent content from being hidden behind fixed footer */
        .bookings-container {
            padding-bottom: 100px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="my_bookings.php">My Bookings</a></li>
                <li><a href="my_profile.php">My Profile</a></li>
                <li><a href="index.php">Back to Home</a></li>
            </ul>
        </aside>
        <main class="bookings-container">
            <h2>My Bookings</h2>
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Car Details</th>
                        <th>Booking From</th>
                        <th>Booking To</th>
                        <th>Pickup Location</th>
                        <th>Drop-off Location</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $carName = htmlspecialchars($booking['car_name'] ?? '');
                                        $carModel = htmlspecialchars($booking['car_model'] ?? '');
                                        if (!empty($carName)) {
                                            echo $carName;
                                            if (!empty($carModel)) {
                                                echo ' - ' . $carModel;
                                            }
                                        } else {
                                            echo 'N/A';
                                        }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($booking['booking_from'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_to'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['pickup_location'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['dropoff_location'] ?? 'N/A'); ?></td>
                                <td class="total-amount"><?php 
                                    if (!empty($booking['booking_from']) && !empty($booking['booking_to']) && !empty($booking['car_price'])) {
                                        $date1 = new DateTime($booking['booking_from']);
                                        $date2 = new DateTime($booking['booking_to']);
                                        $interval = $date1->diff($date2);
                                        $days = $interval->days + 1; // Including both start and end days
                                        $total = $days * $booking['car_price'];
                                        echo 'NRS ' . number_format($total, 2);
                                    } else {
                                        echo 'N/A';
                                    }
                                ?></td>
                                <td>
                                    <?php 
                                        $status = strtolower(htmlspecialchars($booking['status'] ?? 'pending'));
                                        $statusClass = '';
                                        switch($status) {
                                            case 'approved':
                                                $statusClass = 'status-approved';
                                                break;
                                            case 'declined':
                                                $statusClass = 'status-declined';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'status-cancelled';
                                                break;
                                            default:
                                                $statusClass = 'status-pending';
                                        }
                                        echo "<span class='status-badge $statusClass'>" . ucfirst($status) . "</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">No bookings found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
        <?php
        // Check if any booking is approved
        $hasApprovedBooking = false;
        foreach ($bookings as $booking) {
            if ($booking['status'] === 'approved') {
                $hasApprovedBooking = true;
                break;
            }
        }
        if ($hasApprovedBooking): ?>
            <footer class="booking-footer">
                <div class="success-message">
                    <p>✓ Your booking has been successfully approved!</p>
                    <p>Please wait for our call. We will contact you shortly for car delivery.</p>
                </div>
            </footer>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?> 