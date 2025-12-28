<?php
// Start session
session_start();

// // Database connection parameters
// $servername = "localhost"; // Change if necessary
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, start session and redirect
            $_SESSION['login'] = $row; // Store user data in session
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            echo "<script>
                    alert('Invalid username or password.');
                    window.location.href = 'login.php';
                  </script>";
        }
    } else {
        // User not found
        echo "<script>
                alert('Invalid username or password.');
                window.location.href = 'login.php';
              </script>";
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
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
        .input-group {
            position: relative; /* Position relative for absolute positioning of icons */
            margin-bottom: 15px; /* Space between input groups */
        }

        .input-group i {
            position: absolute; /* Position the icon absolutely */
            right: 10px; /* Space from the right */
            top: 50%; /* Center vertically */
            transform: translateY(-50%); /* Adjust for vertical centering */
            color: #888; /* Icon color */
        }

        .input-group input {
            padding-right: 30px; /* Space for the icon */
            width: 100%; /* Full width */
            padding: 10px; /* Padding inside the input */
            border: 1px solid #ccc; /* Border style */
            border-radius: 5px; /* Rounded corners */
            font-size: 16px; /* Font size */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
            <h2>User Login</h2>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit">Login</button>
                <div class="register-link">
                    Don't have an account? <a href="register.php">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 