<?php
session_start(); // Start the session
include 'connection.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['login']);
if (!$isLoggedIn) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$email = $_SESSION['login']['email'];
$username = $_SESSION['login']['full_name']; // Get the username

// Fetch latest booking for the user to get price and details
$sql = "SELECT b.*, c.name as car_name, c.model as car_model, c.price as car_price 
        FROM bookings b 
        JOIN cars c ON b.carid = c.carid 
        WHERE b.email = ? 
        ORDER BY b.id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

$totalAmount = 0;
$days = 0;
if ($booking) {
    try {
        $date1 = new DateTime($booking['booking_from']);
        $date2 = new DateTime($booking['booking_to']);
        $interval = $date1->diff($date2);
        $days = $interval->days + 1;
        $totalAmount = $days * $booking['car_price'];
    } catch (Exception $e) {
        $totalAmount = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Car Rental Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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

        /* Background Effects */
        .background-iframe-container {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;
            overflow: hidden;
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
            width: 95%; max-width: 900px; margin: 60px auto;
            padding: 50px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10;
        }

        .back-btn {
            display: inline-flex; align-items: center; gap: 10px; color: var(--text-muted);
            text-decoration: none; transition: all 0.3s ease; font-weight: 500; margin-bottom: 30px;
        }
        .back-btn:hover { color: var(--text-main); transform: translateX(-5px); }

        h1 {
            color: white; font-weight: 600; margin-bottom: 40px; text-align: center;
            font-size: 2.2rem; letter-spacing: 1px;
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .payment-methods {
            display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;
        }

        .method-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative; overflow: hidden;
        }

        .method-card:hover {
            background: rgba(255, 255, 255, 0.07);
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .method-card i {
            font-size: 45px; margin-bottom: 20px;
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .method-card h3 { margin: 0 0 10px; color: white; font-weight: 600; }
        .method-card p { font-size: 0.9rem; color: var(--text-muted); margin: 0; }

        .payment-form {
            margin-top: 40px; padding-top: 40px; border-top: 1px solid var(--glass-border);
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .confirm-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white; padding: 16px; border: none; border-radius: 12px;
            cursor: pointer; font-size: 1.1rem; font-weight: 600; width: 100%;
            margin-top: 25px; transition: transform 0.2s;
        }

        .confirm-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }

        .hidden { display: none; }

        .payment-icon-lg { 
            font-size: 70px; margin-bottom: 25px; display: block; text-align: center; 
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .booking-summary {
            background: rgba(0, 198, 255, 0.1);
            border: 1px solid rgba(0, 198, 255, 0.2);
            border-radius: 16px; padding: 25px; margin-bottom: 40px;
        }

        .summary-header {
            font-size: 0.85rem; text-transform: uppercase; color: #00c6ff; font-weight: 700;
            margin-bottom: 15px; display: block; letter-spacing: 1px;
        }

        .summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 1rem; }
        .summary-label { color: var(--text-muted); }
        .summary-value { font-weight: 600; color: white; }
        .summary-total { 
            font-size: 1.3rem; color: #10b981; border-top: 1px solid rgba(255,255,255,0.1); 
            padding-top: 15px; margin-top: 15px; 
        }

        .change-method-btn {
            background: rgba(255,255,255,0.05); color: var(--text-muted);
            width: 100%; margin-top: 15px; border: 1px solid var(--glass-border); cursor: pointer;
            padding: 12px; border-radius: 10px; transition: 0.3s;
        }
        .change-method-btn:hover { background: rgba(255,255,255,0.1); color: white; }

    </style>
    <script>
        function showPaymentForm(method) {
            document.getElementById('payment-options').classList.add('hidden');
            document.getElementById('payment-options-title').classList.add('hidden');
            if (method === 'cash') {
                document.getElementById('cash-form').classList.remove('hidden');
            } else if (method === 'online') {
                document.getElementById('online-form').classList.remove('hidden');
            }
        }

        function goBackToOptions() {
            document.getElementById('cash-form').classList.add('hidden');
            document.getElementById('online-form').classList.add('hidden');
            document.getElementById('payment-options').classList.remove('hidden');
            document.getElementById('payment-options-title').classList.remove('hidden');
        }
    </script>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        
        <h1 id="payment-options-title">Secure Payment</h1>

        <?php if ($booking): ?>
        <div class="booking-summary">
            <span class="summary-header">Booking Summary</span>
            <div class="summary-row">
                <span class="summary-label">Vehicle</span>
                <span class="summary-value"><?php echo htmlspecialchars($booking['car_name'] . ' ' . $booking['car_model']); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Duration</span>
                <span class="summary-value"><?php echo $days; ?> Day(s)</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Price per Day</span>
                <span class="summary-value">NRS <?php echo number_format($booking['car_price'], 2); ?></span>
            </div>
            <div class="summary-row summary-total">
                <span class="summary-label">Total Amount</span>
                <span class="summary-value">NRS <?php echo number_format($totalAmount, 2); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div id="payment-options" class="payment-methods">
            <div class="method-card" onclick="showPaymentForm('cash')">
                <i class="fas fa-money-bill-wave"></i>
                <h3>Cash on Delivery</h3>
                <p>Pay when you receive the car</p>
            </div>
            <div class="method-card" onclick="showPaymentForm('online')">
                <i class="fas fa-wallet"></i>
                <h3>eSewa Payment</h3>
                <p>Pay securely via eSewa</p>
            </div>
        </div>

        <div id="cash-form" class="hidden payment-form">
            <i class="fas fa-hand-holding-usd payment-icon-lg"></i>
            <h1>Cash on Delivery</h1>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px; font-size: 1.1rem;">
                Confirm your booking with the total amount of <strong style="color: white;">NRS <?php echo number_format($totalAmount, 2); ?></strong> to be paid upon delivery.
            </p>
            <button class="confirm-btn" onclick="alert('Cash on Delivery selected for NRS <?php echo number_format($totalAmount, 2); ?>!')">Confirm Booking</button>
            <button class="change-method-btn" onclick="goBackToOptions()">Change Method</button>
        </div>

        <div id="online-form" class="hidden payment-form">
            <i class="fas fa-wallet payment-icon-lg"></i>
            <h1>eSewa Payment</h1>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 20px; font-size: 1.1rem;">
                You are paying <strong style="color: white;">NRS <?php echo number_format($totalAmount, 2); ?></strong> for your booking.
            </p>
            <div style="margin: 20px 0; text-align: center;">
                <form action="esewa_initiate.php" method="POST">
                    <button type="submit" style="background-color: #60bb46; color: white; border: none; padding: 12px 24px; border-radius: 4px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 10px;">
                        <img src="https://esewa.com.np/common/images/esewa_logo.png" alt="eSewa" style="height: 20px;">
                        Pay with eSewa
                    </button>
                </form>
            </div>
            <button class="change-method-btn" onclick="goBackToOptions()">Change Method</button>
        </div>
    </div>
 
</body>
</html>  

