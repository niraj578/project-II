<?php
session_start();
include 'connection.php';

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['login']['email'];
$userName = $_SESSION['login']['full_name'];

// Fetch Booking Statistics
$sql_stats = "SELECT status, COUNT(*) as count FROM bookings WHERE email = ? GROUP BY status";
$stmt_stats = $conn->prepare($sql_stats);
$stmt_stats->bind_param("s", $email);
$stmt_stats->execute();
$statsResult = $stmt_stats->get_result();
$bookingStats = [];
while ($row = $statsResult->fetch_assoc()) {
    $bookingStats[$row['status']] = $row['count'];
}

$approvedCount = isset($bookingStats['approved']) ? $bookingStats['approved'] : 0;
$pendingCount = isset($bookingStats['pending']) ? $bookingStats['pending'] : 0;
$declinedCount = isset($bookingStats['declined']) ? $bookingStats['declined'] : 0;
$cancelledCount = isset($bookingStats['cancelled']) ? $bookingStats['cancelled'] : 0;
$totalBookings = $approvedCount + $pendingCount + $declinedCount + $cancelledCount;

// Fetch Recent Bookings
$sql_recent = "SELECT bookings.*, cars.name AS car_name, cars.model, cars.image 
               FROM bookings 
               JOIN cars ON bookings.carid = cars.carid 
               WHERE bookings.email = ?
               ORDER BY bookings.created_at DESC
               LIMIT 10";
$stmt_recent = $conn->prepare($sql_recent);
$stmt_recent->bind_param("s", $email);
$stmt_recent->execute();
$recentResult = $stmt_recent->get_result();
$recentBookings = $recentResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Car Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --accent-color: #00c6ff;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --gray-color: #64748b;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030303;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background Effects */
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
            width: 95%; max-width: 1400px; margin: 20px auto; padding: 20px;
            position: relative; z-index: 10;
        }

        /* Header */
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 40px; flex-wrap: wrap; gap: 20px;
        }

        h1 {
            font-size: 2.5rem; font-weight: 700;
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-transform: uppercase; letter-spacing: 2px;
        }

        .back-btn {
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.05); padding: 12px 24px;
            border-radius: 50px; border: 1px solid var(--glass-border);
            text-decoration: none; color: var(--text-main); font-weight: 500;
            transition: all 0.3s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.1); border-color: var(--accent-color); }
        .back-btn i { color: var(--accent-color); }

        /* Stats Grid */
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px; margin-bottom: 40px;
        }

        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px; padding: 24px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255,255,255,0.3);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        .stat-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px;
        }

        .stat-label {
            color: var(--text-muted); font-size: 0.9rem;
            text-transform: uppercase; letter-spacing: 1px;
        }

        .stat-icon {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 2.5rem; font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-description {
            color: var(--text-muted); font-size: 0.85rem;
        }

        /* Chart Section */
        .chart-section {
            background: var(--glass-bg);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px; padding: 30px;
            margin-bottom: 40px;
        }

        .chart-header {
            margin-bottom: 30px;
        }

        .chart-header h2 {
            font-size: 1.5rem; font-weight: 600;
            margin-bottom: 8px;
        }

        .chart-header p {
            color: var(--text-muted); font-size: 0.9rem;
        }

        .chart-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Table Section */
        .table-section {
            background: var(--glass-bg);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px; padding: 30px;
            overflow-x: auto;
        }

        .table-header {
            margin-bottom: 20px;
        }

        .table-header h2 {
            font-size: 1.5rem; font-weight: 600;
        }

        table {
            width: 100%; border-collapse: collapse;
        }

        thead {
            background: rgba(255,255,255,0.05);
        }

        th {
            padding: 12px; text-align: left;
            color: var(--text-muted); font-weight: 600;
            font-size: 0.85rem; text-transform: uppercase;
            letter-spacing: 1px;
        }

        td {
            padding: 16px 12px;
            border-bottom: 1px solid var(--glass-border);
        }

        tbody tr:hover {
            background: rgba(255,255,255,0.03);
        }

        .car-info {
            display: flex; align-items: center; gap: 12px;
        }

        .car-img {
            width: 60px; height: 45px;
            object-fit: cover; border-radius: 6px;
            border: 1px solid var(--glass-border);
        }

        .car-details h4 {
            font-size: 0.95rem; margin-bottom: 4px;
        }

        .car-details p {
            font-size: 0.8rem; color: var(--text-muted);
        }

        .status-badge {
            padding: 6px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600;
            text-transform: uppercase; display: inline-block;
        }

        .status-approved { background: rgba(16, 185, 129, 0.2); color: var(--success-color); }
        .status-pending { background: rgba(245, 158, 11, 0.2); color: var(--warning-color); }
        .status-declined { background: rgba(239, 68, 68, 0.2); color: var(--danger-color); }
        .status-cancelled { background: rgba(100, 116, 139, 0.2); color: var(--gray-color); }

        .no-data {
            text-align: center; padding: 60px 20px;
            color: var(--text-muted);
        }

        .no-data i {
            font-size: 4rem; margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            h1 { font-size: 2rem; }
            .stats-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; }
            table { font-size: 0.85rem; }
            .car-img { width: 50px; height: 38px; }
        }
    </style>
</head>
<body>

    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-chart-line"></i> Analytics</h1>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Total Bookings</span>
                    <div class="stat-icon" style="background: rgba(0, 198, 255, 0.2);">
                        <i class="fas fa-calendar-alt" style="color: var(--accent-color);"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: var(--accent-color);"><?php echo $totalBookings; ?></div>
                <div class="stat-description">All time bookings</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Approved</span>
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: var(--success-color);"><?php echo $approvedCount; ?></div>
                <div class="stat-description">Confirmed bookings</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Pending</span>
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2);">
                        <i class="fas fa-clock" style="color: var(--warning-color);"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: var(--warning-color);"><?php echo $pendingCount; ?></div>
                <div class="stat-description">Awaiting approval</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Declined/Cancelled</span>
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.2);">
                        <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: var(--danger-color);"><?php echo ($declinedCount + $cancelledCount); ?></div>
                <div class="stat-description">Not completed</div>
            </div>
        </div>

        <!-- Chart Section -->
        <?php if ($totalBookings > 0): ?>
        <div class="chart-section">
            <div class="chart-header">
                <h2>Booking Status Distribution</h2>
                <p>Visual breakdown of your booking statuses</p>
            </div>
            <div class="chart-container">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Bookings Table -->
        <div class="table-section">
            <div class="table-header">
                <h2>Recent Bookings</h2>
            </div>

            <?php if (count($recentBookings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Booking Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBookings as $booking): ?>
                    <tr>
                        <td>
                            <div class="car-info">
                                <img src="<?php echo htmlspecialchars($booking['image']); ?>" alt="Car" class="car-img">
                                <div class="car-details">
                                    <h4><?php echo htmlspecialchars($booking['car_name']); ?></h4>
                                    <p><?php echo htmlspecialchars($booking['model']); ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($booking['booking_from'])); ?></td>
                        <td>
                            <?php 
                            $from = new DateTime($booking['booking_from']);
                            $to = new DateTime($booking['booking_to']);
                            $days = $from->diff($to)->days + 1;
                            echo $days . ' day' . ($days > 1 ? 's' : '');
                            ?>
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
                <i class="fas fa-inbox"></i>
                <h3>No Bookings Yet</h3>
                <p>Start by renting a car from our fleet!</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php if ($totalBookings > 0): ?>
    <script>
        const ctx = document.getElementById('bookingChart').getContext('2d');
        
        const data = {
            labels: ['Approved', 'Pending', 'Declined', 'Cancelled'],
            datasets: [{
                data: [
                    <?php echo $approvedCount; ?>,
                    <?php echo $pendingCount; ?>,
                    <?php echo $declinedCount; ?>,
                    <?php echo $cancelledCount; ?>
                ],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',  // Green
                    'rgba(245, 158, 11, 0.8)',  // Yellow
                    'rgba(239, 68, 68, 0.8)',   // Red
                    'rgba(100, 116, 139, 0.8)'  // Gray
                ],
                borderColor: [
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(100, 116, 139, 1)'
                ],
                borderWidth: 2
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 14,
                                family: 'Outfit'
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    </script>
    <?php endif; ?>

</body>
</html>
<?php $conn->close(); ?>
