<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="register-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-blur"></div>
    </div>
    <div class="register-container">
        <div class="register-box">
            <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
            <h2>Create Account</h2>
            <form action="register_process.php" method="POST">
                <div class="input-group">
                    <input type="text" name="full_name" placeholder="Full Name" required>
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="input-group">
                    <input type="tel" id="phone" name="phone" placeholder="Phone" required pattern="^\d{10}$" title="Please enter a 10-digit phone number">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="terms">
                    <label><input type="checkbox" required> I agree to the Terms & Conditions</label>
                </div>
                <button type="submit">Register</button>
                <div class="login-link">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>