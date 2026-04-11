<?php
session_start();
include 'connection.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all bookings with payment information
$sql = "SELECT b.*, c.name as car_name, c.model as car_model, c.price as car_price, c.image 
        FROM bookings b 
        JOIN cars c ON b.carid = c.carid 
        ORDER BY b.id DESC";
$result = $conn->query($sql);
$bookings = [];
if ($result) {
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Admin Dashboard</title>
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
            width: 95%; max-width: 1400px; margin: 60px auto;
            padding: 40px; background: var(--glass-bg);
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
            font-size: 2.5rem; letter-spacing: 1px;
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .payments-table {
            width: 100%; border-collapse: collapse; margin-top: 30px;
        }

        .payments-table thead {
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 2px solid var(--glass-border);
        }

        .payments-table th {
            padding: 15px; text-align: left; font-weight: 600;
            color: var(--accent-color); text-transform: uppercase;
            font-size: 0.85rem; letter-spacing: 1px;
        }

        .payments-table td {
            padding: 20px 15px; border-bottom: 1px solid var(--glass-border);
            color: var(--text-main);
        }

        .payments-table tbody tr {
            transition: all 0.3s ease;
        }

        .payments-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .car-info {
            display: flex; align-items: center; gap: 15px;
        }

        .car-thumb {
            width: 60px; height: 45px; object-fit: cover;
            border-radius: 8px; border: 1px solid var(--glass-border);
        }

        .car-details h4 {
            margin: 0 0 5px; font-size: 0.95rem; color: white;
        }

        .car-details p {
            margin: 0; font-size: 0.8rem; color: var(--text-muted);
        }

        .payment-badge {
            padding: 6px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block;
        }

        .payment-cash {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .payment-online {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge {
            padding: 6px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-pending {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-approved {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-declined {
            background: rgba(107, 114, 128, 0.2);
            color: #9ca3af; border: 1px solid rgba(107, 114, 128, 0.3);
        }

        .amount-cell {
            font-weight: 700; font-size: 1.1rem;
            color: #10b981;
        }

        .no-data {
            text-align: center; padding: 60px 20px;
            color: var(--text-muted); font-size: 1.1rem;
        }

        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px; margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 16px; padding: 25px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2rem; margin-bottom: 10px;
            background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .stat-card p {
            color: var(--text-muted); font-size: 0.9rem;
            text-transform: uppercase; letter-spacing: 1px;
        }

        /* Payment Status Indicators */
        .payment-status-received {
            color: #10b981;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .payment-status-progress {
            color: #f59e0b;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .payment-status-received i,
        .payment-status-progress i {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <a href="admin_dash.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        
        <h1>Payment Transactions</h1>

        <?php
        // Calculate statistics
        $totalBookings = count($bookings);
        $cashPayments = 0;
        $onlinePayments = 0;
        $totalRevenue = 0;

        foreach ($bookings as $booking) {
            if (isset($booking['payment_method'])) {
                if ($booking['payment_method'] === 'cash') {
                    $cashPayments++;
                } elseif ($booking['payment_method'] === 'online') {
                    $onlinePayments++;
                }
            }
            
            // Calculate revenue
            if (isset($booking['booking_from']) && isset($booking['booking_to'])) {
                try {
                    $date1 = new DateTime($booking['booking_from']);
                    $date2 = new DateTime($booking['booking_to']);
                    $interval = $date1->diff($date2);
                    $days = $interval->days + 1;
                    $totalRevenue += $days * $booking['car_price'];
                } catch (Exception $e) {
                    // Skip if date calculation fails
                }
            }
        }
        ?>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $totalBookings; ?></h3>
                <p>Total Bookings</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $cashPayments; ?></h3>
                <p>Cash Payments</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $onlinePayments; ?></h3>
                <p>Online Payments</p>
            </div>
            <div class="stat-card">
                <h3>NRS <?php echo number_format($totalRevenue, 2); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>

        <?php if (!empty($bookings)): ?>
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Vehicle</th>
                    <th>Customer</th>
                    <th>Duration</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): 
                    // Calculate amount
                    $amount = 0;
                    $days = 0;
                    if (isset($booking['booking_from']) && isset($booking['booking_to'])) {
                        try {
                            $date1 = new DateTime($booking['booking_from']);
                            $date2 = new DateTime($booking['booking_to']);
                            $interval = $date1->diff($date2);
                            $days = $interval->days + 1;
                            $amount = $days * $booking['car_price'];
                        } catch (Exception $e) {
                            $amount = 0;
                        }
                    }
                ?>
                <tr>
                    <td>#<?php echo $booking['id']; ?></td>
                    <td>
                        <div class="car-info">
                            <img src="<?php echo htmlspecialchars($booking['image']); ?>" alt="Car" class="car-thumb">
                            <div class="car-details">
                                <h4><?php echo htmlspecialchars($booking['car_name']); ?></h4>
                                <p><?php echo htmlspecialchars($booking['car_model']); ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong><?php echo htmlspecialchars($booking['name']); ?></strong><br>
                            <small style="color: var(--text-muted);"><?php echo htmlspecialchars($booking['email']); ?></small>
                        </div>
                    </td>
                    <td>
                        <?php echo $days; ?> day(s)<br>
                        <small style="color: var(--text-muted);">
                            <?php echo date('M d', strtotime($booking['booking_from'])); ?> - 
                            <?php echo date('M d, Y', strtotime($booking['booking_to'])); ?>
                        </small>
                    </td>
                    <td class="amount-cell">NRS <?php echo number_format($amount, 2); ?></td>
                    <td>
                        <?php if (isset($booking['payment_method'])): ?>
                            <span class="payment-badge payment-<?php echo $booking['payment_method']; ?>">
                                <?php echo $booking['payment_method'] === 'cash' ? 'Cash on Delivery' : 'Online Payment'; ?>
                            </span>
                            <br>
                            <?php 
                            // Determine payment status
                            $paymentStatus = 'In Progress';
                            $paymentStatusClass = 'payment-status-progress';
                            
                            if ($booking['payment_method'] === 'online' && $booking['status'] === 'approved') {
                                $paymentStatus = 'Received';
                                $paymentStatusClass = 'payment-status-received';
                            } elseif ($booking['payment_method'] === 'cash' && $booking['status'] === 'approved') {
                                $paymentStatus = 'Received';
                                $paymentStatusClass = 'payment-status-received';
                            }
                            ?>
                            <small class="<?php echo $paymentStatusClass; ?>" style="margin-top: 5px; display: inline-block;">
                                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 5px;"></i>
                                <?php echo $paymentStatus; ?>
                            </small>
                            <?php if (!empty($booking['transaction_id'])): ?>
                                <br>
                                <small style="color: var(--text-muted); font-family: monospace; font-size: 0.7rem;">
                                    TID: <?php echo htmlspecialchars($booking['transaction_id']); ?>
                                </small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">Not specified</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo $booking['status']; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.3;"></i>
            <p>No payment transactions found.</p>
        </div>
        <?php endif; ?>
    </div>
 
</body>
</html>
<?php $conn->close(); ?>
