<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Check if there's a pending payment booking
if (!isset($_SESSION['pending_payment_booking_id'])) {
    header("Location: dashboard.php");
    exit();
}

$bookingId = $_SESSION['pending_payment_booking_id'];
$email = $_SESSION['login']['email'];
$username = $_SESSION['login']['full_name'];

// Fetch booking details
$sql = "SELECT b.*, c.name as car_name, c.model as car_model, c.price as car_price, c.image 
        FROM bookings b 
        JOIN cars c ON b.carid = c.carid 
        WHERE b.id = ? AND b.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $bookingId, $email);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

// If booking not found or doesn't belong to user, redirect
if (!$booking) {
    unset($_SESSION['pending_payment_booking_id']);
    unset($_SESSION['pending_payment_amount']);
    header("Location: dashboard.php");
    exit();
}

// Calculate booking details
$totalAmount = $booking['total money'];
$days = 0;
if (!empty($booking['booking_from']) && !empty($booking['booking_to'])) {
    try {
        $date1 = new DateTime($booking['booking_from']);
        $date2 = new DateTime($booking['booking_to']);
        $interval = $date1->diff($date2);
        $days = $interval->days + 1;
    } catch (Exception $e) {
        $days = 1;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment - Car Rental Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

        h1 {
            color: white; font-weight: 600; margin-bottom: 10px; text-align: center;
            font-size: 2.5rem; letter-spacing: 1px;
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .subtitle {
            text-align: center; color: var(--text-muted); margin-bottom: 40px; font-size: 1.1rem;
        }

        /* Booking Summary */
        .booking-summary {
            background: rgba(0, 198, 255, 0.1);
            border: 1px solid rgba(0, 198, 255, 0.2);
            border-radius: 20px; padding: 30px; margin-bottom: 40px;
        }

        .summary-header {
            display: flex; align-items: center; gap: 20px; margin-bottom: 25px;
            padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .car-image {
            width: 120px; height: 80px; border-radius: 12px; object-fit: cover;
            border: 2px solid rgba(0, 198, 255, 0.3);
        }

        .car-info h3 {
            font-size: 1.5rem; color: white; margin-bottom: 5px;
        }

        .car-info p {
            color: var(--text-muted); font-size: 0.9rem;
        }

        .summary-details {
            display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;
        }

        .detail-item {
            display: flex; flex-direction: column; gap: 5px;
        }

        .detail-label {
            font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted);
            letter-spacing: 1px; font-weight: 600;
        }

        .detail-value {
            font-size: 1.1rem; color: white; font-weight: 600;
        }

        .total-amount {
            grid-column: 1 / -1;
            background: rgba(0, 198, 255, 0.1);
            border: 1px solid rgba(0, 198, 255, 0.3);
            border-radius: 12px; padding: 20px;
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 10px;
        }

        .total-amount .label {
            font-size: 1.1rem; color: var(--text-muted); text-transform: uppercase;
            letter-spacing: 1px;
        }

        .total-amount .amount {
            font-size: 2rem; font-weight: 700; color: #00c6ff;
        }

        /* Payment Section */
        .payment-section {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--glass-border);
            border-radius: 20px; padding: 40px;
        }

        .payment-title {
            font-size: 1.3rem; font-weight: 600; color: white; margin-bottom: 10px;
            text-align: center;
        }

        .payment-subtitle {
            text-align: center; color: var(--text-muted); margin-bottom: 30px;
            font-size: 0.95rem;
        }



        #result-message {
            margin-top: 20px;
        }

        .payment-note {
            text-align: center; color: var(--text-muted); margin-top: 25px;
            font-size: 0.85rem; font-style: italic;
        }

        .skip-payment {
            text-align: center; margin-top: 20px;
        }

        .skip-btn {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: var(--text-muted); padding: 12px 24px;
            border-radius: 12px; text-decoration: none;
            transition: all 0.3s ease; font-weight: 500;
        }

        .skip-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white; border-color: var(--accent-color);
        }

        @media (max-width: 768px) {
            .summary-details { grid-template-columns: 1fr; }
            .main-content { padding: 30px 20px; }
        }

    </style>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <h1>Complete Your Payment</h1>
        <p class="subtitle">Secure your booking with online payment</p>

        <!-- Booking Summary -->
        <div class="booking-summary">
            <div class="summary-header">
                <img src="<?php echo htmlspecialchars($booking['image']); ?>" alt="Car" class="car-image">
                <div class="car-info">
                    <h3><?php echo htmlspecialchars($booking['car_name']); ?></h3>
                    <p><?php echo htmlspecialchars($booking['car_model']); ?> • Booking #<?php echo $bookingId; ?></p>
                </div>
            </div>

            <div class="summary-details">
                <div class="detail-item">
                    <span class="detail-label">Customer Name</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['phone']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pickup Date</span>
                    <span class="detail-value"><?php echo date('M d, Y', strtotime($booking['booking_from'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Return Date</span>
                    <span class="detail-value"><?php echo date('M d, Y', strtotime($booking['booking_to'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value"><?php echo $days; ?> Day<?php echo $days > 1 ? 's' : ''; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pickup Time</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['booking_time']); ?></span>
                </div>

                <div class="total-amount">
                    <span class="label">Total Amount</span>
                    <span class="amount">NRS <?php echo number_format($totalAmount, 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="payment-section">
            <h2 class="payment-title">Secure Online Payment</h2>
            <p class="payment-subtitle">
                Complete your payment of <strong style="color: #00c6ff;">NRS <?php echo number_format($totalAmount, 2); ?></strong> to confirm your booking
            </p>
            
            <!-- eSewa Payment Button -->
            <div style="text-align: center; margin-bottom: 20px;">
                <form action="esewa_initiate.php" method="POST">
                    <button type="submit" style="background-color: #60bb46; color: white; border: none; padding: 12px 24px; border-radius: 4px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 10px; margin: 0 auto;">
                        <img src="https://esewa.com.np/common/images/esewa_logo.png" alt="eSewa" style="height: 20px;">
                        Pay with eSewa
                    </button>
                </form>
            </div>

    <script>
        // Any specific JS for eSewa can go here if needed
    </script>
</body>
</html>
