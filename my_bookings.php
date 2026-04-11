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
        b.booking_from, b.booking_to, b.esewa_status, c.price as car_price
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --accent-color: #00c6ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030303;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background Effects */
        .background-iframe-container {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;
            overflow: hidden;
        }
        .background-iframe-container iframe {
            width: 100%; height: 100%; border: none; pointer-events: none;
            transform: scale(1.1); filter: brightness(0.2) blur(10px);
        }
        .overlay-vignette {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
        }

        .bookings-container {
            width: 95%; max-width: 1200px; margin: 80px auto 40px;
            padding: 40px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10;
        }

        .back-btn {
            display: inline-flex; align-items: center; gap: 10px; color: var(--text-muted);
            text-decoration: none; transition: all 0.3s ease; font-weight: 500; margin-bottom: 20px;
        }
        .back-btn:hover { color: var(--text-main); transform: translateX(-5px); }

        h2 {
            font-size: 2.5rem; font-weight: 600; background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-transform: uppercase; letter-spacing: 2px; margin-bottom: 40px;
        }

        /* Table Styling */
        .bookings-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; margin-top: 20px; }
        
        .bookings-table th { 
            text-align: left; padding: 20px; color: var(--text-muted); font-size: 13px; 
            text-transform: uppercase; letter-spacing: 1px; font-weight: 600;
        }
        
        .bookings-table td { 
            padding: 20px; background: rgba(255, 255, 255, 0.03); 
            border-top: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border); 
            color: var(--text-main);
        }
        
        .bookings-table td:first-child { border-left: 1px solid var(--glass-border); border-radius: 12px 0 0 12px; font-weight: 600; color: var(--accent-color); }
        .bookings-table td:last-child { border-right: 1px solid var(--glass-border); border-radius: 0 12px 12px 0; }
        
        .bookings-table tr:hover td { background: rgba(255, 255, 255, 0.07); }

        /* Status Badges */
        .status-badge {
            padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; 
            font-weight: 600; text-transform: uppercase; letter-spacing: 1px;
            display: inline-block;
        }
        .status-approved { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.4); }
        .status-declined { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); }
        .status-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.4); }
        .status-cancelled { background: rgba(107, 114, 128, 0.2); color: #9ca3af; border: 1px solid rgba(107, 114, 128, 0.4); }

        .total-amount { color: #10b981; font-weight: 600; font-family: monospace; font-size: 1.1em; }

        .booking-footer {
            margin-top: 40px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            text-align: center;
            color: #10b981;
        }

        .success-message p:first-child { font-weight: 600; font-size: 1.1rem; margin-bottom: 5px; }

    </style>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="bookings-container">
        <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <main>
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
                        <th>Transaction Status</th>
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
                                        $eStatus = htmlspecialchars($booking['esewa_status'] ?? 'Pending');
                                        $eClass = $eStatus == 'Completed' ? 'status-approved' : 'status-pending';
                                        echo "<span class='status-badge $eClass'>" . $eStatus . "</span>";
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $status = strtolower(htmlspecialchars($booking['status'] ?? 'pending'));
                                        $statusClass = 'status-' . $status;
                                        echo "<span class='status-badge $statusClass'>" . ucfirst($status) . "</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">No bookings found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
        <?php
        $hasApprovedBooking = false;
        foreach ($bookings as $booking) {
            if ($booking['status'] === 'approved') {
                $hasApprovedBooking = true;
                break;
            }
        }
        if ($hasApprovedBooking): ?>
            <div class="booking-footer">
                <div class="success-message">
                    <p><i class="fas fa-check-circle"></i> Your booking has been approved!</p>
                    <p>Please wait for our call. We will contact you shortly to coordinate delivery.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?> 