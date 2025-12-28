<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
            overflow-y: auto; /* Allow scrolling if content overflows */
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
            overflow-y: auto; /* Allow scrolling if content overflows */
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
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
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        form input:focus {
            border-color: #007BFF; /* Change border color on focus */
            outline: none; /* Remove default outline */
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

        /* Section styles */
        .section {
            display: none; /* Hide all sections by default */
        }

        .active {
            display: block; /* Show active section */
        }
    </style>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            var sections = document.querySelectorAll('.section');
            sections.forEach(function(section) {
                section.classList.remove('active');
            });
            // Show the selected section
            document.getElementById(sectionId).classList.add('active');
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <p>Welcome admin <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>.</p>
        
        <a href="available_cars.php">Available Cars</a>
        <a href="add_car.php">Add Cars</a>
        <a href="reports.php">Reports</a>
        <a href="managebooking.php">Manage Booking</a>
        <a href="admin_chatbot_messages.php">Chatbot Messages</a>
       
        <a href="admin_login.php?action=logout" class="logout-btn">Logout</a>

       

    <div class="main-content">
        <!-- Reports Section -->
        <div id="reports" class="section">
            <h2>Reports</h2>
            <p>View and generate reports here.</p>
            <!-- Add reporting functionality here -->
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
