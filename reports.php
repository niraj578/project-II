<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php');
    exit();
}

include 'connection.php';

// Fetch data for reports

// 1. Existing Reports
$availableCarsQuery = "SELECT COUNT(*) as count FROM cars";
$bookedCarsQuery = "SELECT COUNT(*) as count FROM bookings";
$manageBookingsQuery = "SELECT 
                            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                            SUM(CASE WHEN status = 'declined' THEN 1 ELSE 0 END) as declined,
                            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
                        FROM bookings";

$availableCarsResult = $conn->query($availableCarsQuery);
$bookedCarsResult = $conn->query($bookedCarsQuery);
$manageBookingsResult = $conn->query($manageBookingsQuery);

$availableCarsCount = $availableCarsResult->fetch_assoc()['count'];
$bookedCarsCount = $bookedCarsResult->fetch_assoc()['count'];
$manageBookingsData = $manageBookingsResult->fetch_assoc();
$approvedCount = $manageBookingsData['approved'];
$declinedCount = $manageBookingsData['declined'];
$pendingCount = $manageBookingsData['pending'];

// 2. New Report: Car Wise Booking (Approved Only)
$carWiseQuery = "SELECT c.name, COUNT(*) as count 
                 FROM bookings b 
                 JOIN cars c ON b.carid = c.carid 
                 WHERE b.status = 'approved' 
                 GROUP BY c.name 
                 ORDER BY count DESC 
                 LIMIT 5";
$carWiseResult = $conn->query($carWiseQuery);
$carLabels = [];
$carData = [];
while($row = $carWiseResult->fetch_assoc()) {
    $carLabels[] = $row['name'];
    $carData[] = $row['count'];
}

// 3. New Report: Booking Date Trends (Daily - Last 30 Days)
$dateTrendQuery = "SELECT DATE(created_at) as date, COUNT(*) as count 
                   FROM bookings 
                   WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                   GROUP BY date 
                   ORDER BY date ASC";
$dateTrendResult = $conn->query($dateTrendQuery);
$dateLabels = [];
$dateData = [];
while($row = $dateTrendResult->fetch_assoc()) {
    $dateLabels[] = date('M d', strtotime($row['date']));
    $dateData[] = $row['count'];
}

// 4. Booking Date Summary (Daily, Weekly, Monthly)
$summaryQuery = "SELECT 
    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_count,
    SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) as weekly_count,
    SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as monthly_count
FROM bookings";
$summaryResult = $conn->query($summaryQuery);
$summaryData = $summaryResult->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ff4b2b 0%, #ff416c 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --accent-color: #ff4b2b;
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
            width: 95%; max-width: 1400px; margin: 60px auto 40px;
            padding: 40px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10;
        }

        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px; }
        h1 {
            font-size: 32px; font-weight: 600; background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .back-btn {
            display: inline-flex; align-items: center; gap: 10px; color: var(--text-muted);
            text-decoration: none; transition: all 0.3s ease; font-weight: 500;
        }
        .back-btn:hover { color: var(--text-main); transform: translateX(-5px); }

        /* Summary Badges */
        .summary-row {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;
        }
        .summary-card {
            background: rgba(255,255,255,0.08); padding: 20px; border-radius: 16px; border: 1px solid var(--glass-border);
            text-align: center;
        }
        .summary-count { font-size: 2rem; font-weight: 700; color: var(--accent-color); }
        .summary-label { color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }

        /* Chart Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s ease;
        }
        
        .chart-card.full-width { grid-column: 1 / -1; }

        .chart-card:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600; margin-bottom: 20px; color: var(--text-main);
            display: flex; align-items: center; gap: 10px;
        }

        .chart-title i {
            background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            font-size: 24px;
        }

        canvas { max-height: 350px; width: 100%; }
        
        h2.section-heading { margin: 40px 0 20px; font-size: 1.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px; color: var(--text-muted); }

    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <header>
            <h1>Analytics & Reports</h1>
            <a href="admin_dash.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </header>

        <!-- Summary Statistics -->
        <h2 class="section-heading">Detailed Statistics</h2>
        <div class="summary-row">
            <div class="summary-card">
                <div class="summary-count"><?php echo $summaryData['today_count']; ?></div>
                <div class="summary-label">Bookings Today</div>
            </div>
            <div class="summary-card">
                <div class="summary-count"><?php echo $summaryData['weekly_count']; ?></div>
                <div class="summary-label">This Week</div>
            </div>
            <div class="summary-card">
                <div class="summary-count"><?php echo $summaryData['monthly_count']; ?></div>
                <div class="summary-label">This Month</div>
            </div>
            <div class="summary-card">
                <div class="summary-count"><?php echo $bookedCarsCount; ?></div>
                <div class="summary-label">Total Bookings</div>
            </div>
        </div>

        <div class="charts-grid">
            <!-- 1. Car Wise Booking Report (Approved) -->
            <div class="chart-card full-width">
                <div class="chart-title">
                    <i class="fas fa-star"></i>
                    Car Wise Booking Report (Approved Only)
                </div>
                <!-- Canvas for Car Chart -->
                <div style="height: 300px;">
                    <canvas id="carWiseChart"></canvas>
                </div>
            </div>

            <!-- 2. Booking Date Reports -->
            <div class="chart-card full-width">
                <div class="chart-title">
                    <i class="fas fa-calendar-alt"></i>
                    Report on the Basis of Booking Date (Daily Trend)
                </div>
                <div style="height: 300px;">
                    <canvas id="dateTrendChart"></canvas>
                </div>
            </div>

            <!-- Existing Overview Charts -->
             <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-car-side"></i>
                    Fleet Overview
                </div>
                <canvas id="availableCarsChart"></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-tasks"></i>
                    Booking Status
                </div>
                <canvas id="manageBookingsChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Chart.js default configuration for dark theme
        Chart.defaults.color = '#ccc';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';
        Chart.defaults.font.family = 'Outfit';

        // 1. Car Wise Booking Chart (Approved)
        const carWiseCtx = document.getElementById('carWiseChart').getContext('2d');
        new Chart(carWiseCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($carLabels); ?>,
                datasets: [{
                    label: 'Approved Bookings',
                    data: <?php echo json_encode($carData); ?>,
                    backgroundColor: 'rgba(76, 175, 80, 0.6)',
                    borderColor: '#4CAF50',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Horizontal bar chart
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { grid: { display: false } }
                }
            }
        });

        // 2. Booking Date Trend Chart
        const dateTrendCtx = document.getElementById('dateTrendChart').getContext('2d');
        new Chart(dateTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dateLabels); ?>,
                datasets: [{
                    label: 'Daily Bookings',
                    data: <?php echo json_encode($dateData); ?>,
                    borderColor: '#ff4b2b',
                    backgroundColor: 'rgba(255, 75, 43, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ff416c'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });

        // 3. Fleet Overview Chart (Existing)
        const availableCarsCtx = document.getElementById('availableCarsChart').getContext('2d');
        new Chart(availableCarsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Total Fleet'],
                datasets: [{
                    data: [<?php echo $availableCarsCount; ?>],
                    backgroundColor: ['rgba(33, 150, 243, 0.7)'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, cutout: '70%' }
        });

        // 4. Booking Status Chart (Existing)
        const manageBookingsCtx = document.getElementById('manageBookingsChart').getContext('2d');
        new Chart(manageBookingsCtx, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Declined', 'Pending'],
                datasets: [{
                    data: [<?php echo $approvedCount; ?>, <?php echo $declinedCount; ?>, <?php echo $pendingCount; ?>],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>
</html>