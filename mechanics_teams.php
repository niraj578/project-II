<?php
session_start();
$isLoggedIn = isset($_SESSION['login']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanics Teams - CarRental Premium</title>
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

        .hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.pexels.com/photos/190574/pexels-photo-190574.jpeg?auto=compress&cs=tinysrgb&w=1920&h=800&dpr=1');
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

        .content-section { padding: 5rem 5%; max-width: 1200px; margin: 0 auto; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 5rem; }
        .grid.reverse { direction: rtl; }
        .grid.reverse .text-box { direction: ltr; }
        
        .image-box img { width: 100%; border-radius: 12px; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
        .text-box h2 { font-size: 2.2rem; margin-bottom: 1.5rem; color: var(--dark); position: relative; }
        .text-box h2::after { content: ''; position: absolute; left: 0; bottom: -10px; width: 50px; height: 4px; background: var(--primary); }
        .text-box p { font-size: 1.1rem; color: #475569; margin-bottom: 1.5rem; }

        .cta-bottom { text-align: center; padding: 4rem 0; background: white; }
        .btn-back { display: inline-block; padding: 1rem 2rem; background: var(--primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 700; transition: 0.3s; }
        .btn-back:hover { background: #0056b3; transform: translateY(-3px); }

        .footer-lite { text-align: center; padding: 2rem; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 0.9rem; }

        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; gap: 2rem; }
            .hero h1 { font-size: 2.5rem; }
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
            <a href="index.php" style="text-decoration: none; color: var(--secondary);">Home</a> / <span style="color: var(--primary);">Mechanics</span>
        </div>
    </div>

    <header class="hero">
        <h1>Hamro Engine, Tapaiko Bharosa</h1>
        <p>Expert mechanics ko team sadhai ready chha. Hamile car matrai hoina, tapai ko yatra ko full responsibility linchau.</p>
    </header>

    <div class="content-section">
        <div class="grid">
            <div class="text-box">
                <h2>Zero Breakdown Policy</h2>
                <p>Hamro teams le pratyek trip bhanda agadi engine ko micro-level checkup garchha. Kehi sano noise suniye pani car bhada ma janna!</p>
                <p>We use high-tech diagnostic tools to ensure every moving part is in peak condition. Reliability bhaneko CarRental ko mechanics team ho. Drive with zero tension!</p>
            </div>
            <div class="image-box">
                <img src="https://images.pexels.com/photos/4489744/pexels-photo-4489744.jpeg?auto=compress&cs=tinysrgb&w=800&h=500&dpr=1" alt="Mechanic Checkup">
            </div>
        </div>

        <div class="grid reverse">
            <div class="text-box">
                <h2>Mobile Mechanic Squads</h2>
                <p>Raasta ma kehi problem bhayo? Hamro mobile squads haru KTM valley ra major highways ma standby hunchhan. Just one help request and support is coming!</p>
                <p>Expert hands reach you within minutes. Hamro mechanics haru certified experts hun jun tapai ko car ko engine lai pratyek bariko nakhyaat garchhan.</p>
            </div>
            <div class="image-box">
                <img src="https://images.pexels.com/photos/2244746/pexels-photo-2244746.jpeg?auto=compress&cs=tinysrgb&w=800&h=500&dpr=1" alt="Engine Work">
            </div>
        </div>
    </div>

    <div class="cta-bottom">
        <a href="index.php" class="btn-back">Trust Our Tech - Back to Home</a>
    </div>

    <footer class="footer-lite">
        &copy; 2026 Car Rental Service - Precision in Every Part.
    </footer>
</body>
</html>