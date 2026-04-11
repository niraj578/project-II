<?php
session_start();
$isLoggedIn = isset($_SESSION['login']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Rates - CarRental Premium</title>
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

        /* Navbar */
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
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.pexels.com/photos/164634/pexels-photo-164634.jpeg?auto=compress&cs=tinysrgb&w=1920&h=800&dpr=1');
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
        
        .image-box img { width: 100%; border-radius: 12px; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
        .text-box h2 { font-size: 2.2rem; margin-bottom: 1.5rem; color: var(--dark); position: relative; }
        .text-box h2::after { content: ''; position: absolute; left: 0; bottom: -10px; width: 50px; height: 4px; background: var(--primary); }
        .text-box p { font-size: 1.1rem; color: #475569; margin-bottom: 1.5rem; }

        /* Price Table */
        .price-list { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); margin-top: 2rem; }
        .price-item { display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #f1f5f9; }
        .price-item:last-child { border-bottom: none; }
        .price-item span { font-weight: 700; color: var(--primary); }

        /* Back Button */
        .cta-bottom { text-align: center; padding: 4rem 0; background: white; }
        .btn-back { display: inline-block; padding: 1rem 2rem; background: var(--primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 700; transition: 0.3s; }
        .btn-back:hover { background: #0056b3; transform: translateY(-3px); }

        /* Footer */
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
            <a href="index.php" style="text-decoration: none; color: var(--secondary);">Home</a> / <span style="color: var(--primary);">Best Rates</span>
        </div>
    </div>

    <header class="hero">
        <h1>Sasto Hoina, Best Rate!</h1>
        <p>Hamro rates sunera tapai dangai parnu hunchha. Quality ma compromise nagari, sabai bhanda affordable deal hamro ma matrai.</p>
    </header>

    <div class="content-section">
        <div class="grid">
            <div class="text-box">
                <h2>No Hidden Charges. Period.</h2>
                <p>Market ma dherai jaso thau ma 'cheap rate' bhanchhan tara pachi extra charges thapiraako hunchhe. CarRental ma testo hudaina!</p>
                <p>Whatever price you see is what you pay. Zero extra booking fees, zero hidden service taxes. Hamile transparency ma biswas garchhau.</p>
                
                <div class="price-list">
                    <div class="price-item">Compact City Cars <span>NRS 2,500/day</span></div>
                    <div class="price-item">Family SUVs <span>NRS 4,500/day</span></div>
                    <div class="price-item">Premium Luxury <span>NRS 8,000/day</span></div>
                    <div class="price-item">Vintage Classics <span>NRS 10,000/day</span></div>
                </div>
            </div>
            <div class="image-box">
                <img src="https://images.pexels.com/photos/3802510/pexels-photo-3802510.jpeg?auto=compress&cs=tinysrgb&w=800&h=500&dpr=1" alt="Best Rates">
            </div>
        </div>

        <div class="grid reverse">
            <div class="text-box">
                <h2>Value for Every Rupee</h2>
                <p>Tapai le kharcha gareko pratyek paisa ko value hamile dinchau. Hamro rates ma sirf car matrai hudaina, fully serviced condition pani hunchha.</p>
                <p>Long-term rentals ko lagi hamro ma special discounts chha. 1 week bhanda mathi rent garnu bhayo bhane extra 15% off paunu hunchha. Sasto deal khojne hoina, value deal khojne garnuhos!</p>
            </div>
            <div class="image-box">
                <img src="https://images.pexels.com/photos/5632386/pexels-photo-5632386.jpeg?auto=compress&cs=tinysrgb&w=800&h=500&dpr=1" alt="Savings">
            </div>
        </div>
    </div>

    <div class="cta-bottom">
        <a href="index.php" class="btn-back">Ready to Save? Back to Home</a>
    </div>

    <footer class="footer-lite">
        &copy; 2026 Car Rental Service - Best Service, Best Rates.
    </footer>
</body>
</html>