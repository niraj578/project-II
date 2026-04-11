<?php
session_start();
include 'connection.php';

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['login']['email'];

// Fetch User Messages
$sql_msgs = "SELECT * FROM messages WHERE email = ? ORDER BY created_at DESC";
$stmt_msg = $conn->prepare($sql_msgs);
$stmt_msg->bind_param("s", $email);
$stmt_msg->execute();
$messagesResult = $stmt_msg->get_result();
$userMessages = $messagesResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Messages - Car Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --accent-color: #00c6ff;
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
            width: 95%; max-width: 900px; margin: 80px auto 40px;
            padding: 50px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10;
        }

        .back-btn {
            display: inline-flex; align-items: center; gap: 10px; color: var(--text-muted);
            text-decoration: none; transition: all 0.3s ease; font-weight: 500; margin-bottom: 20px;
            background: rgba(255,255,255,0.05); padding: 8px 16px; border-radius: 50px;
            border: 1px solid var(--glass-border); font-size: 0.9rem;
        }
        .back-btn:hover { color: var(--text-main); background: rgba(255,255,255,0.1); }

        .header-row {
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 40px; border-bottom: 1px solid var(--glass-border); padding-bottom: 20px;
        }

        h1 {
            font-size: 2.2rem; font-weight: 600; background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-transform: uppercase; letter-spacing: 2px; margin: 0;
        }

        .new-msg-btn {
            background: var(--primary-gradient);
            color: white; padding: 12px 25px; border-radius: 12px;
            text-decoration: none; font-weight: 600; transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 198, 255, 0.3);
            display: flex; align-items: center; gap: 8px;
        }
        .new-msg-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 198, 255, 0.5); color: white; }

        .message-item {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .message-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .msg-meta {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;
        }
        
        .msg-date { font-size: 0.85rem; color: var(--text-muted); font-family: monospace; }
        .msg-label { 
            font-size: 0.75rem; text-transform: uppercase; color: #00c6ff; 
            letter-spacing: 1px; font-weight: 600; 
        }

        .message-text {
            color: var(--text-main); font-size: 1.05rem; line-height: 1.6;
            margin-bottom: 20px; font-weight: 300;
        }

        .reply-box {
            background: rgba(0, 198, 255, 0.05);
            border-left: 3px solid #00c6ff;
            border-radius: 0 12px 12px 0;
            padding: 20px;
            margin-top: 20px;
            position: relative;
        }

        .reply-header {
            display: flex; align-items: center; gap: 10px;
            color: #00c6ff; font-weight: 600; font-size: 0.9rem; margin-bottom: 10px;
            text-transform: uppercase; letter-spacing: 1px;
        }

        .reply-content { color: rgba(255,255,255,0.9); font-size: 1rem; line-height: 1.6; }

        .pending-status {
            color: #f59e0b; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;
            background: rgba(245, 158, 11, 0.1); padding: 8px 15px; border-radius: 50px;
            width: fit-content; border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .no-messages { text-align: center; padding: 60px; color: var(--text-muted); }
        .no-messages i { font-size: 50px; margin-bottom: 20px; color: rgba(255,255,255,0.1); }
        .no-messages a { color: #00c6ff; text-decoration: none; }
        .no-messages a:hover { text-decoration: underline; }

    </style>
</head>
<body>
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="container">
        <div class="header-row">
            <div>
                <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Dashboard</a>
                <h1>My Inquiries</h1>
            </div>
            <a href="contact.php" class="new-msg-btn"><i class="fas fa-paper-plane"></i> New Message</a>
        </div>

        <div class="messages-list">
            <?php if (!empty($userMessages)): ?>
                <?php foreach ($userMessages as $msg): ?>
                    <div class="message-item">
                        <div class="msg-meta">
                            <span class="msg-label">My Inquiry</span>
                            <span class="msg-date"><i class="far fa-clock"></i> <?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?></span>
                        </div>
                        
                        <div class="message-text">
                            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                        </div>
                        
                        <?php if (!empty($msg['admin_reply'])): ?>
                            <div class="reply-box">
                                <div class="reply-header">
                                    <i class="fas fa-reply"></i> Admin Response
                                </div>
                                <div class="reply-content"><?php echo nl2br(htmlspecialchars($msg['admin_reply'])); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="pending-status">
                                <i class="fas fa-hourglass-half"></i> Awaiting response from support...
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-messages">
                    <i class="fas fa-inbox"></i>
                    <p>You haven't sent any messages yet.<br>Have a question? <a href="contact.php">Contact Support</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
<?php $conn->close(); ?>
