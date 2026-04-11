<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// // Database connection
// $servername = "localhost"; // Your database server
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
// Include Selection Sort Algorithm (REMOVED)
// include 'algorithms/sorting.php';

// Initialize cars array
$cars = [];
$searchQuery = "";

// Handle search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])) {
    $searchQuery = $_POST['search_query'];
    $sql = "SELECT carid, name, model, year, price, image, image2, image3, type FROM cars WHERE name LIKE ? OR model LIKE ? OR year LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    $stmt->close();
} else {
    // Fetch available cars from the database
    $sql = "SELECT carid, name, model, year, price, image, image2, image3, type FROM cars";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

// Apply Manual Selection Sort (REMOVED)
// $cars = selectionSortCars($cars);

// Assuming you have a connection to the database
$isLoggedIn = isset($_SESSION['login']);

// Legacy/broken booking insertion code has been removed.

// Get the username from the session
$username = $_SESSION['login']['full_name']; // Assuming full_name is stored in session
$userEmail = $_SESSION['login']['email']; // Get email from session
$userPhone = $_SESSION['login']['phone_number']; // Get phone from session

// Fetch Recommendations
include 'algorithms/recommendation.php';
$recResult = getRecommendations($conn, $userEmail);
$recommendedCars = $recResult['cars'];
$recReason = $recResult['reason'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service - Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            --accent-gradient: linear-gradient(135deg, #ff4b2b 0%, #ff416c 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --card-hover: rgba(255, 255, 255, 0.1);
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

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 10;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative; 
            top: 0; left: 0; 
            margin-bottom: 30px;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(-5px);
        }

        h1 {
            text-align: center;
            margin: 20px 0 10px;
            font-size: 3.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        h2 {
            text-align: center;
            color: var(--text-muted);
            font-weight: 300;
            font-size: 1.2rem;
            margin-bottom: 60px;
            letter-spacing: 1px;
        }

        /* Boxy Grid System */
        .car-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 40px;
            padding: 20px 0;
        }

        .car-item {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            opacity: 0; transform: translateY(30px);
        }

        .car-item.reveal {
            opacity: 1; transform: translateY(0);
        }

        .car-item:hover {
            transform: translateY(-10px) scale(1.02);
            background: var(--card-hover);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border-color: rgba(255,255,255,0.2);
        }

        .image-container {
            width: 100%;
            height: 240px;
            overflow: hidden;
            border-bottom: 1px solid var(--glass-border);
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .car-item:hover .image-container img {
            transform: scale(1.1);
        }

        .car-info {
            padding: 30px;
            flex-grow: 1;
            text-align: left;
        }

        .car-info h3 {
            margin: 0 0 15px;
            font-size: 1.5rem;
            color: var(--text-main);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .car-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .car-details span i { color: #00c6ff; margin-right: 8px; }

        .price-tag {
            font-size: 1.4rem;
            font-weight: 700;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 25px;
            display: block;
        }
        
        .price-tag small { color: var(--text-muted); -webkit-text-fill-color: initial; font-weight: 400; font-size: 0.8em; }

        .book-btn {
            width: 100%;
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 16px;
            cursor: pointer;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .book-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 114, 255, 0.4);
        }

        /* Modal Redesign - Dark Glass */
        .modal {
            display: none; position: fixed; z-index: 10000; left: 0; top: 0;
            width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(10px); align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease;
        }

        .modal.show { display: flex; opacity: 1; }

        .modal-content {
            background: #111;
            background: linear-gradient(145deg, #111, #1a1a1a);
            padding: 0;
            border-radius: 20px;
            width: 90%; max-width: 1000px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid var(--glass-border);
            overflow: hidden;
        }

        .modal.show .modal-content { transform: scale(1) translateY(0); }

        /* Detail Modal Specifics */
        .details-hero {
            height: 450px;
            position: relative;
        }
        
        .details-body {
            padding: 40px;
            background: transparent;
        }

        .specs-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 30px;
        }

        .spec-item {
            display: flex; align-items: center; gap: 15px; color: var(--text-muted);
        }

        .spec-item i { font-size: 1.5rem; color: #00c6ff; }
        
        .spec-label { display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .spec-item strong { color: white; font-size: 1.1rem; }

        .details-footer {
            border-top: 1px solid var(--glass-border);
            padding-top: 30px;
            margin-top: 20px;
        }

        /* Booking Form Styles */
        .booking-modal-content { width: 600px !important; background: #111 !important; max-height: 90vh; overflow-y: auto; }
        
        .booking-header {
            background: var(--primary-gradient);
            padding: 20px;
            text-align: center;
        }
        
        .booking-header h2 {
            color: white; font-size: 1.5rem; margin: 0;
        }

        .booking-form-container { padding: 30px; }

        .form-row {
            display: flex; gap: 20px; margin-bottom: 15px;
        }

        .form-group {
            flex: 1; display: flex; flex-direction: column; margin-bottom: 15px;
        }

        .form-group label {
            color: var(--text-muted); font-size: 0.8rem; letter-spacing: 0.5px; margin-bottom: 8px; font-weight: 500;
        }

        .form-group input {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 12px; border-radius: 8px;
            width: 100%; font-family: 'Outfit', sans-serif;
        }

        .form-group input:focus {
            border-color: #00c6ff; outline: none; box-shadow: 0 0 0 3px rgba(0, 198, 255, 0.1);
        }
        
        .form-group input[readonly] { background: rgba(0,0,0,0.2); color: #888; cursor: not-allowed; border-color: transparent; }

        .confirm-btn {
            background: var(--accent-gradient);
            color: white; border: none; font-weight: 600; cursor: pointer;
            padding: 15px; font-size: 1rem; border-radius: 10px; margin-top: 10px; width: 100%;
            transition: all 0.3s;
        }

        .confirm-btn:hover {
            box-shadow: 0 10px 20px rgba(255, 65, 108, 0.3);
            transform: translateY(-2px) scale(1.01);
        }

        /* Payment Method Selection */
        .payment-section {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--glass-border);
        }

        .payment-section-title {
            color: var(--text-muted);
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .payment-methods-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-method-card {
            background: rgba(255, 255, 255, 0.03);
            border: 2px solid var(--glass-border);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .payment-method-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .payment-method-card.selected {
            background: rgba(0, 198, 255, 0.1);
            border-color: #00c6ff;
            box-shadow: 0 0 20px rgba(0, 198, 255, 0.2);
        }

        .payment-method-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .payment-method-card.selected i {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .payment-method-card h4 {
            margin: 0 0 5px;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .payment-method-card p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin: 0;
        }

        .payment-method-card .check-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #10b981;
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .payment-method-card.selected .check-icon {
            display: flex;
        }

        /* Terms and Conditions */
        .terms-section {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--glass-border);
        }

        .terms-checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .terms-checkbox-wrapper input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #00c6ff;
        }

        .terms-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .terms-link {
            color: #00c6ff;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.3s;
        }

        .terms-link:hover {
            color: #0072ff;
        }

        /* Terms Modal */
        .terms-modal {
            display: none;
            position: fixed;
            z-index: 10001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.85);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .terms-modal.show {
            display: flex;
            opacity: 1;
        }

        .terms-modal-content {
            background: #111;
            background: linear-gradient(145deg, #111, #1a1a1a);
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid var(--glass-border);
        }

        .terms-modal.show .terms-modal-content {
            transform: scale(1) translateY(0);
        }

        .terms-modal-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--glass-border);
        }

        .terms-modal-header h2 {
            margin: 0;
            font-size: 1.8rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .terms-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .terms-list li {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: var(--text-muted);
            line-height: 1.6;
            display: flex;
            gap: 15px;
        }

        .terms-list li:last-child {
            border-bottom: none;
        }

        .terms-list li .term-number {
            flex-shrink: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
        }

        .terms-list li .term-text {
            flex: 1;
            padding-top: 4px;
        }

        .terms-close-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 25px;
            width: 100%;
            transition: all 0.3s;
        }

        .terms-close-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 114, 255, 0.4);
        }


        .rec-close:hover { color: white; }

        /* Recommendation Popup - Top Right - Smaller */
        #rec-popup-container { position: fixed; top: 100px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        
        .rec-popup {
            display: flex; gap: 10px; align-items: center; width: 260px;
            padding: 12px; border-radius: 12px;
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border); border-left: 3px solid #f59e0b;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            color: white;
        }

        @keyframes slideInRight { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .rec-popup.fade-out { animation: fadeOutDown 0.5s forwards; }
        @keyframes fadeOutDown { to { transform: translateY(20px); opacity: 0; } }

        .rec-popup img { width: 60px; height: 45px; object-fit: cover; border-radius: 6px; }
        .rec-badge {
            position: absolute; top: -8px; left: 10px;
            background: #f59e0b; color: black; font-size: 0.6rem; font-weight: 700;
            padding: 2px 8px; border-radius: 20px; text-transform: uppercase;
        }
        
        .rec-book-btn-mini {
            background: var(--primary-gradient); color: white; padding: 4px 10px;
            border-radius: 5px; font-size: 0.7rem; text-decoration: none;
            margin-top: 5px; display: inline-block; font-weight: 600;
        }

        /* Highlight Animation */
        .highlight-card {
            border: 2px solid #ffeb3b !important;
            box-shadow: 0 0 20px #ffeb3b80, 0 0 40px #ffeb3b40 !important;
            transform: scale(1.05) !important;
            z-index: 100;
            animation: pulseYellow 1.5s infinite;
        }

        @keyframes pulseYellow {
            0% { box-shadow: 0 0 0 0 rgba(255, 235, 59, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(255, 235, 59, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 235, 59, 0); }
        }

    </style>
</head>
<body>
    <!-- Background Logic -->
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="container">
        <a href="dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h1>Premium Car Fleet</h1>
        <h2>Select the perfect ride for your journey</h2>
        <div class="car-grid">
            <?php if (!empty($cars)): ?>
                <?php foreach ($cars as $car): ?>
                    <div class="car-item" 
                         data-carname="<?php echo htmlspecialchars($car['name']); ?>"
                         data-carid="<?php echo $car['carid']; ?>"
                         data-carmodel="<?php echo htmlspecialchars($car['model']); ?>"
                         data-cartype="<?php echo htmlspecialchars($car['type'] ?? 'Sedan'); ?>"
                         data-caryear="<?php echo htmlspecialchars($car['year']); ?>"
                         data-carprice="<?php echo htmlspecialchars($car['price']); ?>"
                         data-carimage="<?php echo htmlspecialchars($car['image']); ?>"
                         data-carimage2="<?php echo htmlspecialchars($car['image2'] ?: $car['image']); ?>"
                         data-carimage3="<?php echo htmlspecialchars($car['image3'] ?: $car['image']); ?>"
                         onclick="animateAndOpen(this)">
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
                        </div>
                        <div class="car-info">
                            <h3><?php echo htmlspecialchars($car['name']); ?></h3>
                            <div class="car-details">
                                <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($car['model']); ?></span>
                                <span><i class="fas fa-car-side"></i> <?php echo htmlspecialchars($car['type'] ?? 'Sedan'); ?></span>
                                <span><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($car['year']); ?></span>
                                <span><i class="fas fa-check-circle"></i> Available</span>
                            </div>
                            <span class="price-tag">NRS <?php echo number_format($car['price'], 2); ?> <small>/ day</small></span>
                            <button class="book-btn" onclick="event.stopPropagation(); openModal('<?php echo $car['carid']; ?>', '<?php echo $car['price']; ?>')">
                                Book This Car
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align:center; padding: 50px;">No cars available matching your criteria.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content details-modal-content">
            <span class="close" style="z-index: 11; color: white; background: rgba(0,0,0,0.5); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; top: 15px; right: 15px;">&times;</span>
            <div id="details-content">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content booking-modal-content">
            <span class="close" style="z-index: 11; color: white; right: 20px; top: 20px;">&times;</span>
            <div class="booking-header">
                <h2>Secure Your Ride</h2>
            </div>
            
            <div class="booking-form-container">
                <form id="bookingForm" action="add_booking.php" method="POST">
                    <input type="hidden" name="carid" id="carid" value="">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" value="<?php echo htmlspecialchars($username); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($userPhone); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Pickup Location</label>
                            <input type="text" name="pickup_location" placeholder="Street, City" required>
                        </div>
                        <div class="form-group">
                            <label>Drop-off Location</label>
                            <input type="text" name="dropoff_location" placeholder="Street, City" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Booking From</label>
                            <input type="date" name="booking_from" id="booking_from" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Booking To</label>
                            <input type="date" name="booking_to" id="booking_to" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Preferred Pickup Time</label>
                        <input type="time" name="booking_time" required>
                    </div>

                    <div class="form-group">
                        <label>Total Amount (NRS)</label>
                        <input type="text" name="amount" id="amount" readonly style="font-weight: bold; color: var(--accent);">
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="payment-section">
                        <label class="payment-section-title">Select Payment Method</label>
                        <input type="hidden" name="payment_method" id="payment_method" value="" required>
                        
                        <div class="payment-methods-grid">
                            <div class="payment-method-card" onclick="selectPaymentMethod('cash')">
                                <div class="check-icon"><i class="fas fa-check"></i></div>
                                <i class="fas fa-money-bill-wave"></i>
                                <h4>Cash on Delivery</h4>
                                <p>Pay when you receive</p>
                            </div>
                            <div class="payment-method-card" onclick="selectPaymentMethod('online')">
                                <div class="check-icon"><i class="fas fa-check"></i></div>
                                <i class="fas fa-wallet"></i>
                                <h4>eSewa / Online</h4>
                                <p>Pay securely via eSewa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="terms-section">
                        <div class="terms-checkbox-wrapper">
                            <input type="checkbox" id="terms_checkbox" name="terms_accepted" required>
                            <label for="terms_checkbox" class="terms-label">
                                I accept the <span class="terms-link" onclick="openTermsModal()">Terms and Conditions</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="confirm-btn">Confirm Reservation</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" class="terms-modal">
        <div class="terms-modal-content">
            <div class="terms-modal-header">
                <h2>Terms and Conditions</h2>
            </div>
            <ul class="terms-list">
                <li>
                    <div class="term-number">1</div>
                    <div class="term-text">Driver must be at least 21 years old with a valid driver's license for minimum 1 year.</div>
                </li>
                <li>
                    <div class="term-number">2</div>
                    <div class="term-text">In case of accident caused by the driver, full repair charges will be borne by the renter.</div>
                </li>
                <li>
                    <div class="term-number">3</div>
                    <div class="term-text">Late return charges: NRS 500 per hour will be applied for returns beyond the agreed time.</div>
                </li>
                <li>
                    <div class="term-number">4</div>
                    <div class="term-text">Security deposit of NRS 10,000 is required and will be refunded after vehicle inspection.</div>
                </li>
                <li>
                    <div class="term-number">5</div>
                    <div class="term-text">Fuel policy: Vehicle must be returned with the same fuel level as provided.</div>
                </li>
                <li>
                    <div class="term-number">6</div>
                    <div class="term-text">Smoking and pets are strictly prohibited inside the vehicle.</div>
                </li>
                <li>
                    <div class="term-number">7</div>
                    <div class="term-text">Traffic violations and fines incurred during rental period are renter's responsibility.</div>
                </li>
                <li>
                    <div class="term-number">8</div>
                    <div class="term-text">Vehicle cannot be used for commercial purposes or subleasing without prior written consent.</div>
                </li>
                <li>
                    <div class="term-number">9</div>
                    <div class="term-text">Renter is responsible for any damage to interior, exterior, or mechanical parts during rental period.</div>
                </li>
                <li>
                    <div class="term-number">10</div>
                    <div class="term-text">Cancellation must be made 24 hours in advance for full refund. Late cancellations forfeit 50% of payment.</div>
                </li>
                <li>
                    <div class="term-number">11</div>
                    <div class="term-text">Insurance coverage is basic. Additional comprehensive insurance available at extra cost.</div>
                </li>
                <li>
                    <div class="term-number">12</div>
                    <div class="term-text">Company reserves the right to terminate rental agreement if terms are violated.</div>
                </li>
            </ul>
            <button class="terms-close-btn" onclick="closeTermsModal()">I Understand</button>
        </div>
    </div>

    <script>
        const modal = document.getElementById("bookingModal");
        const detailsModal = document.getElementById("detailsModal");
        const spans = document.querySelectorAll('.close');
        
        // Multi-image slider variables
        let currentSlide = 0;

        // Form validation - ensure payment method and terms are accepted
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const paymentMethod = document.getElementById('payment_method').value;
            const termsAccepted = document.getElementById('terms_checkbox').checked;
            
            if (!paymentMethod) {
                e.preventDefault();
                alert('Please select a payment method before confirming your reservation.');
                return false;
            }
            
            if (!termsAccepted) {
                e.preventDefault();
                alert('Please accept the Terms and Conditions to proceed.');
                return false;
            }
        });

        // Terms Modal Functions
        function openTermsModal() {
            event.preventDefault();
            const termsModal = document.getElementById('termsModal');
            termsModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeTermsModal() {
            const termsModal = document.getElementById('termsModal');
            termsModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close terms modal when clicking outside
        window.addEventListener('click', function(e) {
            const termsModal = document.getElementById('termsModal');
            if (e.target === termsModal) {
                closeTermsModal();
            }
        });

        function animateAndOpen(card) {
            card.classList.add('clicked');
            setTimeout(() => {
                card.classList.remove('clicked');
                openCarDetails(card);
            }, 350);
        }

        function openCarDetails(card) {
            const data = card.dataset;
            const detailsContent = document.getElementById('details-content');
            currentSlide = 0;
            
            const images = [data.carimage, data.carimage2, data.carimage3];
            
            detailsContent.innerHTML = `
                <div class="details-hero">
                    <div class="gallery-container" id="gallery">
                        ${images.map(img => `<div class="gallery-slide" style="background-image: url('${img}')"></div>`).join('')}
                    </div>
                    
                    <div class="gallery-arrow gallery-prev" onclick="moveGallery(-1)"><i class="fas fa-chevron-left"></i></div>
                    <div class="gallery-arrow gallery-next" onclick="moveGallery(1)"><i class="fas fa-chevron-right"></i></div>
                    
                    <div class="gallery-nav">
                        ${images.map((_, i) => `<div class="gallery-dot ${i === 0 ? 'active' : ''}" onclick="goToSlide(${i})"></div>`).join('')}
                    </div>

                    <div class="details-hero-overlay" style="z-index: 6; position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 30px; color: white;">
                        <h2 style="margin: 0; text-align: left; font-size: 2rem; color: white;">${data.carname}</h2>
                        <p style="margin: 5px 0 0; font-size: 1.1rem; color: #ddd;">${data.carmodel} Premium Collection</p>
                    </div>
                </div>
                <div class="details-body">
                    <div class="specs-grid">
                        <div class="spec-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <span class="spec-label">Year</span>
                                <strong>${data.caryear}</strong>
                            </div>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-car-side"></i>
                            <div>
                                <span class="spec-label">Body Type</span>
                                <strong>${data.cartype}</strong>
                            </div>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-gas-pump"></i>
                            <div>
                                <span class="spec-label">Fuel</span>
                                <strong>Petrol / Diesel</strong>
                            </div>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <span class="spec-label">Seats</span>
                                <strong>5 - 7 Seats</strong>
                            </div>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-cog"></i>
                            <div>
                                <span class="spec-label">Trans</span>
                                <strong>Automatic</strong>
                            </div>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-tachometer-alt"></i>
                            <div>
                                <span class="spec-label">Power</span>
                                <strong>200+ HP</strong>
                            </div>
                        </div>
                    </div>
                    <div class="details-footer">
                        <div class="details-price">
                            NRS ${parseFloat(data.carprice).toLocaleString()} <small>/ day</small>
                        </div>
                        <button class="book-btn" style="width: auto; padding: 14px 40px;" onclick="closeDetails(); openModal('${data.carid}', '${data.carprice}')">
                            Continue to Booking
                        </button>
                    </div>
                </div>
            `;
            
            detailsModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function moveGallery(step) {
            const gallery = document.getElementById('gallery');
            const dots = document.querySelectorAll('.gallery-dot');
            const total = 3;
            
            currentSlide = (currentSlide + step + total) % total;
            gallery.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        function goToSlide(index) {
            const gallery = document.getElementById('gallery');
            const dots = document.querySelectorAll('.gallery-dot');
            currentSlide = index;
            gallery.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        function closeDetails() {
            detailsModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Flashy Open Booking
        function openModal(carId, price) {
            document.getElementById('carid').value = carId;
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Amount Calculation Logic
            const bookingFrom = document.getElementById('booking_from');
            const bookingTo = document.getElementById('booking_to');
            const amountInput = document.getElementById('amount');
            const carPrice = parseFloat(price);

            function calculateAmount() {
                const fromDate = new Date(bookingFrom.value);
                const toDate = new Date(bookingTo.value);

                if (fromDate && toDate && !isNaN(fromDate) && !isNaN(toDate)) {
                    const diffTime = Math.abs(toDate - fromDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Include start date as 1 day? Or just difference? Usually difference but lets say at least 1 day.
                                        // If same day, diff is 0. So +1 is good for rental usually.
                                        // Or maybe just diffDays if they rent 24h. Let's assume day based.
                                        // If logic needs to be strict difference:
                                        // const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                                        // If using date inputs, typically same date = 1 day.
                    
                    let days = diffDays;
                    if (days < 1) days = 1; // Minimum 1 day

                    const totalAmount = days * carPrice;
                    amountInput.value = totalAmount.toFixed(2);
                } else {
                    amountInput.value = '';
                }
            }

            bookingFrom.addEventListener('change', calculateAmount);
            bookingTo.addEventListener('change', calculateAmount);
            
            // Reset fields
            bookingFrom.value = '';
            bookingTo.value = '';
            amountInput.value = '';
            
            // Reset payment method selection
            document.getElementById('payment_method').value = '';
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('selected');
            });
        }

        // Payment Method Selection
        function selectPaymentMethod(method) {
            // Update hidden input
            document.getElementById('payment_method').value = method;
            
            // Update visual selection
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.target.closest('.payment-method-card').classList.add('selected');
        }

        // Close
        function closeModal() {
            modal.classList.remove('show');
            detailsModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        spans.forEach(span => {
            span.addEventListener('click', closeModal);
        });

        window.onclick = (e) => { 
            if(e.target == modal || e.target == detailsModal) closeModal(); 
        };


        // Scroll Reveal Logic
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Staggered reveal
                    setTimeout(() => {
                        entry.target.classList.add('reveal');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.car-item').forEach(item => {
            observer.observe(item);
        });

        // Deep Linking & Highlighting
        window.addEventListener('load', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const highlightName = urlParams.get('highlight');
            const highlightModel = urlParams.get('highlight_model');

            if (highlightName) {
                const targetCard = document.querySelector(`.car-item[data-carname="${highlightName}"]`);
                if (targetCard) {
                    highlightCard(targetCard);
                }
            } else if (highlightModel) {
                // Highlight ALL cards matching the model
                const targetCards = document.querySelectorAll(`.car-item[data-carmodel="${highlightModel}"]`);
                if (targetCards.length > 0) {
                    // Scroll to the first one
                    setTimeout(() => {
                        targetCards[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 500);

                    // Highlight all of them
                    targetCards.forEach(card => highlightCard(card));
                }
            }
        });

        function highlightCard(card) {
            // Slight delay to ensure reveal animation doesn't fight with scroll
            setTimeout(() => {
                card.classList.add('reveal'); // Ensure it's revealed
                card.classList.add('highlight-card');
            }, 500);
            
            // Remove highlight after a few seconds
            setTimeout(() => {
                card.style.animation = 'none';
                setTimeout(() => {
                    card.classList.remove('highlight-card');
                }, 1000);
            }, 5000);
        }

        // Recommendation Logic
        const recommendations = <?php echo json_encode($recommendedCars); ?>;
        const recReason = <?php echo json_encode($recReason); ?>;
        let currentRecIndex = 0;

        function showNextRecommendation() {
            if (currentRecIndex >= recommendations.length) return;

            const car = recommendations[currentRecIndex];
            const container = document.getElementById('rec-popup-container');

            const popup = document.createElement('div');
            popup.className = 'rec-popup';
            popup.innerHTML = `
                <div class="rec-badge"><i class="fas fa-lightbulb"></i> Recommended</div>
                <span class="rec-close" onclick="closePopup(this)">&times;</span>
                <img src="${car.image}" alt="Car">
                <div class="rec-content">
                    <h4>${car.name}</h4>
                    <p>${car.model} (${car.year})</p>
                    <span class="rec-price">NRS ${car.price} / day</span>
                    <a href="#" class="rec-book-btn-mini" onclick="openModal('${car.carid}', '${car.price}')">Book Now</a>
                </div>
            `;

            container.appendChild(popup);
            currentRecIndex++;

            if (currentRecIndex < recommendations.length) {
                setTimeout(showNextRecommendation, 6000);
            }
        }

        function closePopup(btn) {
            const popup = btn.parentElement;
            popup.classList.add('fade-out');
            setTimeout(() => popup.remove(), 500);
        }

        function bookFromPopup(carId) {
            // Find the button in the main list and click it
            const btn = document.querySelector(`.book-btn[data-carid="${carId}"]`);
            if (btn) {
                btn.click();
            } else {
                // If not in the list (though it should be), just set the value and open modal
                document.getElementById('carid').value = carId;
                document.getElementById('bookingModal').style.display = "block";
            }
        }

        if (recommendations.length > 0) {
            setTimeout(showNextRecommendation, 2000);
        }
    </script>

    <div id="rec-popup-container"></div>


    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>