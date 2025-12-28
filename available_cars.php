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

// Fetch available cars from the database
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Cars - Admin Dashboard</title>
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
            background-color:rgb(197, 17, 17);
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
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <p>Welcome admin <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>.</p>
        <a href="admin_dash.php">Dashboard</a>
        <a href="available_cars.php">Available Cars</a>
        <a href="booked_cars.php">Booked Cars</a>
        <a href="reports.php">Reports</a>
        <a href="admin_login.php?action=logout" class="logout-btn">Logout</a>
    </div>

    <div class="main-content">
        <h1>Available Cars</h1>
        <table>
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Car Name</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Price per Day</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['carid']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['model']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td>NRS <?php echo htmlspecialchars($row['price']); ?></td>
                            <td>
                                <a href="update_car.php?id=<?php echo $row['carid']; ?>">Update</a> | 
                                <a href="delete_car.php?id=<?php echo $row['carid']; ?>" onclick="return confirm('Are you sure you want to delete this car?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No available cars found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>