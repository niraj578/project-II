<?php
session_start(); // Start the session

// Check if the logout button is clicked
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header('Location: admin_login.php'); // Redirect to the admin login page
    exit(); // Exit the script
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    // $conn = new mysqli("localhost", "root", "", "carrentaldb");
    
    // if ($conn->connect_error) {
    //     die("Connection Error");
    // }
    include 'connection.php';
    
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$pass'";
    $r = $conn->query($sql);
    
    if ($r->num_rows > 0) {
        $row = $r->fetch_assoc();
        $_SESSION['login'] = $row;
        header('Location: admin_dash.php'); // Redirect to admin dashboard
        exit(); // Exit the script
    } else {
        echo "<script>alert('Login Error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="login-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff4b2b;
            --secondary-color: #ff416c;
            --accent-color: #ff4b2b;
        }
        .login-box {
            border-top: 4px solid var(--primary-color);
        }
        button {
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            box-shadow: 0 4px 15px rgba(255, 75, 43, 0.3);
        }
        button:hover {
            box-shadow: 0 6px 20px rgba(255, 75, 43, 0.4);
        }
        .input-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(255, 75, 43, 0.2);
        }
    </style>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-blur"></div>
    </div>
    <div class="login-container">
        <div class="login-box">
            <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
            <h2 style="color: white; margin-top: 10px;">Admin Portal</h2>
            <p style="color: rgba(255,255,255,0.6); margin-bottom: 30px;">Authorized Access Only</p>
            <form action="admin_login.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Admin Email" required>
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <button type="submit" name="login">Access Dashboard</button>
            </form>
        </div>
    </div>
</body>
</html>
                        
