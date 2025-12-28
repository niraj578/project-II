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
        b.status, c.name as car_name, c.price as car_price 
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
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        form {
            display: inline;
        }

        select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        select:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
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
    </style>
</head>
<body>
    <h1>Manage Bookings</h1>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Name</th>
            <th>Car ID</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Pickup Location</th>
            <th>Drop-off Location</th>
            <th>Total Amount</th>
            <th>Booking From</th>
            <th>Booking To</th>
            <th>Booking Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['carid']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['pickup_location']); ?></td>
            <td><?php echo htmlspecialchars($row['dropoff_location']); ?></td>
            <td><?php 
                if (!empty($row['booking_from']) && !empty($row['booking_to']) && !empty($row['car_price'])) {
                    $date1 = new DateTime($row['booking_from']);
                    $date2 = new DateTime($row['booking_to']);
                    $interval = $date1->diff($date2);
                    $days = $interval->days + 1; // Including both start and end days
                    $total = $days * $row['car_price'];
                    echo 'NRS ' . number_format($total, 2);
                } else {
                    echo 'N/A';
                }
            ?></td>
            <td><?php echo !empty($row['booking_from']) ? date('Y-m-d', strtotime($row['booking_from'])) : 'N/A'; ?></td>
            <td><?php echo !empty($row['booking_to']) ? date('Y-m-d', strtotime($row['booking_to'])) : 'N/A'; ?></td>
            <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
            <td><?php 
                $status = htmlspecialchars($row['status']);
                $statusClass = '';
                switch($status) {
                    case 'approved':
                        $statusClass = 'status-approved';
                        break;
                    case 'declined':
                        $statusClass = 'status-declined';
                        break;
                    default:
                        $statusClass = 'status-pending';
                }
                echo "<span class='status-badge $statusClass'>$status</span>";
            ?></td>
            <td>
                <form action="update_booking.php" method="POST">
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
    </table>
</body>
</html>

<?php
$conn->close();
?> 