<?php
session_start();
$isLoggedIn = isset($_SESSION['login']);
$username = $isLoggedIn ? $_SESSION['login']['full_name'] : 'Guest'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe & Secure - CarRental Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary: #007bff;
            --secondary: #1e293b;
            --accent: #10b981;
            --light: #f8fafc;
            --dark: #0f172a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--light); color: var(--secondary); line-height: 1.6; }

        /* Navbar (Consistent with index.php) */
        .banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #e2e8f0;
        }
        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--dark); }
        .logo i { font-size: 1.5rem; color: var(--primary); }
        .logo h1 { font-size: 1.2rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

        /* Hero Section */
        .hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&q=80&w=1920&h=800');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }
        .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem; text-transform: uppercase; }
        .hero p { font-size: 1.25rem; max-width: 700px; color: #cbd5e1; }

        /* Content Sections */
        .content-section { padding: 5rem 5%; max-width: 1200px; margin: 0 auto; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 5rem; }
        .grid.reverse { direction: rtl; }
        .grid.reverse .text-box { direction: ltr; }
        
        .image-box img { width: 100%; border-radius: 12px; shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
        .text-box h2 { font-size: 2.2rem; margin-bottom: 1.5rem; color: var(--dark); position: relative; }
        .text-box h2::after { content: ''; position: absolute; left: 0; bottom: -10px; width: 50px; height: 4px; background: var(--primary); }
        .text-box p { font-size: 1.1rem; color: #475569; margin-bottom: 1.5rem; }

        /* Stats Section */
        .stats-bar { background: var(--dark); color: white; padding: 4rem 5%; display: flex; justify-content: space-around; text-align: center; }
        .stat-item i { font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem; }
        .stat-item h3 { font-size: 2rem; }
        .stat-item p { color: #94a3b8; }

        /* Back Button */
        .cta-bottom { text-align: center; padding: 4rem 0; background: white; }
        .btn-back { display: inline-block; padding: 1rem 2rem; background: var(--primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 700; transition: 0.3s; }
        .btn-back:hover { background: #0056b3; transform: translateY(-3px); }

        /* Footer */
        .footer-lite { text-align: center; padding: 2rem; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 0.9rem; }

        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; gap: 2rem; }
            .hero h1 { font-size: 2.5rem; }
            .stats-bar { flex-direction: column; gap: 2rem; }
        }
    </style>
</head>
<body>
    <div class="banner">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-car"></i>
            <h1>CarRental</h1>
        </a>
        <div style="font-size: 0.9rem; font-weight: 600;">
            <a href="index.php" style="text-decoration: none; color: var(--secondary);">Home</a> / <span style="color: var(--primary);">Safety</span>
        </div>
    </div>

    <header class="hero">
        <h1>Tapaiko Surakshya, Hamro Jimma</h1>
        <p>At CarRental, we don't just rent cars; we deliver peace of mind. Every journey you take is backed by our rigorous safety standards.</p>
    </header>

    <div class="content-section">
        <!-- Maintenance Section -->
        <div class="grid">
            <div class="text-box">
                <h2>Certified 100-Point Inspection</h2>
                <p>Hamro pratyek car lai yatra bhanda agadi expert mechanics haru le check garchhan. Engine dekhi liyera tyre ko pressure samma, hamile kehi pani chhoddainau.</p>
                <p>We perform a deep diagnostic check on every vehicle after every single trip. If it's not perfect, it doesn't go on the road. Your family's safety is non-negotiable for us.</p>
            </div>
            <div class="image-box">
                <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&q=80&w=800&h=500" alt="Car Maintenance">
            </div>
        </div>

        <!-- Insurance Section -->
        <div class="grid reverse">
            <div class="text-box">
                <h2>Full Insurance Coverage</h2>
                <p>Dukhad ghatna bhanda pani paila surakshya! All our premium cars come with comprehensive insurance. Tapai lai tension linu pardaina, hamile sabai cover gareka chhau.</p>
                <p>From minor scratches to major accidents, our policy ensures that you are protected financially and legally. Drive with the confidence that CarRental has your back at every turn.</p>
            </div>
            <div class="image-box">
                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=800&h=500" alt="Insurance and Papers">
            </div>
        </div>

        <!-- Support Section -->
        <div class="grid">
            <div class="text-box">
                <h2>24/7 Roadside Assistance</h2>
                <p>Raat ko 2 baje hos ya din ko 12, hamro team sadhai ready chha. Car bigryo ya kehi emergency bhayo bhane, just one call and we are there!</p>
                <p>Our GPS-tracked support fleet is stationed across major routes to ensure help reaches you within minutes. We provide towing, fuel delivery, and even replacement vehicles instantly.</p>
            </div>
            <div class="image-box">
                <img src="https://images.pexels.com/photos/4489749/pexels-photo-4489749.jpeg?auto=compress&cs=tinysrgb&w=800&h=500&dpr=1" alt="Emergency Assistance">
            </div>
        </div>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <i class="fas fa-check-circle"></i>
            <h3>100%</h3>
            <p>Sanitized Fleet</p>
        </div>
        <div class="stat-item">
            <i class="fas fa-user-shield"></i>
            <h3>Verified</h3>
            <p>Drivers & Staff</p>
        </div>
        <div class="stat-item">
            <i class="fas fa-headset"></i>
            <h3>24/7</h3>
            <p>Live Support</p>
        </div>
    </div>

    <div class="cta-bottom">
        <a href="index.php" class="btn-back">Ready to Rent? Back to Home</a>
    </div>

    <footer class="footer-lite">
        &copy; 2026 Car Rental Service - Safety First, Always.
    </footer>
</body>
</html>