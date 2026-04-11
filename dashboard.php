<?php
session_start();
include 'connection.php';

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['login']['email'];

// Fetch user bookings
$sql_bookings = "SELECT bookings.*, cars.name AS car_name, cars.image 
                 FROM bookings 
                 JOIN cars ON bookings.carid = cars.carid 
                 WHERE bookings.email = ?
                 ORDER BY bookings.id DESC";
$stmt = $conn->prepare($sql_bookings);
$stmt->bind_param("s", $email);
$stmt->execute();
$bookingsResult = $stmt->get_result();
$bookings = $bookingsResult->fetch_all(MYSQLI_ASSOC);

// Fetch Recommendations
include 'algorithms/recommendation.php';
$recResult = getRecommendations($conn, $email);
$recommendedCars = $recResult['cars'];
$recReason = $recResult['reason'];

// Fetch User Messages
$sql_msgs = "SELECT * FROM messages WHERE email = ? ORDER BY created_at DESC";
$stmt_msg = $conn->prepare($sql_msgs);
$stmt_msg->bind_param("s", $email);
$stmt_msg->execute();
$messagesResult = $stmt_msg->get_result();
$userMessages = $messagesResult->fetch_all(MYSQLI_ASSOC);

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
$totalBookings = count($bookings);
$approvedCount = isset($bookingStats['approved']) ? $bookingStats['approved'] : 0;
$pendingCount = isset($bookingStats['pending']) ? $bookingStats['pending'] : 0;
$declinedCount = isset($bookingStats['declined']) ? $bookingStats['declined'] : 0;
$cancelledCount = isset($bookingStats['cancelled']) ? $bookingStats['cancelled'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Car Rental</title>
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
            --success-color: #10b981;
            --danger-color: #ef4444;
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
            width: 95%; max-width: 1200px; margin: 20px auto; padding: 20px;
            position: relative; z-index: 10;
        }

        h1 {
            text-align: center; margin-bottom: 5px; font-size: 2.5rem; font-weight: 600;
            background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-transform: uppercase; letter-spacing: 2px;
        }

        .welcome-msg {
            text-align: center; margin-bottom: 50px; color: var(--text-muted); font-size: 1.1rem;
        }

        .dashboard-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px; margin-bottom: 40px;
        }

        .material-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px 20px;
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.3s cubic-bezier(0.25,.8,.25,1);
            position: relative; overflow: hidden;
            display: flex; flex-direction: column; align-items: center; text-align: center;
        }

        .material-card:hover { 
            transform: translateY(-5px); 
            border-color: rgba(255,255,255,0.3);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        .card-icon {
            width: 70px; height: 70px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; font-size: 1.8rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
        
        .icon-green i { background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .icon-orange i { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .icon-purple i { background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .icon-blue i { background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .icon-emerald i { background: linear-gradient(135deg, #34d399 0%, #10b981 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .icon-gray i { background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .card-category { color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .card-title { font-size: 1.5rem; font-weight: 600; margin-bottom: 15px; }

        .card-footer {
            margin-top: auto; padding-top: 15px; width: 100%;
            border-top: 1px solid var(--glass-border);
            color: var(--text-muted); font-size: 0.8rem;
            display: flex; justify-content: center; gap: 5px;
        }

        /* Top Buttons */
        .top-btn {
            position: absolute; top: 20px;
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.05); padding: 10px 20px;
            border-radius: 50px; border: 1px solid var(--glass-border);
            text-decoration: none; color: var(--text-main); font-weight: 500;
            transition: all 0.3s; z-index: 20;
        }
        .top-btn:hover { background: rgba(255,255,255,0.1); border-color: var(--accent-color); }
        .top-btn i { color: var(--accent-color); }
        .home-btn { left: 20px; }
        .profile-btn { right: 20px; }

        /* Recommendation Popup - Top Right - Smaller */
        #rec-popup-container { position: fixed; top: 100px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        
        /* Status Notification - Top Left */
        #status-popup-container { position: fixed; top: 100px; left: 20px; z-index: 10000; }

        .rec-popup, .status-popup {
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-left: 3px solid #f59e0b;
            border-radius: 12px; padding: 12px;
            display: flex; gap: 10px; align-items: center; width: 260px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            color: white;
        }
        
        .rec-popup { animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .status-popup { animation: slideInLeft 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

        @keyframes slideInRight { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideInLeft { from { transform: translateX(-120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        .rec-popup.fade-out, .status-popup.fade-out { animation: fadeOutDown 0.5s forwards; }
        @keyframes fadeOutDown { to { transform: translateY(20px); opacity: 0; } }

        .rec-popup img, .status-popup img { width: 60px; height: 45px; object-fit: cover; border-radius: 6px; }
        .rec-content h4, .status-content h4 { font-size: 0.85rem; margin-bottom: 2px; color: white; }
        .rec-content p, .status-content p { font-size: 0.7rem; color: var(--text-muted); margin: 0; }
        .rec-price { color: var(--success-color); font-weight: 600; font-size: 0.75rem; margin-top: 3px; display: block; }
        
        .rec-book-btn {
            background: var(--primary-gradient); color: white; padding: 6px 12px;
            border-radius: 6px; font-size: 0.8rem; text-decoration: none;
            margin-top: 8px; display: inline-block; font-weight: 600;
        }

        .rec-close {
            position: absolute; top: 10px; right: 10px; color: var(--text-muted);
            cursor: pointer; font-size: 18px; transition: 0.2s;
        }
        .rec-close:hover { color: white; }

        .rec-badge {
            position: absolute; top: -10px; left: 15px;
            background: #f59e0b; color: black; font-size: 0.7rem; font-weight: 700;
            padding: 3px 10px; border-radius: 20px; text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .top-btn span { display: none; }
            .dashboard-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <main class="main-content">
        <!-- Home Button -->
        <a href="index.php" class="top-btn home-btn">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>

        <!-- Profile Button -->
        <a href="my_profile.php" class="top-btn profile-btn">
            <i class="fas fa-user-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['login']['full_name']); ?></span>
        </a>

        <h1>Dashboard</h1>
        <p class="welcome-msg">Welcome back! Manage your journey.</p>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            
            <!-- Rent a Car -->
            <a href="store.php" class="material-card">
                <div class="card-icon icon-green">
                    <i class="fas fa-car-side"></i>
                </div>
                <div class="card-content">
                    <p class="card-category">Fleet</p>
                    <h3 class="card-title">Rent a Car</h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> Browse Vehicles
                </div>
            </a>

            <!-- My Bookings -->
            <a href="my_bookings.php" class="material-card">
                <div class="card-icon icon-orange">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="card-content">
                    <p class="card-category">History</p>
                    <h3 class="card-title"><?php echo count($bookings); ?> <small style="font-size: 0.6em; color: var(--text-muted);">Bookings</small></h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> View Status
                </div>
            </a>

            <!-- Analytics -->
            <a href="user_analytics.php" class="material-card">
                <div class="card-icon icon-blue">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="card-content">
                    <p class="card-category">Reports</p>
                    <h3 class="card-title"><?php echo $approvedCount; ?> <small style="font-size: 0.6em; color: var(--text-muted);">Approved</small></h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> View Analytics
                </div>
            </a>

            <!-- My Messages -->
            <a href="user_messages.php" class="material-card">
                <div class="card-icon icon-purple">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="card-content">
                    <p class="card-category">Support</p>
                    <h3 class="card-title"><?php echo count($userMessages); ?> <small style="font-size: 0.6em; color: var(--text-muted);">Messages</small></h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> Check Replies
                </div>
            </a>

            <!-- Logout -->
            <a href="logout.php" class="material-card" style="border-color: rgba(239, 68, 68, 0.3);">
                <div class="card-icon icon-gray">
                    <i class="fas fa-sign-out-alt" style="color: #ef4444;"></i>
                </div>
                <div class="card-content">
                    <p class="card-category">Session</p>
                    <h3 class="card-title">Logout</h3>
                </div>
                <div class="card-footer">
                    <i class="fas fa-power-off"></i> End Session
                </div>
            </a>
        </div>
        
        <!-- Floating Popup Containers -->
        <div id="status-popup-container"></div>
        <div id="rec-popup-container"></div>

        <script>
            // Recommendation Data from PHP
            const recommendations = <?php echo json_encode($recommendedCars); ?>;
            const recReason = <?php echo json_encode($recReason); ?>;
            
            // Booking Notification Data
            <?php
            $latestBooking = !empty($bookings) ? $bookings[0] : null;
            $statusNotification = null;

            if ($latestBooking) {
                if ($latestBooking['status'] === 'approved') {
                    $statusNotification = [
                        'type' => 'success',
                        'title' => 'Ready to Roll! 🚗💨',
                        'message' => 'Your ride ' . htmlspecialchars($latestBooking['car_name']) . ' is confirmed for <br><b style="color: #4ade80">' . date('M d, Y', strtotime($latestBooking['booking_from'])) . '</b>.',
                        'image' => $latestBooking['image'],
                        'color' => '#4ade80' // Green
                    ];
                } elseif ($latestBooking['status'] === 'declined') {
                    $statusNotification = [
                        'type' => 'error',
                        'title' => 'Booking Declined 😔',
                        'message' => 'Sorry, your booking for ' . htmlspecialchars($latestBooking['car_name']) . ' was not accepted. Please try another vehicle.',
                        'image' => $latestBooking['image'],
                        'color' => '#ef4444' // Red
                    ];
                }
            }
            ?>
            const statusNotification = <?php echo json_encode($statusNotification); ?>;
            
            let currentRecIndex = 0;

            function createPopupHTML(data, isStatus = false) {
                const borderLeftColor = isStatus ? data.color : '#f59e0b';
                const buttonHtml = isStatus 
                    ? `<button onclick="closePopup(this)" style="margin-top:5px; background:rgba(255,255,255,0.1); border:1px solid ${borderLeftColor}; color:white; padding:4px 10px; border-radius:4px; cursor:pointer;">Dismiss</button>` 
                    : `<a href="store.php" class="rec-book-btn">Book Now</a>`;
                    
                const badge = isStatus ? `<div class="rec-badge" style="background: ${borderLeftColor}; color:black;">${data.type === 'success' ? 'APPROVED' : 'DECLINED'}</div>` 
                                       : `<div class="rec-badge"><i class="fas fa-bolt"></i> Recommended</div>`;

                return `
                    ${badge}
                    <span class="rec-close" onclick="closePopup(this)">&times;</span>
                    <img src="${data.image}" alt="Car" style="border: 1px solid ${borderLeftColor}">
                    <div class="rec-content status-content">
                        <h4 style="color:${isStatus ? borderLeftColor : 'white'}">${data.title || data.name}</h4>
                        <p>${data.message || (data.model + ' (' + data.year + ')')}</p>
                        ${!isStatus ? `<span class="rec-price">NRS ${data.price} / day</span>` : ''}
                        ${buttonHtml}
                    </div>
                `;
            }

            function showStatusNotification() {
                if (!statusNotification) return;

                const container = document.getElementById('status-popup-container');
                const popup = document.createElement('div');
                popup.className = 'status-popup';
                popup.style.borderLeftColor = statusNotification.color;
                popup.innerHTML = createPopupHTML(statusNotification, true);

                container.appendChild(popup);
                
                // Auto hide status after 10 seconds
                setTimeout(() => {
                    if(popup.parentNode) {
                        popup.classList.add('fade-out');
                        setTimeout(() => popup.remove(), 500);
                    }
                }, 10000);
            }

            function showNextRecommendation() {
                if (currentRecIndex >= recommendations.length) return;

                const car = recommendations[currentRecIndex];
                const container = document.getElementById('rec-popup-container');

                const popup = document.createElement('div');
                popup.className = 'rec-popup';
                popup.innerHTML = createPopupHTML(car, false);

                container.appendChild(popup);
                currentRecIndex++;

                // Auto slide in next one after a delay if more exist
                if (currentRecIndex < recommendations.length) {
                    setTimeout(showNextRecommendation, 8000); 
                }
            }

            function closePopup(btn) {
                const popup = btn.parentElement.closest('div[class*="popup"]'); // Fix selector to match either
                if(popup) {
                    popup.classList.add('fade-out');
                    setTimeout(() => popup.remove(), 500);
                }
            }

            // Sequence Logic
            
            if (statusNotification) {
                // Show Status on Left
                setTimeout(showStatusNotification, 1000);
            }
            
            // Show Recs on Right independently
            if (recommendations.length > 0) {
                setTimeout(showNextRecommendation, 2500);
            }
        </script>

    </main>

</body>
</html>
<?php $conn->close(); ?>