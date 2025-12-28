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

// Fetch data for reports
$availableCarsQuery = "SELECT COUNT(*) as count FROM cars";
$bookedCarsQuery = "SELECT COUNT(*) as count FROM bookings";
$manageBookingsQuery = "SELECT 
                            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                            SUM(CASE WHEN status = 'declined' THEN 1 ELSE 0 END) as declined,
                            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
                        FROM bookings";

$availableCarsResult = $conn->query($availableCarsQuery);
$bookedCarsResult = $conn->query($bookedCarsQuery);
$manageBookingsResult = $conn->query($manageBookingsQuery);

$availableCarsCount = $availableCarsResult->fetch_assoc()['count'];
$bookedCarsCount = $bookedCarsResult->fetch_assoc()['count'];
$manageBookingsData = $manageBookingsResult->fetch_assoc();
$approvedCount = $manageBookingsData['approved'];
$declinedCount = $manageBookingsData['declined'];
$pendingCount = $manageBookingsData['pending'];

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .section {
            display: none; /* Hide all sections by default */
        }

        .active {
            display: block; /* Show active section */
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
        }

        form {
            margin-top: 20px;
        }

        form div {
            margin-bottom: 15px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
        }

        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px;
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

        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <p>Welcome admin <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>.</p>
        <a href="admin_dash.php">Dashboard</a>
        <a href="available_cars.php">Available Cars</a>
        <a href="booked_cars.php">Booked Cars</a>
        <a href="reports.php">Reports</a> <!-- Link to the reports page -->
        <a href="admin_login.php?action=logout" class="logout-btn">Logout</a>
    </div>

    <div class="main-content">
        <h1>Reports</h1>

        <div class="chart-container">
            <canvas id="availableCarsChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="bookedCarsChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="manageBookingsChart"></canvas>
        </div>
    </div>

    <script>
        const availableCarsCount = <?php echo $availableCarsCount; ?>;
        const bookedCarsCount = <?php echo $bookedCarsCount; ?>;
        const approvedCount = <?php echo $approvedCount; ?>;
        const declinedCount = <?php echo $declinedCount; ?>;
        const pendingCount = <?php echo $pendingCount; ?>;

        // Available Cars Chart
        const ctx1 = document.getElementById('availableCarsChart').getContext('2d');
        const availableCarsChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Available Cars'],
                datasets: [{
                    label: 'Count',
                    data: [availableCarsCount],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Booked Cars Chart
        const ctx2 = document.getElementById('bookedCarsChart').getContext('2d');
        const bookedCarsChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Booked Cars'],
                datasets: [{
                    label: 'Count',
                    data: [bookedCarsCount],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Manage Bookings Chart
        const ctx3 = document.getElementById('manageBookingsChart').getContext('2d');
        const manageBookingsChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['Approved', 'Declined', 'Pending'],
                datasets: [{
                    label: 'Count',
                    data: [approvedCount, declinedCount, pendingCount],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html> 