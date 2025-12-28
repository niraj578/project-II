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
    <title>Login - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="login-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* Add some basic styles for the layout */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Gradient background */
        }
        .login-box {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 400px; /* Fixed width for better layout */
            text-align: center; /* Center text */
        }
        h2 {
            margin-bottom: 20px;
            color: #333; /* Darker color for the heading */
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group i {
            margin-right: 10px;
            color: #007BFF; /* Icon color */
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s; /* Smooth transition for border color */
        }
        .input-group input:focus {
            border-color: #007BFF; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px; /* Increase font size */
            transition: background-color 0.3s, transform 0.2s; /* Smooth transition */
        }
        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px); /* Slight lift effect on hover */
        }
        .register-link {
            margin-top: 15px;
            text-align: center;
            color: #555; /* Slightly lighter color for the link */
        }
        .register-link a {
            color: #007BFF; /* Link color */
            text-decoration: none; /* Remove underline */
            transition: color 0.3s; /* Smooth transition for color */
        }
        .register-link a:hover {
            color: #0056b3; /* Darker color on hover */
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <form action="admin_login.php" method="POST">
            <div class="input-group">
                <i class=""></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class=""></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
                        
