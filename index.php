<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['login']);
$username = $isLoggedIn ? $_SESSION['login']['full_name'] : 'Guest'; 

// Check for McQueen auto-arrival flag
$autoTriggerMcQueen = false;
if (isset($_SESSION['just_logged_in'])) {
    $autoTriggerMcQueen = true;
    unset($_SESSION['just_logged_in']); // Clear the flag so it only happens once
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarRental - Premium Car Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary: #007bff;
            --secondary: #1e293b;
            --accent: #10b981;
            --light: #f8fafc;
            --dark: #0f172a;
            --card-bg: #ffffff;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --card-shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--secondary);
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* Modern Navbar */
        .banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 5%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--dark);
        }

        .logo i {
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary), var(--dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(0, 123, 255, 0.2));
            transition: all 0.3s ease;
        }

        .logo:hover i {
            transform: scale(1.1) rotate(-5deg);
            filter: drop-shadow(0 4px 8px rgba(0, 123, 255, 0.4));
        }

        .logo h1 {
            font-size: 1.25rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
            position: relative;
            color: var(--dark);
            overflow: hidden;
            background: linear-gradient(90deg, var(--dark), var(--primary), var(--dark));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: logoShimmer 3s linear infinite;
        }

        @keyframes logoShimmer {
            to { background-position: 200% center; }
        }

        .logo:hover h1 {
            animation: logoGlitch 0.3s cubic-bezier(.25,.46,.45,.94) both infinite;
        }

        @keyframes logoGlitch {
            0% { transform: translate(0); text-shadow: none; }
            20% { transform: translate(-2px, 2px); text-shadow: 2px 0 #ff00c1, -2px 0 #00fff9; }
            40% { transform: translate(-2px, -2px); text-shadow: 2px 0 #ff00c1, -2px 0 #00fff9; }
            60% { transform: translate(2px, 2px); text-shadow: 2px 0 #ff00c1, -2px 0 #00fff9; }
            80% { transform: translate(2px, -2px); text-shadow: 2px 0 #ff00c1, -2px 0 #00fff9; }
            100% { transform: translate(0); }
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .username {
            font-weight: 500;
            color: var(--secondary);
            font-size: 0.9rem;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .login-btn { color: var(--secondary); }
        .register-btn, .profile-btn { 
            background: var(--primary); 
            color: white; 
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }
        .register-btn:hover, .profile-btn:hover { background: #0056b3; transform: translateY(-1px); }

        .logout-btn { color: #ef4444; font-size: 0.9rem; margin-left: 10px; text-decoration: none; font-weight: 600; }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            padding: 1rem 0;
            background: white;
            border-bottom: 1px solid #f1f5f9;
        }

        .nav-links a {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a:hover { color: var(--primary); }

        /* Hero Showcase Gallery */
        .wow-showcase {
            width: 100%;
            height: 600px;
            background: #000;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Dark Layer for Hero */
        .wow-showcase::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5));
            z-index: 4;
            pointer-events: none;
        }

        .showcase-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .showcase-img.active {
            opacity: 1;
        }

        .showcase-overlay {
            position: absolute;
            z-index: 5;
            text-align: center;
            color: white;
            text-shadow: 0 4px 20px rgba(0,0,0,0.6);
            pointer-events: none;
        }

        .showcase-overlay h2 {
            font-size: 4rem;
            color: white !important;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 10px;
            background: none !important;
        }

        .showcase-overlay h2::after { display: none; }

        /* End Hero Showcase Gallery */


        /* Section Styling */
        section {
            padding: 5rem 5%;
            max-width: 1300px;
            margin: 0 auto;
        }

        section h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .section-under {
            display: block;
            width: 50px;
            height: 4px;
            background: var(--primary);
            margin: 0 auto 3rem;
            border-radius: 2px;
        }

        /* Boxy Grids */
        .car-grid, .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
        }

        .car-card, .feature, .service-card {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
            box-shadow: var(--card-shadow);
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-align: center;
            opacity: 0;
            transform: scale(0.9) translateY(40px);
            cursor: pointer;
        }

        .reveal { 
            opacity: 1 !important; 
            transform: scale(1) translateY(0) !important; 
        }

        /* Section Header Animation */
        section h2, .section-under {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease-out;
        }

        section.active-section h2, section.active-section .section-under {
            opacity: 1;
            transform: translateY(0);
        }

        .car-card:hover, .feature:hover, .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--card-shadow-hover);
        }

        .car-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .car-card h3 { font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--dark); }
        .car-card p { color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem; }

        .book-now {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--dark);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: background 0.2s;
        }

        .book-now:hover { background: var(--primary); }

        /* Feature Icons */
        .feature i, .service-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .feature a, .service-card a {
            display: block;
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .feature p, .service-card p { color: #64748b; font-size: 0.9rem; }

        /* CTA Section */
        .title-cta {
            background: var(--dark);
            color: white;
            padding: 6rem 5%;
            text-align: center;
            margin-top: 4rem;
        }

        .title-cta h1 { font-size: 3rem; margin-bottom: 1.5rem; font-weight: 300; }
        .title-cta span { color: var(--primary); font-weight: 700; }
        .title-cta p { font-size: 1.25rem; color: #cbd5e1; margin-bottom: 2.5rem; }

        .cta-button {
            display: inline-block;
            padding: 1.2rem 2.5rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: transform 0.2s;
        }

        .cta-button:hover { transform: scale(1.05); }

        /* Footer */
        .footer-main {
            background: white;
            padding: 4rem 5%;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .socials a {
            margin: 0 1rem;
            font-size: 1.5rem;
            color: #64748b;
            transition: color 0.2s;
        }
        .socials a:hover { color: var(--primary); }
        .copyright { color: #94a3b8; font-size: 0.875rem; margin-top: 2rem; }

        /* AI Car Assistant Styles */
        /* AI Car Assistant Styles (Lightning McQueen) */
        #ai-car-container {
            position: fixed;
            bottom: 50px;
            left: -150px;
            width: 80px; /* Micro Size */
            height: 40px;
            z-index: 10001;
            pointer-events: none;
            transition: all 0.1s;
        }

        .ai-car-body {
            position: relative;
            width: 100%;
            height: 40px;
            background: #e10600; /* McQueen Red */
            border-radius: 10px 30px 8px 10px;
            border: 1.5px solid #b30500;
            box-shadow: 0 0 10px rgba(225, 6, 0, 0.4);
            overflow: visible;
        }

        /* spoiler */
        .ai-car-body::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            width: 35px;
            height: 12px;
            background: #e10600;
            border-radius: 3px 3px 0 0;
            border-left: 1.5px solid #b30500;
            border-top: 1.5px solid #b30500;
        }

        /* Smile */
        .ai-car-body::after {
            content: ')';
            position: absolute;
            top: 15px;
            right: 10px;
            color: white;
            font-size: 14px;
            transform: rotate(90deg);
            opacity: 0.6;
            font-weight: bold;
        }

        .ai-car-window {
            position: absolute;
            top: 1px;
            right: 20px;
            width: 50px;
            height: 22px;
            background: #fff; 
            border-radius: 10px 25px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 0 5px;
            border: 1.5px solid #333;
            z-index: 5;
        }

        .mcqueen-eye {
            width: 5px;
            height: 5px;
            background: #000;
            border-radius: 50%;
            position: relative;
        }

        .mcqueen-eye::after {
            content: '';
            position: absolute;
            top: 1px;
            left: 1px;
            width: 2px;
            height: 2px;
            background: #55aaff; 
            border-radius: 50%;
        }

        .hood-logo {
            position: absolute;
            top: 18px;
            right: 15px;
            font-size: 0.3rem;
            color: #ffcc00;
            font-weight: 900;
            font-style: italic;
            text-shadow: 1px 1px 0 #000;
            z-index: 4;
            transform: rotate(-10deg);
        }

        .lightning-bolt {
            position: absolute;
            bottom: 2px;
            left: 3px;
            font-size: 1.2rem;
            color: #ffcc00; 
            transform: skewX(-20deg) rotate(-5deg);
            font-weight: 900;
            text-shadow: 1px 1px 0 #000;
            z-index: 2;
        }

        .number-95 {
            position: absolute;
            top: 55%;
            left: 45%;
            transform: translate(-50%, -50%);
            color: #ffcc00;
            font-weight: 900;
            font-size: 0.8rem;
            font-style: italic;
            -webkit-text-stroke: 0.5px #000;
            z-index: 3;
        }

        .ai-car-wheel {
            position: absolute;
            bottom: -6px;
            width: 18px;
            height: 18px;
            background: #222;
            border: 2px solid #e10600; 
            border-radius: 50%;
            animation: rotateWheel 0.5s linear infinite;
            z-index: 10;
        }

        .wheel-front { right: 15px; }
        .wheel-back { left: 15px; }

        @keyframes rotateWheel {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .smoke-particle {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 10px;
            height: 10px;
            background: rgba(150, 150, 150, 0.5);
            border-radius: 50%;
            pointer-events: none;
            animation: smokeRise 1s ease-out forwards;
        }

        @keyframes smokeRise {
            0% { transform: scale(1) translateY(0); opacity: 0.8; }
            100% { transform: scale(3) translateY(-40px) translateX(-20px); opacity: 0; }
        }

        .ai-greeting {
            position: absolute;
            top: -70px; /* Adjusted further */
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 5px 12px; /* Micro padding */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-weight: 700;
            font-size: 0.75rem; /* Micro font */
            white-space: nowrap;
            color: var(--dark);
            opacity: 0;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px; /* Micro gap */
            z-index: 100;
        }

        .ai-greeting button {
            background: #ffcc00;
            border: none;
            padding: 3px 10px; /* Micro button */
            border-radius: 6px;
            font-weight: 900;
            font-size: 0.7rem; /* Micro button text */
            cursor: pointer;
            transition: transform 0.2s;
        }

        .ai-greeting button:hover { transform: scale(1.1); }

        .ai-greeting::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid white;
        }

        .beep-text {
            position: absolute;
            top: -40px;
            left: 100px;
            color: #ffcc00;
            font-weight: 900;
            font-size: 1.5rem;
            text-shadow: 2px 2px 0 #000;
            opacity: 0;
        }

        .do-beep {
            animation: beepEffect 0.5s ease-out forwards;
        }

        @keyframes beepEffect {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.5); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Call Queen Button */
        #ai-helper-btn {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            background: #e10600;
            color: white;
            padding: 10px 6px; /* Micro padding */
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            z-index: 10000;
            box-shadow: 3px 0 10px rgba(225, 6, 0, 0.2);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px; /* Micro gap */
            border: 1px solid #b30500;
        }

        #ai-helper-btn:hover {
            padding-left: 12px;
            background: #ffcc00;
            color: #000;
        }

        #ai-helper-btn i { font-size: 1.2rem; } /* Micro icon */
        #ai-helper-btn span { writing-mode: vertical-rl; transform: rotate(180deg); font-weight: 900; font-size: 0.65rem; } /* Micro text */

        /* Drift Animation Class */
        .drift-in {
            animation: carDrift 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes carDrift {
            0% { left: -300px; transform: rotate(0deg); }
            60% { left: 150px; transform: rotate(-5deg); }
            80% { left: 120px; transform: rotate(2deg); }
            100% { left: 130px; transform: rotate(0deg); }
        }

        .drive-across {
            animation: driveSequence 6s linear forwards;
        }

        @keyframes driveSequence {
            0% { left: -300px; }
            100% { left: 110vw; }
        }

        /* Model Selection Popup */
        #model-popup {
            display: none;
            position: fixed;
            z-index: 20000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.85);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        #model-popup.show { display: flex; opacity: 1; }
        .model-popup-content {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            color: white;
            padding: 40px;
            border-radius: 16px;
            width: 600px;
            max-width: 90%;
            text-align: center;
            box-shadow: 0 0 50px rgba(0, 123, 255, 0.3);
            border: 1px solid rgba(255,255,255,0.1);
            transform: scale(0.8);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        #model-popup.show .model-popup-content { transform: scale(1); }
        .model-popup-content h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #fff, #a5f3fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .model-popup-content p { color: #94a3b8; margin-bottom: 30px; }
        .model-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            max-height: 400px;
            overflow-y: auto;
            padding: 5px;
        }
        .model-option {
            background: rgba(255,255,255,0.05);
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            font-weight: 600;
            color: #e2e8f0;
        }
        .model-option:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        .skip-btn {
            margin-top: 25px;
            background: transparent;
            border: none;
            color: #64748b;
            cursor: pointer;
            text-decoration: underline;
            font-size: 0.9rem;
        }
        .skip-btn:hover { color: white; }
    </style>
</head>
<body>
    <div class="banner">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-car"></i>
            <h1>CarRental</h1>
        </a>
        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <span class="username">Hello, <?php echo htmlspecialchars($username); ?></span>
                <a href="dashboard.php" class="btn profile-btn">Dashboard</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn login-btn">Login</a>
                <a href="register.php" class="btn register-btn">Get Started</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="nav-links">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="store.php"><i class="fas fa-car-side"></i> Store</a>
        <a href="team.php"><i class="fas fa-users"></i> Team</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
        <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
    </div>

    <main>
        <div class="wow-showcase reveal">
            
            <img src="pictures/hero_1.png" class="showcase-img active" id="img1">
            <img src="pictures/hero_2.png" class="showcase-img" id="img2">
            <img src="pictures/hero_3.png" class="showcase-img" id="img3">
            <img src="pictures/hero_4.png" class="showcase-img" id="img4">
            <img src="pictures/hero_5.png" class="showcase-img" id="img5">

            <div class="showcase-overlay">
                <h2>EXPERIENCE THE POWER</h2>
                <p>Unlock the Ultimate Drive with Premium Cars</p>
            </div>
        </div>


        <!-- Featured Cars Section -->
        <section id="featured-cars">
            <h2>Just Arrived</h2>
            <div class="section-under"></div>
            <div class="car-grid">
                <div class="car-card" onclick="location.href='store.php?highlight=Prado VX';">
                    <img src="pictures/prado.jpg" alt="Luxury Car">
                    <h3>Luxury SUV</h3>
                    <p>Experience the ultimate comfort and power with our Prado VX.</p>
                    <a href="store.php?highlight=Prado VX" class="book-now">Check It Out</a>
                </div>
                <div class="car-card" onclick="location.href='store.php?highlight=Vintage';">
                    <img src="pictures/vintage1.jpg" alt="SUV">
                    <h3>Classic Vintage</h3>
                    <p>Travel back in time with elegance and style.</p>
                    <a href="store.php?highlight=Vintage" class="book-now">Check It Out</a>
                </div>
                <div class="car-card" onclick="location.href='store.php?highlight=Mustang';">
                    <img src="pictures/mustang.jpg" alt="Sports Car">
                    <h3>Modern Sports</h3>
                    <p>Fast, bold, and ready for the open road.</p>
                    <a href="store.php?highlight=Mustang" class="book-now">Check It Out</a>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="why-choose-us">
            <h2>Why Choose Us</h2>
            <div class="section-under"></div>
            <div class="features-grid">
                <div class="feature" onclick="location.href='safe_and_secure.php';">
                    <i class="fas fa-shield-alt"></i>
                    <a href="safe_and_secure.php">Safe & Secure</a>
                    <p>All our vehicles are fully insured and regularly maintained for your peace of mind.</p>
                </div>
                <div class="feature" onclick="location.href='best_rates.php';">
                    <i class="fas fa-rupee-sign"></i>
                    <a href="best_rates.php">Best Rates</a>
                    <p>We offer competitive prices with no hidden charges or surprises.</p>
                </div>
                <div class="feature" onclick="location.href='flexible_rental.php';">
                    <i class="fas fa-clock"></i>
                    <a href="flexible_rental.php">Flexible Rental</a>
                    <p>Choose from daily, weekly, or monthly rental options to suit your needs.</p>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services">
            <h2>Our Future Services</h2>
            <div class="section-under"></div>
            <div class="services-grid">
                <div class="service-card" onclick="location.href='mechanics_teams.php';">
                    <i class="fas fa-car-side"></i>
                    <a href="mechanics_teams.php">Mechanics Teams</a>
                    <p>On-demand maintenance support for your rented vehicle.</p>
                </div>
                <div class="service-card" onclick="location.href='tour_packages.php';">
                    <i class="fas fa-route"></i>
                    <a href="tour_packages.php">Tour Packages</a>
                    <p>Curated travel itineraries for smooth journeys across the country.</p>
                </div>
                <div class="service-card" onclick="location.href='support.php';">
                    <i class="fas fa-tools"></i>
                    <a href="support.php">24/7 Support</a>
                    <p>Our help center is always available to assist you anytime.</p>
                </div>
                <div class="service-card" onclick="location.href='driver_hire.php';">
                    <i class="fas fa-user-tie"></i>
                    <a href="driver_hire.php">Professional Drivers</a>
                    <p>Hire experienced and verified drivers for a stress-free trip.</p>
                </div>
            </div>
        </section>

        <div class="title-cta">
            <h1>YOUR JOURNEY <span>STARTS HERE</span></h1>
            <p>"Freedom on wheels - explore the world at your own pace."</p>
        </div>
    </main>

    <footer class="footer-main">
        <div class="socials">
            <a href="https://www.facebook.com/profile.php?id=61578654355927"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://www.youtube.com/@PahadiMythMoto"><i class="fa-brands fa-youtube"></i></a>
            <a href="https://www.instagram.com/nirajpandey212/"><i class="fa-brands fa-instagram"></i></a>
        </div>
        <p class="copyright">&copy; 2026 Car Rental Service. All Rights Reserved.</p>
    </footer>

    <!-- AI Assistant: McQueen -->
    <div id="ai-helper-btn" onclick="activateAICar()">
        <i class="fas fa-bolt"></i>
        <span>CALL QUEEN</span>
    </div>

    <div id="ai-car-container">
        <div class="ai-greeting" id="ai-greeting">
            <span id="ai-msg">Kachow! Ready for a victory lap, <?php echo htmlspecialchars($username); ?>?</span>
            <button id="ai-btn" onclick="mcqueenGuide()">Speed to Store! 🏁</button>
        </div>
        <div class="beep-text" id="ai-beep">BEEP BEEP!!</div>
        <div class="ai-car-body">
            <div class="ai-car-window">
                <div class="mcqueen-eye"></div>
                <div class="mcqueen-eye"></div>
            </div>
            <div class="hood-logo">RUST-EZE</div>
            <div class="lightning-bolt">⚡</div>
            <div class="number-95">95</div>
            <div class="ai-car-wheel wheel-front"></div>
            <div class="ai-car-wheel wheel-back"></div>
        </div>
    </div>


    <script>
        // Scroll Reveal Animation
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // Activate section headers
                    entry.target.classList.add('active-section');

                    // Staggering effect for cards inside the section
                    const items = entry.target.querySelectorAll('.car-card, .feature, .service-card');
                    items.forEach((item, i) => {
                        setTimeout(() => {
                            item.classList.add('reveal');
                        }, i * 150);
                    });

                    // For elements that are not sections (like the showcase)
                    if (entry.target.classList.contains('wow-showcase')) {
                        entry.target.classList.add('reveal');
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Track sections and individual major elements
        document.querySelectorAll('section, .wow-showcase').forEach(el => observer.observe(el));

        // Hero Showcase Image Rotation
        const showcaseImages = document.querySelectorAll('.showcase-img');
        let currentIdx = 0;

        function rotateShowcase() {
            // Change Image with smooth fade
            showcaseImages[currentIdx].classList.remove('active');
            currentIdx = (currentIdx + 1) % showcaseImages.length;
            showcaseImages[currentIdx].classList.add('active');

            // Move to next after a short stay
            setTimeout(rotateShowcase, 3000);
        }

        // Start rotation after initial reveal
        setTimeout(rotateShowcase, 3000);

        // AI Assistant Logic (Lightning McQueen)
        const aiCar = document.getElementById('ai-car-container');
        const aiGreeting = document.getElementById('ai-greeting');
        const aiBeep = document.getElementById('ai-beep');
        const aiMsg = document.getElementById('ai-msg');
        const aiBtn = document.getElementById('ai-btn');
        let isMoving = false;

        function createSmoke() {
            if (!isMoving) return;
            const smoke = document.createElement('div');
            smoke.className = 'smoke-particle';
            aiCar.appendChild(smoke);
            setTimeout(() => smoke.remove(), 1000);
        }

        setInterval(createSmoke, 100);

        function playEngineRoar(duration) {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const noiseBuffer = audioCtx.createBuffer(1, audioCtx.sampleRate * duration, audioCtx.sampleRate);
            const output = noiseBuffer.getChannelData(0);
            for (let i = 0; i < noiseBuffer.length; i++) {
                output[i] = Math.random() * 2 - 1;
            }

            const whiteNoise = audioCtx.createBufferSource();
            whiteNoise.buffer = noiseBuffer;

            const filter = audioCtx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(400, audioCtx.currentTime);
            filter.frequency.exponentialRampToValueAtTime(100, audioCtx.currentTime + duration);

            const gainNode = audioCtx.createGain();
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.1, audioCtx.currentTime + 0.2);
            gainNode.gain.linearRampToValueAtTime(0, audioCtx.currentTime + duration);

            whiteNoise.connect(filter);
            filter.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            whiteNoise.start();
            whiteNoise.stop(audioCtx.currentTime + duration);
        }

        function playHornSound() {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc1 = audioCtx.createOscillator();
            const osc2 = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc1.type = 'triangle';
            osc2.type = 'triangle';
            osc1.frequency.setValueAtTime(440, audioCtx.currentTime);
            osc2.frequency.setValueAtTime(440 * 1.5, audioCtx.currentTime);

            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.1, audioCtx.currentTime + 0.05);
            gain.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 0.5);

            osc1.connect(gain);
            osc2.connect(gain);
            gain.connect(audioCtx.destination);

            osc1.start();
            osc2.start();
            osc1.stop(audioCtx.currentTime + 0.5);
            osc2.stop(audioCtx.currentTime + 0.5);
        }

        function activateAICar() {
            if (isMoving) return;
            isMoving = true;
            
            aiCar.style.transition = 'all 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            aiCar.style.left = '130px';
            aiCar.style.transform = 'rotate(0deg)';
            
            playEngineRoar(1.5);

            setTimeout(() => {
                aiGreeting.style.opacity = '1';
                aiGreeting.style.pointerEvents = 'auto';
            }, 1500);
        }

        function mcqueenGuide() {
            aiBtn.style.display = 'none';
            aiMsg.innerText = "Speeding to the car showcase! Look at these curves!";
            
            setTimeout(() => {
                // 1. Zoom to the Store link
                aiCar.style.transition = 'all 2s ease-in-out';
                aiCar.style.left = '43vw'; 
                aiCar.style.bottom = '85vh';
                
                setTimeout(() => {
                    // 2. Beep Beep!
                    aiMsg.innerText = "Check out the RENTALS! Best in town!";
                    aiBeep.classList.add('do-beep');
                    playHornSound();
                    
                    setTimeout(() => {
                        aiBeep.classList.remove('do-beep');
                        
                        setTimeout(() => {
                            // 3. Goodbye message
                            aiMsg.innerText = "I have to go, duty calls at the Piston Cup!";
                            
                            setTimeout(() => {
                                aiGreeting.style.opacity = '0';
                                // 4. Drive away
                                aiCar.style.transition = 'all 1.5s cubic-bezier(0.6, -0.28, 0.735, 0.045)';
                                aiCar.style.left = '110vw';
                                aiCar.style.bottom = '110vh';
                                aiCar.style.transform = 'rotate(-45deg) scale(0.5)';
                                
                                setTimeout(() => {
                                    aiCar.style.transition = 'none';
                                    aiCar.style.transform = 'rotate(0deg) scale(1)';
                                    aiCar.style.left = '-150px';
                                    aiCar.style.bottom = '50px';
                                    aiBtn.style.display = 'block';
                                    aiMsg.innerText = "Kachow! Ready for a victory lap, <?php echo htmlspecialchars($username); ?>?";
                                    isMoving = false;
                                }, 1500);
                            }, 2000);
                        }, 1000);
                    }, 2000); // Beep for 2 seconds
                }, 2000);
            }, 1000);
        }


    </script>

    <!-- Model Selection Modal -->
    <div id="model-popup">
        <div class="model-popup-content">
            <h2>Choose Your Dream Ride</h2>
            <p>Select a model you're interested in for personalized recommendations.</p>
            <div class="model-grid" id="model-list">
                <!-- Models injected here -->
            </div>
            <button class="skip-btn" onclick="closeModelPopup()">I'll browse everything</button>
        </div>
    </div>

    <script>
        function showModelPopup() {
            const popup = document.getElementById('model-popup');
            const list = document.getElementById('model-list');
            
            // Fetch models
            fetch('get_car_models.php')
                .then(response => response.json())
                .then(models => {
                    list.innerHTML = '';
                    models.forEach(model => {
                        const div = document.createElement('div');
                        div.className = 'model-option';
                        div.innerText = model;
                        div.onclick = () => selectModel(model);
                        list.appendChild(div);
                    });
                    
                    popup.classList.add('show');
                })
                .catch(err => console.error(err));
        }

        function selectModel(model) {
            const formData = new FormData();
            formData.append('model', model);
            
            fetch('set_preference.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update recommendations in store/dashboard via reload or redirect
                    // Redirect with highlight param
                    window.location.href = 'store.php?highlight_model=' + encodeURIComponent(model);
                }
            });
        }

        function closeModelPopup() {
            document.getElementById('model-popup').classList.remove('show');
        }

        // Initialize McQueen OR Popup if auto-trigger is set
        <?php if ($autoTriggerMcQueen): ?>
        window.addEventListener('load', () => {
             // Prioritize Popup for user preference
             setTimeout(showModelPopup, 500);

             // Optional: Still run McQueen in background or after?
             // Let's run McQueen slightly later so he drifts in while user decides
            setTimeout(activateAICar, 2500); 
        });
        <?php endif; ?>
    </script>
</body>
</html>  
