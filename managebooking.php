<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// // Database connection
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

// Fetch all bookings
$sql = "SELECT b.id, b.name, b.carid, b.email, b.phone, b.pickup_location, 
        b.dropoff_location, b.booking_from, b.booking_to, b.booking_time, 
        b.status, b.esewa_status, c.name as car_name, c.price as car_price 
        FROM bookings b 
        LEFT JOIN cars c ON b.carid = c.carid 
        ORDER BY b.id DESC";
$result = $conn->query($sql);

// Debug the results
if ($result) {
    $firstRow = $result->fetch_assoc();
    if ($firstRow) {
        error_log("First booking data: " . print_r($firstRow, true));
        $result->data_seek(0); // Reset pointer to beginning
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ff4b2b 0%, #ff416c 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --accent-color: #ff4b2b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
        }

        .background-iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            pointer-events: none;
            transform: scale(1.1);
            filter: brightness(0.2) blur(10px);
        }

        .overlay-vignette {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
        }

        .main-content {
            width: 98%;
            max-width: 1600px;
            margin: 100px auto 40px;
            padding: 40px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            position: relative;
            z-index: 10;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-btn:hover {
            color: var(--text-main);
            transform: translateX(-5px);
        }

        /* Table Styling */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            min-width: 1400px;
        }

        th {
            text-align: left;
            padding: 20px 15px;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
        }

        td {
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
            font-size: 14px;
        }

        td:first-child {
            border-left: 1px solid var(--glass-border);
            border-radius: 12px 0 0 12px;
        }

        td:last-child {
            border-right: 1px solid var(--glass-border);
            border-radius: 0 12px 12px 0;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.07);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-approved { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
        .status-declined { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
        .status-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
        .status-cancelled { background: rgba(107, 114, 128, 0.2); color: #9ca3af; border: 1px solid rgba(107, 114, 128, 0.3); }

        select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 6px 10px;
            border-radius: 8px;
            outline: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        /* Green background when Approve is selected */
        select[name="status"]:has(option[value="approved"]:checked),
        select[name="status"] option[value="approved"] {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        /* Red background when Decline is selected */
        select[name="status"]:has(option[value="declined"]:checked),
        select[name="status"] option[value="declined"] {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        select:focus {
            border-color: var(--accent-color);
        }

        button {
            padding: 7px 15px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: 5px;
        }

        button:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(255, 75, 43, 0.4);
        }

    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <header>
            <h1>Manage Bookings</h1>
            <a href="admin_dash.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </header>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Car ID</th>
                        <th>Contact</th>
                        <th>Location (P/D)</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Dates</th>
                        <th>Booking Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="font-family: monospace; color: var(--accent-color);">#<?php echo $row['id']; ?></td>
                        <td>
                            <div style="font-weight: 600;"><?php echo htmlspecialchars($row['name']); ?></div>
                            <div style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($row['email']); ?></div>
                        </td>
                        <td style="font-family: monospace;"><?php echo htmlspecialchars($row['carid']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <div style="font-size: 12px;"><i class="fas fa-map-marker-alt" style="color: #10b981;"></i> <?php echo htmlspecialchars($row['pickup_location']); ?></div>
                            <div style="font-size: 12px;"><i class="fas fa-flag-checkered" style="color: #ef4444;"></i> <?php echo htmlspecialchars($row['dropoff_location']); ?></div>
                        </td>
                        <td style="font-weight: 600;">
                            <?php 
                                if (!empty($row['booking_from']) && !empty($row['booking_to']) && !empty($row['car_price'])) {
                                    $date1 = new DateTime($row['booking_from']);
                                    $date2 = new DateTime($row['booking_to']);
                                    $interval = $date1->diff($date2);
                                    $days = $interval->days + 1;
                                    $total = $days * $row['car_price'];
                                    echo 'NRS ' . number_format($total);
                                } else {
                                    echo 'N/A';
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                            $eStatus = htmlspecialchars($row['esewa_status'] ?? 'Pending');
                            $eClass = $eStatus == 'Completed' ? 'status-approved' : 'status-pending';
                            echo "<span class='status-badge $eClass'>$eStatus</span>";
                            ?>
                        </td>
                        <td>
                            <div style="font-size: 12px;"><?php echo !empty($row['booking_from']) ? date('M d', strtotime($row['booking_from'])) : 'N/A'; ?> - <?php echo !empty($row['booking_to']) ? date('M d', strtotime($row['booking_to'])) : 'N/A'; ?></div>
                        </td>
                        <td style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($row['booking_time']); ?></td>
                        <td><?php 
                            $status = htmlspecialchars($row['status']);
                            $statusClass = '';
                            switch($status) {
                                case 'approved': $statusClass = 'status-approved'; break;
                                case 'declined': $statusClass = 'status-declined'; break;
                                case 'cancelled': $statusClass = 'status-cancelled'; break;
                                default: $statusClass = 'status-pending';
                            }
                            echo "<span class='status-badge $statusClass'>$status</span>";
                        ?></td>
                        <td>
                            <form action="update_booking.php" method="POST" style="display: flex; align-items: center;">
                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                <select name="status" <?php echo $row['status'] === 'cancelled' ? 'disabled' : ''; ?>>
                                    <option value="approved" <?php if ($row['status'] == 'approved') echo 'selected'; ?>>Approve</option>
                                    <option value="declined" <?php if ($row['status'] == 'declined') echo 'selected'; ?>>Decline</option>
                                </select>
                                <?php if ($row['status'] !== 'cancelled'): ?>
                                    <button type="submit">Update</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?> 