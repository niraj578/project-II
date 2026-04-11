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
            width: 95%;
            max-width: 1200px;
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
        }

        th {
            text-align: left;
            padding: 20px;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        td {
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
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

        .action-links a {
            color: var(--text-main);
            text-decoration: none;
            margin-right: 15px;
            font-size: 14px;
            transition: color 0.3s;
        }

        .action-links a:hover {
            color: #ff4b2b;
        }

        .delete-link {
            color: #ff4d4d !important;
        }

        .price-badge {
            background: var(--primary-gradient);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
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
            <h1>Available Cars</h1>
            <a href="admin_dash.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </header>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Car ID</th>
                        <th>Car Name</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Price/Day</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td style="font-family: monospace; color: var(--accent-color); font-weight: 600;">#<?php echo htmlspecialchars($row['carid']); ?></td>
                                <td style="font-weight: 600;"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['model']); ?></td>
                                <td><?php echo htmlspecialchars($row['year']); ?></td>
                                <td><span class="price-badge">NRS <?php echo number_format($row['price']); ?></span></td>
                                <td class="action-links">
                                    <a href="update_car.php?id=<?php echo $row['carid']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="delete_car.php?id=<?php echo $row['carid']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this car?');"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">No available cars found in the fleet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>