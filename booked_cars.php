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

// Fetch all bookings from the bookings table
$sql = "SELECT b.carid, b.name, b.email, b.phone, b.pickup_location, b.dropoff_location, b.booking_from, b.booking_to, b.booking_time, c.name AS car_name 
        FROM bookings b 
        JOIN cars c ON b.carid = c.carid"; // Join with cars table to get car names
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Cars</title>
    <link rel="stylesheet" href="style.css"> <!-- Ensure your CSS file is linked -->
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100vh; /* Ensure body takes full height */
        }

        .sidebar {
            width: 250px;
            background-color: #007BFF;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh; /* Full height sidebar */
            position: fixed; /* Fix the sidebar to the left */
        }

        .sidebar h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .sidebar a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 250px; /* Leave space for the sidebar */
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Booked Cars</h1>
    <div class="booked-cars-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Car Name</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Pickup Location</th>
                        <th>Drop-off Location</th>
                        <th>Booking from</th>
                        <th>Booking to</th>
                        <th>Booking Time</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['car_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['pickup_location']); ?></td>
                            <td><?php echo htmlspecialchars($row['dropoff_location']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_from']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_to']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No cars have been booked yet.</p>
        <?php endif; ?>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>