<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="register-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
            <h2>Create Account</h2>
            <form action="register_process.php" method="POST">
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="full_name" placeholder="Full Name" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-phone"></i>
                    <input type="tel" id="phone" name="phone" placeholder="Phone" required required pattern="^\d{10}$" title="Please enter a 10-digit phone number">
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
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