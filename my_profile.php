<?php
session_start();
$isLoggedIn = isset($_SESSION['login']);
if (!$isLoggedIn) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['login']['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Car Rental Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            display: block; /* Override flex */
        }

        .background-iframe-container {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; overflow: hidden;
        }
        .background-iframe-container iframe {
            width: 100%; height: 100%; border: none; pointer-events: none;
            transform: scale(1.1); filter: brightness(0.2) blur(10px);
        }
        .overlay-vignette {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
        }

        .main-content {
            width: 95%; max-width: 600px; margin: 100px auto 40px;
            padding: 50px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10; text-align: center;
        }

        .profile-avatar {
            width: 100px; height: 100px; background: var(--primary-gradient);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 3rem; color: white; margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(0, 198, 255, 0.4);
        }

        h1 {
            font-size: 2.5rem; font-weight: 600; margin-bottom: 10px;
        }
        h1 span {
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        
        p.welcome-text { color: var(--text-muted); margin-bottom: 40px; font-size: 1.1rem; }

        .profile-actions {
            display: flex; flex-direction: column; gap: 15px; width: 100%; max-width: 300px; margin: 0 auto;
        }

        .action-btn {
            background: rgba(255,255,255,0.05); color: white; text-decoration: none;
            padding: 15px; border-radius: 12px; border: 1px solid var(--glass-border);
            font-weight: 500; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .action-btn:hover { background: rgba(255,255,255,0.1); transform: translateY(-3px); }

        .logout-btn { border-color: rgba(239, 68, 68, 0.5); color: #ff6b6b; }
        .logout-btn:hover { background: rgba(239, 68, 68, 0.2); }

        .back-link {
            display: inline-block; margin-top: 30px; color: var(--text-muted);
            text-decoration: none; transition: 0.3s; font-size: 0.9rem;
        }
        .back-link:hover { color: #00c6ff; }

    </style>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($username, 0, 1)); ?>
        </div>
        <h1>Hello, <span><?php echo htmlspecialchars($username); ?></span></h1>
        <p class="welcome-text">Manage your account and bookings</p>

        <div class="profile-actions">
            <a href="my_bookings.php" class="action-btn"><i class="fas fa-calendar-check"></i> My Bookings</a>
            <a href="user_messages.php" class="action-btn"><i class="fas fa-envelope"></i> My Messages</a>
            <a href="logout.php" class="action-btn logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>
