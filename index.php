<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['login']);
$username = $isLoggedIn ? $_SESSION['login']['full_name'] : 'Guest'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #ff7e5f, #feb47b, #ff6a88, #ff8a00); /* Multi-color gradient */
            height: 100vh; /* Full height of the viewport */
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            font-family: Arial, sans-serif; /* Font style */
            color: #333; /* Text color */
            line-height: 1.6;
        }

        header {
            background: #50d07d;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 2.5em;
            animation: moveText 2s ease-in-out infinite; /* Apply the animation */
        }

        nav {
            margin: 20px 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        nav a:hover {
            background: #0056b3;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white background */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            padding: 20px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h2 {
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .card p {
            margin-bottom: 15px;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background: #50d07d;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            nav {
                display: flex;
                flex-direction: column;
            }

            nav a {
                margin: 5px 0;
            }
        }

        /* Add this CSS to highlight the profile button */
        .highlight {
        background-color: #007bff; /* Blue highlight color */
        border: 2px solid #0056b3; /* Optional blue border */
        transition: background-color 0.3s, border 0.3s; /* Smooth transition */
    }

    .highlight:hover {
        background-color: #0056b3; /* Darker blue highlight on hover */
    }

    /* Animation for the h1 text */
    @keyframes moveText {
        0% {
            transform: translateX(0);
        }
        50% {
            transform: translateX(10px); /* Move right */
        }
        100% {
            transform: translateX(0); /* Move back to original position */
        }
    }

    /* Animation for coming forward effect */
    @keyframes comeForward {
        0% {
            transform: translateZ(0) scale(1); /* Original position and size */
        }
        50% {
            transform: translateZ(20px) scale(1.2); /* Move forward and grow */
        }
        100% {
            transform: translateZ(0) scale(1); /* Return to original position and size */
        }
    }

    .fa-car {
        animation: comeForward 1s ease-in-out infinite; /* Apply the coming forward animation */
    }

    /* Animation for moving left */
    @keyframes moveLeft {
        0% {
            transform: translateX(0);
        }
        50% {
            transform: translateX(-10px); /* Move left */
        }
        100% {
            transform: translateX(0); /* Return to original position */
        }
    }

    .username {
        animation: moveLeft 1.5s ease-in-out infinite; /* Apply the moving left animation */
    }

    .embossed-image {
        filter: drop-shadow(2px 2px 5px rgba(0, 0, 0, 0.5)); /* Shadow for emboss effect */
        transition: transform 0.3s; /* Smooth transition for hover effect */
    }

    .embossed-image:hover {
        transform: scale(1.05); /* Slightly enlarge on hover */
    }

    .shaded-corners {
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Shadow for shading effect */
    }

    .three-d-effect {
        perspective: 1000px; /* Perspective for 3D effect */
    }

    .three-d-effect:hover {
        transform: rotateY(5deg) rotateX(5deg); /* Slight rotation for 3D effect on hover */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); /* Enhanced shadow for depth */
    }

    </style>
</head>
<body>
    <div class="banner">
        <div class="logo">
            <a href="#"><i class="fa-solid fa-car"></i></a>
            <h1>CAR RENTAL SERVICE</h1>
        </div>
        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <span class="username">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
                <a href="dashboard.php" class="profile-btn highlight">My Profile</a>
                <a href="logout.php" class="logout-btn">Logout</a>
                <link rel="stylesheet" href="logout.css">
            <?php else: ?>
                <a href="login.php" class="login-btn">Login</a>
                <a href="register.php" class="register-btn">Register</a>
                <script>
                    // Alert user to log in if they try to access My Profile
                    document.querySelector('.profile-btn').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent default link behavior
                        alert('Please log in to access your profile.');
                        window.location.href = 'login.php'; // Redirect to login page
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>
    <div class="links">
        <a href="home.php">Home</a>
        <a href="store.php">Store</a>
        <a href="team.php">Team</a>
        <a href="contact.php">Contact</a>
        
    </div>

    <main>
       
       

        <div class="header-banner">
            <img src="pictures/7-mercedes-gle-top-10.jpg" alt="Car Rental Banner" class="embossed-image shaded-corners three-d-effect">
        </div>

        <!-- Featured Cars Section First -->
        <section id="featured-cars" class="featured-cars">
            <h2>Launching Soon!!!</h2>
            <div class="car-grid">
                <div class="car-card">
                    <img src="pictures/prado.jpg" alt="Luxury Car">
                    <h3>Luxury SUV</h3>
                    <p>Starting from 5000NRS/day</p>
                    <a href="luxury_suv.php" class="book-now">Comming Soon</a>
                </div>
                <div class="car-card">
                    <img src="pictures/vintage1.jpg" alt="SUV">
                    <h3>Vintage</h3>
                    <p>Starting from 4000NRS/day</p>
                    <a href="vintage.php" class="book-now">Comming Soon</a>
                </div>
                <div class="car-card">
                    <img src="pictures/mustang.jpg" alt="Sports Car">
                    <h3>Sports Car</h3>
                    <p>Starting from 6000NRS/day</p>
                    <a href="sports_car.php" class="book-now">Comming Soon</a>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section Second -->
        <section class="why-choose-us">
            <h2>Why Choose Us</h2>
            <div class="features-grid">
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <a href="safe_and_secure.php" class="safe-and-secure">Safe & Secure</a>
                    <p>All our vehicles are fully insured and regularly maintained</p>
                </div>
                    <div class="feature">
                        <i class="fas fa-rupee-sign"></i>
                        <a href="best_rates.php" class="best-rates">Best Rates</a>
                    <p>Competitive prices with no hidden charges</p>
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <a href="flexible_rental.php" class="flexible-rental">Flexible Rental</a>
                    <p>Daily, weekly, and monthly rental options available</p>
                </div>
            </div>
        </section>

        <!-- Services Section Last -->
        <section id="services" class="services-section">
            <h2>Our Future Services Comming Soon</h2>
            <div class="services-grid">
                <div class="service-card">
                    <i class="fas fa-car-side"></i>
                    <a href="mechanics_teams.php" class="mechanics-teams">Mechanics Teams</a>
                    <p>For your vehicle Maintenance </p>
                </div>
                <div class="service-card">
                    <i class="fas fa-route"></i>
                    <a href="tour_packages.php" class="tour-packages">Tour Packages</a>
                    <p>Customized tour packages for your journey</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-user-tie"></i>
                    <a href="driver_hire.php" class="driver-hire">Driver Hire</a>
                    <p>Professional drivers at your service</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-tools"></i>
                    <a href="support.php" class="support">24/7 Support</a>
                    <p>Round-the-clock customer support</p>
                </div>
            </div>
        </section>
        <div class="title">
            <h1>CAR <span>RENTAL</span> SERVICES</h1>
            
            <p>"Freedom on wheels - your car rental journey starts here."</p>
            <a href="about.php" class="cta-button">LEARN MORE ABOUT US</a>
        </div>

    </main>

    <footer class="footer">
        <div class="copyright">
            <p>&copy; 2024 Car Rental Service. All Rights Reserved.</p>
        </div>
        <div class="socials">
            
            <h1>Connect with Us:
                <a href="https://www.facebook.com/profile.php?id=100030833889809"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.youtube.com/@PahadiMythMoto"><i class="fa-brands fa-youtube"></i></a>
                <a href="https://myaccount.google.com/personal-info?gar=WzEyMF0&hl=en" target="blank" rel="noopener noreferrer">
                    <i class="fas fa-envelope"></i></a>
                <a href="https://www.instagram.com/nirajpandey212/"><i class="fa-brands fa-instagram"></i></a>
            </h1>
        </div>
    </footer>

    <!-- Chatbot Integration -->
    <button class="chatbot-trigger" onclick="toggleChatbot()">💬</button>

    <!-- Chatbot Container -->
    <div class="chatbot-container" id="chatbotContainer">
        <div class="chatbot-header">
            <div class="chatbot-title">Car Rental Assistant</div>
            <button class="chatbot-close" onclick="toggleChatbot()">×</button>
        </div>
        
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot">
                <div class="message-content">
                    Hello! I'm your car rental assistant. How can I help you today?
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="quick-action-btn" onclick="sendQuickMessage('Book a car')">Book a car</button>
                <button class="quick-action-btn" onclick="sendQuickMessage('Available cars')">Available cars</button>
                <button class="quick-action-btn" onclick="sendQuickMessage('Pricing')">Pricing</button>
                <button class="quick-action-btn" onclick="sendQuickMessage('Contact support')">Contact support</button>
            </div>
            
            <div class="typing-indicator" id="typingIndicator">
                Assistant is typing...
            </div>
        </div>
        
        <div class="chatbot-input-container">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <button class="chatbot-send" onclick="sendMessage()">➤</button>
        </div>
    </div>

    <style>
        /* Chatbot Styles */
        .chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.3);
            z-index: 1000;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .chatbot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-title {
            font-weight: bold;
            font-size: 16px;
        }

        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
        }

        .chatbot-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 18px;
            word-wrap: break-word;
        }

        .message.bot .message-content {
            background: #e3f2fd;
            color: #333;
        }

        .message.user .message-content {
            background: #667eea;
            color: white;
        }

        .message.admin .message-content {
            background: #e8f5e8;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .chatbot-input-container {
            padding: 15px;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        .chatbot-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
            font-size: 14px;
        }

        .chatbot-send {
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chatbot-trigger {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            z-index: 1001;
            transition: transform 0.3s ease;
        }

        .chatbot-trigger:hover {
            transform: scale(1.1);
        }

        .typing-indicator {
            display: none;
            padding: 10px 15px;
            color: #666;
            font-style: italic;
        }

        .typing-indicator.active {
            display: block;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .quick-action-btn {
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 8px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .quick-action-btn:hover {
            background: #e0e0e0;
        }
    </style>

    <script>
        let isOpen = false;

        function toggleChatbot() {
            const container = document.getElementById('chatbotContainer');
            isOpen = !isOpen;
            container.style.display = isOpen ? 'flex' : 'none';
            
            if (isOpen) {
                document.getElementById('chatbotInput').focus();
                startAdminReplyCheck(); // Start checking for admin replies when chat opens
            } else {
                stopAdminReplyCheck(); // Stop checking when chat closes
            }
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        function sendQuickMessage(message) {
            document.getElementById('chatbotInput').value = message;
            sendMessage();
        }

        function sendMessage() {
            const input = document.getElementById('chatbotInput');
            const message = input.value.trim();
            
            if (!message) return;

            // Add user message to chat
            addMessage(message, 'user');
            input.value = '';

            // Show typing indicator
            showTypingIndicator();

            // Send message to backend
            fetch('chatbot_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                hideTypingIndicator();
                addMessage(data.response, 'bot');
                
                // Debug: Log the response
                console.log('Chatbot API Response:', data);
                
                // Show error if any
                if (data.error) {
                    console.error('Chatbot API Error:', data.error);
                    addMessage('Database Error: ' + data.error, 'bot');
                }
                
                // Check for admin replies after bot response
                setTimeout(() => {
                    checkForAdminReplies();
                }, 1000);
            })
            .catch(error => {
                hideTypingIndicator();
                addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                console.error('Error:', error);
            });
        }

        function checkForAdminReplies() {
            // Get session ID from a hidden input or generate one
            let sessionId = sessionStorage.getItem('chatbot_session_id');
            if (!sessionId) {
                sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                sessionStorage.setItem('chatbot_session_id', sessionId);
            }

            fetch('get_admin_replies.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ session_id: sessionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    // Check if we've already shown these admin replies
                    const lastAdminReplyTime = localStorage.getItem('lastAdminReplyTime');
                    let newReplies = data.messages;
                    
                    if (lastAdminReplyTime) {
                        newReplies = data.messages.filter(msg => 
                            new Date(msg.admin_replied_at) > new Date(lastAdminReplyTime)
                        );
                    }
                    
                    if (newReplies.length > 0) {
                        // Show the most recent admin reply
                        const latestReply = newReplies[0];
                        addMessage('Admin Reply: ' + latestReply.admin_reply, 'admin');
                        
                        // Update the last admin reply time
                        localStorage.setItem('lastAdminReplyTime', latestReply.admin_replied_at);
                    }
                }
            })
            .catch(error => {
                console.error('Error checking admin replies:', error);
            });
        }

        // Check for admin replies periodically when chat is open
        let adminReplyInterval;
        function startAdminReplyCheck() {
            if (adminReplyInterval) clearInterval(adminReplyInterval);
            adminReplyInterval = setInterval(checkForAdminReplies, 10000); // Check every 10 seconds
        }

        function stopAdminReplyCheck() {
            if (adminReplyInterval) {
                clearInterval(adminReplyInterval);
                adminReplyInterval = null;
            }
        }

        function addMessage(content, sender) {
            const messagesContainer = document.getElementById('chatbotMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}`;
            
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            contentDiv.textContent = content;
            
            messageDiv.appendChild(contentDiv);
            messagesContainer.appendChild(messageDiv);
            
            // Remove typing indicator if present
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator.parentNode) {
                typingIndicator.parentNode.removeChild(typingIndicator);
            }
            
            // Re-add typing indicator
            messagesContainer.appendChild(typingIndicator);
            
            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function showTypingIndicator() {
            document.getElementById('typingIndicator').classList.add('active');
        }

        function hideTypingIndicator() {
            document.getElementById('typingIndicator').classList.remove('active');
        }
    </script>
</body>
</html>  
