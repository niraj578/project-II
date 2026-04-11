<?php
session_start();
$isLoggedIn = isset($_SESSION['login']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - CarRental Premium</title>
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
            position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid #e2e8f0;
        }
        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--dark); }
        .logo i { font-size: 1.5rem; color: var(--primary); }
        .logo h1 { font-size: 1.2rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

        /* Hero Section */
        .hero {
            height: 50vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg?auto=compress&cs=tinysrgb&w=1920&h=800&dpr=1');
            background-size: cover; background-position: center; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center; padding: 0 20px;
        }
        .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem; text-transform: uppercase; }
        .hero p { font-size: 1.25rem; max-width: 700px; color: #cbd5e1; }

        /* CEO Section */
        .content-section { padding: 5rem 5%; max-width: 1200px; margin: 0 auto; }
        .ceo-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 4rem; align-items: center; }
        
        .ceo-image-container { position: relative; }
        .ceo-image { 
            width: 100%; 
            border-radius: 20px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            border: 8px solid white;
            transition: 0.4s;
        }
        .ceo-image:hover { transform: scale(1.02); }
        .badge { position: absolute; bottom: -20px; right: -20px; background: var(--primary); color: white; padding: 1rem 2rem; border-radius: 50px; font-weight: 700; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

        .ceo-text h2 { font-size: 2.8rem; line-height: 1.2; margin-bottom: 1.5rem; color: var(--dark); }
        .ceo-text p { font-size: 1.15rem; color: #475569; margin-bottom: 2rem; text-align: justify; }
        .ceo-quote { 
            font-style: italic; 
            border-left: 4px solid var(--primary); 
            padding-left: 1.5rem; 
            margin: 2rem 0; 
            color: var(--secondary);
            font-size: 1.3rem;
            font-weight: 500;
        }

        /* Stats Section */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-top: 5rem; }
        .stat-card { background: white; padding: 2.5rem; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-card i { font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem; }
        .stat-card h3 { font-size: 2rem; margin-bottom: 0.5rem; }
        .stat-card p { color: #64748b; font-weight: 500; }

        /* Back Button */
        .cta-bottom { text-align: center; padding: 4rem 0; background: white; }
        .btn-back { display: inline-block; padding: 1rem 2rem; background: var(--primary); color: white; text-decoration: none; border-radius: 6px; font-weight: 700; transition: 0.3s; }
        .btn-back:hover { background: #0056b3; transform: translateY(-3px); }

        .footer-lite { text-align: center; padding: 2rem; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 0.9rem; }

        @media (max-width: 968px) {
            .ceo-grid { grid-template-columns: 1fr; text-align: center; gap: 3rem; }
            .badge { right: 50%; translate: 50% 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
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
            <a href="index.php" style="text-decoration: none; color: var(--secondary);">Home</a> / <span style="color: var(--primary);">About Us</span>
        </div>
    </div>

    <header class="hero">
        <h1>Hamro Kahani, Hamro Vision</h1>
        <p>Rental matrai hoina, hami euta yatra ko partner hau. Nepal ko digital rental revolution yahi bata suru hunchha.</p>
    </header>

    <div class="content-section">
        <div class="ceo-grid">
            <div class="ceo-image-container">
                <img src="pictures/ceo.jpg" alt="The CEO of Car Rental" class="ceo-image">
                <div class="badge">Founder & CEO</div>
            </div>
            <div class="ceo-text">
                <h2>Behind the Steering: The CEO's Story</h2>
                <p>Namaste! Malai thaha chha Nepal ma car rent garnu kati ko garo thiyo—hidden charges, unreliable condition, ra dherai tension. Tyahi tension lai 'Freedom' ma badalna maile yo Car Rental start gareko hu.</p>
                
                <div class="ceo-quote">
                    "Mero dream euta matrai chha: Nepal ko pratyek citizen le luxury ra safety ma sasto rate ma travel garna paos."
                </div>

                <p>Hamile suruwat garda sirf 2 ta car thiyo, tara bishwas pura thiyo. Aaja hamro fleet ma Nepal ko sabai bhanda best cars haru chhan. Jaba tapai CarRental ko car ma aunu hunchha, tapai hamro guest matrai hoina, hamro family member hau.</p>
                
                <p>Hami kaile pani sasto service dinchhau bhandainau, hami 'Value' dinchhau bhanchhau. Enjoy the ride, because we handle the rest!</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>10k+</h3>
                <p>Happy Clients</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-car"></i>
                <h3>150+</h3>
                <p>Premium Cars</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-award"></i>
                <h3>5+</h3>
                <p>Years Excellence</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-map-marked-alt"></i>
                <h3>50+</h3>
                <p>Districts Covered</p>
            </div>
        </div>

        <!-- Team Section -->
        <div style="margin-top: 8rem;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <h2 style="font-size: 2.5rem; color: var(--dark);">Meet the A-Team</h2>
                <p style="color: #64748b; font-size: 1.1rem;">CEO le matrai hudaina, hamro backbone bhane ko yo hardworking team ho.</p>
            </div>

            <div class="ceo-grid" style="margin-bottom: 5rem;">
                <div class="ceo-text">
                    <h2>Our Mechanics & Tech Wizards</h2>
                    <p>CarRental ko real magic garage ma hunchha. Hamro mechanics haru engine ko sano bhanda sano glitch pani 'eagle-eye' le check garchhan.</p>
                    <p>Uniharu ko ekai mission chha: Tapai ko yatra ma car kaile pani nabigros. Raat bhari jagera pani kura sudrinchha kinaki tapai ko safety hamro top priority ho.</p>
                </div>
                <div class="ceo-image-container">
                    <img src="https://images.pexels.com/photos/4489744/pexels-photo-4489744.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&dpr=1" alt="Our Mechanics" class="ceo-image">
                    <div class="badge" style="background: var(--accent);">Safety Squad</div>
                </div>
            </div>

            <div class="ceo-grid reverse" style="direction: rtl;">
                <div class="ceo-text" style="direction: ltr;">
                    <h2>The Customer Happiness Team</h2>
                    <p>Hamro support team sirf phone uthauna matrai haina, tapai ko problem solve garna baseko chha. Booking change hos ya location guide, friendly Nepali smile ko sath uniharu ready chhan.</p>
                    <p>Hami 'Customer Service' ma hoina, 'Customer Happiness' ma biswas garchbau. Tapai ko yatra ko pratyek step ma hamro team ko sath hunchha.</p>
                </div>
                <div class="ceo-image-container">
                    <img src="https://images.pexels.com/photos/7682340/pexels-photo-7682340.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&dpr=1" alt="Happiness Team" class="ceo-image">
                    <div class="badge">Success Team</div>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-bottom">
        <a href="index.php" class="btn-back">Join Our Journey - Back to Home</a>
    </div>

    <footer class="footer-lite">
        &copy; 2026 Car Rental Service - Driven by Vision, Led by Excellence.
    </footer>
</body>
</html>