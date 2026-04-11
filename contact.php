<?php
session_start();
$isLoggedIn = isset($_SESSION['login']);
$status = isset($_GET['status']) ? $_GET['status'] : '';
$defaultName = "";
$defaultEmail = "";
if ($isLoggedIn) {
    $defaultName = $_SESSION['login']['full_name'];
    $defaultEmail = $_SESSION['login']['email'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Car Rental Service</title>
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

        .container {
            width: 95%; max-width: 800px; margin: 80px auto 40px;
            padding: 50px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10;
        }

        h1 {
            font-size: 2.2rem; font-weight: 600; background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-transform: uppercase; letter-spacing: 2px; text-align: center; margin-bottom: 20px;
        }

        p { text-align: center; color: var(--text-muted); margin-bottom: 30px; line-height: 1.6; }

        .success-message {
            background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.4);
            padding: 15px; border-radius: 12px; text-align: center; margin-bottom: 25px;
        }

        form { display: flex; flex-direction: column; gap: 20px; }

        input, textarea {
            background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border);
            color: white; padding: 15px; border-radius: 10px; font-family: inherit; font-size: 1rem;
            outline: none; transition: 0.3s;
        }

        input:focus, textarea:focus { border-color: #00c6ff; box-shadow: 0 0 0 4px rgba(0,198,255,0.1); }
        input[readonly] { opacity: 0.6; cursor: not-allowed; }

        .submit-button {
            background: var(--primary-gradient); color: white; border: none; padding: 16px;
            border-radius: 12px; font-weight: 600; font-size: 1.1rem; cursor: pointer;
            transition: all 0.3s; margin-top: 10px;
        }
        .submit-button:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,198,255,0.3); }

        .contact-info {
            margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--glass-border);
            text-align: center; color: var(--text-muted); font-size: 0.95rem;
        }
        .contact-info strong { color: white; display: block; margin-bottom: 5px; }

        .back-link {
            display: inline-block; margin-top: 30px; color: var(--text-muted);
            text-decoration: none; transition: 0.3s; font-size: 0.9rem;
        }
        .back-link:hover { color: #00c6ff; transform: translateX(-3px); }

    </style>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="container">
        <h1>Contact Us</h1>
        
        <?php if ($status === 'success'): ?>
            <div class="success-message"><i class="fas fa-check-circle"></i> Message sent successfully!</div>
        <?php endif; ?>
        
        <p>If you have any questions or need assistance, feel free to reach out to us using the form below.</p>
        
        <form action="submit_contact.php" method="POST">
            <input type="text" name="name" placeholder="Your Name" value="<?php echo htmlspecialchars($defaultName); ?>" required <?php echo $isLoggedIn ? 'readonly' : ''; ?>>
            <input type="email" name="email" placeholder="Your Email" value="<?php echo htmlspecialchars($defaultEmail); ?>" required <?php echo $isLoggedIn ? 'readonly' : ''; ?>>
            <textarea name="message" rows="5" placeholder="How can we help you?" required></textarea>
            <button type="submit" class="submit-button">Send Message</button>
        </form>

        <div class="contact-info">
            <p>You can also reach us at:</p>
            <div style="display: flex; justify-content: center; gap: 30px; margin-top: 15px;">
                <div><i class="fas fa-envelope" style="color: #00c6ff; margin-bottom: 5px;"></i><br>support@carrentalservice.com</div>
                <div><i class="fas fa-phone" style="color: #00c6ff; margin-bottom: 5px;"></i><br>01-54856932</div>
            </div>
        </div>
        
        <div style="text-align: center;">
        <?php if ($isLoggedIn): ?>
            <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <?php else: ?>
            <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Home Page</a>
        <?php endif; ?>
        </div>
    </div>
</body>
</html> 