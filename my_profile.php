<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['login']);
if (!$isLoggedIn) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['login']['full_name']; // Get the username

$host = 'localhost'; // Database host
$db = 'carrentaldb'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Car Rental Service</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <style>
/* General Page Styling */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f4f8;
    display: flex;
}

/* Sidebar Styling */
.sidebar {
    width: 250px;
    background-color: #007bff;
    color: white;
    padding: 20px;
    height: 100vh;
    position: fixed;
}

.sidebar h2 {
    margin-bottom: 20px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin: 15px 0;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
    transition: background-color 0.3s;
}

.sidebar ul li a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

/* Main Content Styling */
.main-content {
    margin-left: 270px;
    padding: 20px;
    background-color: white;
    width: calc(100% - 270px);
    min-height: 100vh;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

h1 {
    color: #333;
    font-size: 2.5rem;
    text-align: center;
}

/* Profile Options Styling */
.profile-options {
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 20px;
}

.profile-options h2 {
    color: #007BFF;
    font-size: 1.8rem;
    text-align: center;
}

.profile-options ul {
    list-style: none;
    padding: 0;
    text-align: center;
}

.profile-options li {
    margin: 10px 0;
}

.profile-options a {
    text-decoration: none;
    color: #007BFF;
    padding: 10px;
    border: 2px solid #007BFF;
    border-radius: 5px;
    display: inline-block;
    transition: background-color 0.3s, color 0.3s;
    font-weight: bold;
    width: 200px;
}

.profile-options a:hover {
    background-color: #007BFF;
    color: white;
}

/* Logout Button Styling */
.logout-btn {
    color: red !important;
    border-color: red !important;
}

.logout-btn:hover {
    background-color: red !important;
    color: white !important;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        text-align: center;
    }

    .main-content {
        margin-left: 0;
        width: 100%;
        padding: 10px;
    }

    .profile-options a {
        width: 100%;
    }
}
</style>

    <div class="dashboard-container">
        <div class="sidebar">
            <h2>My Profile</h2>
            <ul>
                
                <li><a href="my_bookings.php">My Bookings</a></li>
                <li><a href="payments.php">Payments</a></li>
                <li><a href="dashboard.php">Back to Dashboard</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </div>
        

        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

            </div>
        </div>
    </div>
</body>
</html>
