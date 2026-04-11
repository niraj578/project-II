<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrentaldb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM messages ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
            width: 95%; max-width: 1200px; margin: 100px auto 40px;
            padding: 40px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10;
        }

        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
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

        /* Table Styling */
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        th { text-align: left; padding: 20px; color: var(--text-muted); font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 20px; background: rgba(255, 255, 255, 0.03); border-top: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border); }
        td:first-child { border-left: 1px solid var(--glass-border); border-radius: 12px 0 0 12px; }
        td:last-child { border-right: 1px solid var(--glass-border); border-radius: 0 12px 12px 0; }
        tr:hover td { background: rgba(255, 255, 255, 0.07); }

        /* Modal Styles */
        .modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.8);
            backdrop-filter: blur(8px);
        }
        .modal-content {
            background: #111; margin: 10% auto; padding: 40px;
            border: 1px solid var(--glass-border); width: 600px; border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: modalIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes modalIn { from { transform: scale(0.8) translateY(20px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
        .close-btn { color: var(--text-muted); float: right; font-size: 24px; cursor: pointer; transition: 0.3s; }
        .close-btn:hover { color: white; transform: rotate(90deg); }

        textarea {
            width: 100%; height: 120px; background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border); border-radius: 12px;
            color: white; padding: 15px; margin: 15px 0; outline: none; resize: none;
            font-family: inherit; font-size: 14px;
        }
        textarea:focus { border-color: #ff4b2b; }

        .btn-group { display: flex; gap: 10px; margin-top: 20px; }
        .primary-btn { background: var(--primary-gradient); color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; font-weight: 600; }
        .secondary-btn { background: rgba(255,255,255,0.1); color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; }
        .success-btn { background: #10b981; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; font-weight: 600; }
    </style>

    <script>
        let currentMessageId = null;

        function showPopup(data) {
            // Set customer info
            document.getElementById('msgName').innerText = data.name;
            document.getElementById('msgEmail').innerText = data.email;
            document.getElementById('msgContent').innerText = data.message;
            
            // Handle existing reply
            const existingReplySection = document.getElementById('existingReplySection');
            const existingReplyContent = document.getElementById('existingReplyContent');
            const replyLabel = document.getElementById('replyLabel');
            
            if (data.admin_reply && data.admin_reply.trim() !== '') {
                // Show existing reply
                existingReplySection.style.display = 'block';
                existingReplyContent.innerText = data.admin_reply;
                document.getElementById('adminReplyText').value = data.admin_reply;
                replyLabel.innerText = 'Update Your Response';
            } else {
                // No existing reply
                existingReplySection.style.display = 'none';
                document.getElementById('adminReplyText').value = '';
                replyLabel.innerText = 'Your Response';
            }
            
            currentMessageId = data.id;
            document.getElementById('messagePopup').style.display = "block";
        }

        function closePopup() {
            document.getElementById('messagePopup').style.display = "none";
        }

        function sendReply() {
            if (!currentMessageId) { closePopup(); return; }
            const replyText = document.getElementById('adminReplyText').value;
            if (!replyText.trim()) { alert('Please enter a reply.'); return; }

            const formData = new FormData();
            formData.append('id', currentMessageId);
            formData.append('reply', replyText);

            fetch('reply_message.php', { method: 'POST', body: formData })
                .then(response => response.text())
                .then(res => {
                    if (res.trim() === 'Success') {
                        location.reload();
                    } else {
                        alert('Error saving reply: ' + res);
                    }
                })
                .catch(e => console.error(e));
        }

        function markAsRead() {
            if (!currentMessageId) { closePopup(); return; }

            // Optimistically close
            closePopup();

            // Send request to mark as read
            const formData = new FormData();
            formData.append('id', currentMessageId);
            fetch('mark_message_read.php', { method: 'POST', body: formData })
                .then(response => response.text())
                .then(res => {
                    location.reload(); 
                })
                .catch(e => console.error(e));
        }
        
        // Close modal if clicked outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('messagePopup')) {
                closePopup();
            }
        }
    </script>
</head>
<body>
    <!-- Background Effects -->
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <header>
            <h1>Customer Inquiries</h1>
            <a href="admin_dash.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </header>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sender</th>
                    <th>Message Snippet</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                        $isRead = $row['status'] === 'read';
                        echo "<tr onclick='showPopup($rowData)' style='cursor:pointer' title='Click to view full message'>";
                        echo "<td style='font-family: monospace; color: var(--accent-color);'>#" . $row['id'] . "</td>";
                        echo "<td>
                                <div style='font-weight: 600;'>" . htmlspecialchars($row['name']) . "</div>
                                <div style='font-size: 11px; color: var(--text-muted);'>" . htmlspecialchars($row['email']) . "</div>
                              </td>";
                        $msg = htmlspecialchars($row['message']);
                        if (strlen($msg) > 60) $msg = substr($msg, 0, 60) . '...';
                        echo "<td style='color: var(--text-muted);'>" . $msg . "</td>";
                        $statusStyle = $isRead ? "color: #10b981;" : "color: #f59e0b;";
                        $statusIcon = $isRead ? "fa-check-double" : "fa-envelope";
                        echo "<td style='$statusStyle font-weight: 600; font-size: 12px; text-transform: uppercase;'>
                                <i class='fas $statusIcon' style='margin-right: 5px;'></i> " . $row['status'] . "
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center; padding: 40px; color: var(--text-muted);'>No customer messages found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Message Popup Modal -->
    <div id="messagePopup" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h2 style="margin-bottom: 25px; font-weight: 600; color: white;">Customer Message</h2>
            
            <!-- User's Original Message -->
            <div id="messageDetails" style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 15px; border: 1px solid var(--glass-border); margin-bottom: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 11px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">
                        <i class="fas fa-user"></i> Customer
                    </label>
                    <div style="font-weight: 600; font-size: 16px;">
                        <span id="msgName"></span>
                    </div>
                    <div style="color: var(--accent-color); font-size: 14px; margin-top: 5px;">
                        <i class="fas fa-envelope"></i> <span id="msgEmail"></span>
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                        <i class="fas fa-comment-dots"></i> Their Message
                    </label>
                    <div id="msgContent" style="line-height: 1.8; color: var(--text-main); background: rgba(0,0,0,0.2); padding: 15px; border-radius: 10px; border-left: 3px solid var(--accent-color);"></div>
                </div>
            </div>

            <!-- Existing Reply Display (if any) -->
            <div id="existingReplySection" style="display: none; background: rgba(16, 185, 129, 0.1); padding: 15px; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3); margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; color: #10b981; text-transform: uppercase; margin-bottom: 8px;">
                    <i class="fas fa-check-circle"></i> Your Previous Reply
                </label>
                <div id="existingReplyContent" style="line-height: 1.6; color: var(--text-main);"></div>
            </div>

            <!-- Admin Reply Section -->
            <div id="replySection" style="margin-top: 20px;">
                <label style="display: block; font-size: 11px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                    <i class="fas fa-reply"></i> <span id="replyLabel">Your Response</span>
                </label>
                <textarea id="adminReplyText" placeholder="Type your response to the customer here..." style="min-height: 120px;"></textarea>
                <div class="btn-group">
                    <button onclick="sendReply()" class="primary-btn"><i class="fas fa-paper-plane"></i> Send Reply</button>
                    <button onclick="markAsRead()" id="markReadBtn" class="success-btn"><i class="fas fa-check"></i> Mark as Read</button>
                    <button onclick="closePopup()" class="secondary-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
