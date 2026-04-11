<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrental";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ff4b2b 0%, #ff416c 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --card-hover: rgba(255, 255, 255, 0.1);
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
            filter: brightness(0.3) blur(10px);
        }

        .overlay-vignette {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
            z-index: 0;
        }

        /* Smoky Effect */
        .smoke-container {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .smoke {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(255, 65, 108, 0.1) 0%, rgba(255, 65, 108, 0) 70%);
            filter: blur(80px);
            border-radius: 50%;
            opacity: 0.4;
        }

        .smoke-1 { top: -200px; left: -200px; animation: drift 30s linear infinite alternate; }
        .smoke-2 { bottom: -200px; right: -200px; animation: drift 35s linear infinite alternate-reverse; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1) rotate(0deg); }
            to { transform: translate(150px, 150px) scale(1.3) rotate(360deg); }
        }

        .main-content {
            width: 100%;
            max-width: 1200px;
            margin: 60px auto;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 42px;
            font-weight: 600;
            letter-spacing: 2px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
        }

        .welcome-msg {
            margin-bottom: 60px;
            color: var(--text-muted);
            font-size: 18px;
            letter-spacing: 1px;
        }

        /* Glass Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .material-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 40px 30px;
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            position: relative;
        }

        .material-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
            transform-origin: left;
        }

        .material-card:hover {
            transform: translateY(-10px);
            background: var(--card-hover);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .material-card:hover::before {
            transform: scaleX(1);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: var(--glass-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            transition: all 0.4s ease;
            border: 1px solid var(--glass-border);
        }

        .material-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .card-icon i {
            font-size: 30px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Glowing colors for different cards */
        .icon-orange i { background: linear-gradient(135deg, #ffa726, #fb8c00); -webkit-background-clip: text; }
        .icon-green i { background: linear-gradient(135deg, #66bb6a, #43a047); -webkit-background-clip: text; }
        .icon-blue i { background: linear-gradient(135deg, #26c6da, #00acc1); -webkit-background-clip: text; }
        .icon-purple i { background: linear-gradient(135deg, #ab47bc, #8e24aa); -webkit-background-clip: text; }
        .icon-red i { background: linear-gradient(135deg, #ef5350, #e53935); -webkit-background-clip: text; }
        .icon-gray i { background: linear-gradient(135deg, #9e9e9e, #616161); -webkit-background-clip: text; }

        .card-content h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .card-footer {
            margin-top: 15px;
            color: var(--text-muted);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-footer i {
            font-size: 12px;
            color: var(--text-muted);
            -webkit-text-fill-color: initial;
            background: none;
        }

        /* Top Bar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 15px 40px;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
        }

        .logo {
            font-weight: 600;
            font-size: 20px;
            letter-spacing: 2px;
            color: white;
            text-decoration: none;
        }

        .logo span {
            color: #ff4b2b;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>
    <script>
        // Add any JavaScript here if needed
    </script>
</head>
<body>
    <!-- Background Effects -->
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="smoke-container">
            <div class="smoke smoke-1"></div>
            <div class="smoke smoke-2"></div>
        </div>
        <div class="overlay-vignette"></div>
    </div>

    <!-- Top Navbar -->
    <nav class="top-navbar">
        <a href="index.php" class="logo">CAR<span>RENTAL</span></a>
        <div class="admin-profile">
            <span style="color: var(--text-muted);">Admin Panel</span>
            <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['login']['email'] ?? 'A', 0, 1)); ?></div>
        </div>
    </nav>

    <div class="main-content">
        <!-- Dashboard Home Grid -->
        <div class="dashboard-home">
            <h1 style="margin-top: 40px;">Control Center</h1>
            <p class="welcome-msg">System Overview & Management</p>

            <div class="dashboard-grid">
                
                <!-- Available Cars -->
                <a href="available_cars.php" class="material-card">
                    <div class="card-icon icon-orange">
                        <i class="fas fa-car-side"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Fleet Status</h3>
                    </div>
                    <div class="card-footer">
                        <i class="fas fa-info-circle"></i> View all available vehicles
                    </div>
                </a>

                <!-- Add Cars -->
                <a href="add_car.php" class="material-card">
                    <div class="card-icon icon-green">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Inventory</h3>
                    </div>
                    <div class="card-footer">
                        <i class="fas fa-plus-circle"></i> Add new vehicle to fleet
                    </div>
                </a>

                <!-- Manage Booking -->
                <a href="managebooking.php" class="material-card">
                    <div class="card-icon icon-blue">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Bookings</h3>
                    </div>
                    <div class="card-footer">
                        <i class="fas fa-clock"></i> Manage reservations
                    </div>
                </a>

                <!-- Messages -->
                <a href="message.php" class="material-card">
                    <div class="card-icon icon-purple">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Messages</h3>
                    </div>
                    <div class="card-footer">
                        <i class="fas fa-reply"></i> Customer communications
                    </div>
                </a>

                <!-- Reports -->
                <a href="reports.php" class="material-card">
                    <div class="card-icon icon-red">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Analytics</h3>
                    </div>
                    <div class="card-footer">
                        <i class="fas fa-chart-line"></i> Generate system reports
                    </div>
                </a>

                <!-- Payments -->
                <a href="admin_payments.php" class="material-card">
                    <div class="card-icon icon-green">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Payments</h3>
                    </div>
                    <div class="card-footer">
                        <i class="fas fa-dollar-sign"></i> View payment transactions
                    </div>
                </a>

                 <!-- Logout -->
                 <a href="admin_login.php?action=logout" class="material-card">
                    <div class="card-icon icon-gray">
                        <i class="fas fa-power-off"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Sign Out</h3>
                    </div>
                    <div class="card-footer">
                        <i class="fas fa-sign-out-alt"></i> End secure session
                    </div>
                </a>

            </div>
        </div>
    </div>
</body>
</html>
