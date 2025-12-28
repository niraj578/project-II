<?php
session_start();
require_once 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'reply':
            $message_id = $_POST['message_id'];
            $admin_reply = $_POST['admin_reply'];
            $admin_id = $_SESSION['login']['id'];
            
            try {
                $stmt = $pdo->prepare("UPDATE chatbot_messages SET admin_reply = ?, admin_replied_by = ?, admin_replied_at = NOW(), status = 'replied' WHERE id = ?");
                $stmt->execute([$admin_reply, $admin_id, $message_id]);
                echo json_encode(['success' => true, 'message' => 'Reply sent successfully']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error sending reply']);
            }
            exit();
            
        case 'close':
            $message_id = $_POST['message_id'];
            
            try {
                $stmt = $pdo->prepare("UPDATE chatbot_messages SET status = 'closed' WHERE id = ?");
                $stmt->execute([$message_id]);
                echo json_encode(['success' => true, 'message' => 'Message closed successfully']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error closing message']);
            }
            exit();
            
        case 'get_messages':
            $status = $_POST['status'] ?? 'all';
            
            $sql = "SELECT cm.*, a.email as admin_email FROM chatbot_messages cm 
                    LEFT JOIN admin a ON cm.admin_replied_by = a.id 
                    ORDER BY cm.created_at DESC";
            
            if ($status !== 'all') {
                $sql = "SELECT cm.*, a.email as admin_email FROM chatbot_messages cm 
                        LEFT JOIN admin a ON cm.admin_replied_by = a.id 
                        WHERE cm.status = ? 
                        ORDER BY cm.created_at DESC";
            }
            
            try {
                $stmt = $pdo->prepare($sql);
                if ($status !== 'all') {
                    $stmt->execute([$status]);
                } else {
                    $stmt->execute();
                }
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'messages' => $messages]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error fetching messages']);
            }
            exit();
    }
}

// Get message statistics
try {
    $stats = [];
    
    // Total messages
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM chatbot_messages");
    $stats['total'] = $stmt->fetch()['total'];
    
    // New messages
    $stmt = $pdo->query("SELECT COUNT(*) as new FROM chatbot_messages WHERE status = 'new'");
    $stats['new'] = $stmt->fetch()['new'];
    
    // Replied messages
    $stmt = $pdo->query("SELECT COUNT(*) as replied FROM chatbot_messages WHERE status = 'replied'");
    $stats['replied'] = $stmt->fetch()['replied'];
    
    // Closed messages
    $stmt = $pdo->query("SELECT COUNT(*) as closed FROM chatbot_messages WHERE status = 'closed'");
    $stats['closed'] = $stmt->fetch()['closed'];
    
} catch (PDOException $e) {
    $stats = ['total' => 0, 'new' => 0, 'replied' => 0, 'closed' => 0];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Messages - Admin Panel</title>
    <link rel="stylesheet" href="admin_dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .chatbot-admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 2em;
        }

        .stat-card p {
            margin: 0;
            opacity: 0.9;
        }

        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: #667eea;
            color: white;
        }

        .filter-btn:not(.active) {
            background: #f0f0f0;
            color: #333;
        }

        .messages-container {
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .message-item {
            border-bottom: 1px solid #eee;
            padding: 20px;
            transition: background 0.3s;
        }

        .message-item:hover {
            background: #f8f9fa;
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .message-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9em;
            color: #666;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .status-new {
            background: #ffeb3b;
            color: #333;
        }

        .status-replied {
            background: #4caf50;
            color: white;
        }

        .status-closed {
            background: #f44336;
            color: white;
        }

        .message-content {
            margin: 10px 0;
        }

        .user-message {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .bot-response {
            background: #f3e5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .admin-reply {
            background: #e8f5e8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 4px solid #4caf50;
        }

        .reply-form {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .reply-form.active {
            display: block;
        }

        .reply-textarea {
            width: 100%;
            min-height: 80px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
            margin-bottom: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-success {
            background: #4caf50;
            color: white;
        }

        .btn-danger {
            background: #f44336;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .no-messages {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .back-btn {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .message-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .filters {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="chatbot-admin-container">
        <div class="back-btn">
            <a href="admin_dash.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <h1><i class="fas fa-comments"></i> Chatbot Messages Management</h1>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $stats['total']; ?></h3>
                <p>Total Messages</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['new']; ?></h3>
                <p>New Messages</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['replied']; ?></h3>
                <p>Replied</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['closed']; ?></h3>
                <p>Closed</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <button class="filter-btn active" data-status="all">All Messages</button>
            <button class="filter-btn" data-status="new">New</button>
            <button class="filter-btn" data-status="replied">Replied</button>
            <button class="filter-btn" data-status="closed">Closed</button>
        </div>

        <!-- Messages Container -->
        <div class="messages-container" id="messagesContainer">
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading messages...
            </div>
        </div>
    </div>

    <script>
        let currentStatus = 'all';

        // Load messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadMessages('all');
        });

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentStatus = this.dataset.status;
                loadMessages(currentStatus);
            });
        });

        function loadMessages(status) {
            const container = document.getElementById('messagesContainer');
            container.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading messages...</div>';

            const formData = new FormData();
            formData.append('action', 'get_messages');
            formData.append('status', status);

            fetch('admin_chatbot_messages.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMessages(data.messages);
                } else {
                    container.innerHTML = '<div class="no-messages">Error loading messages</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="no-messages">Error loading messages</div>';
            });
        }

        function displayMessages(messages) {
            const container = document.getElementById('messagesContainer');
            
            if (messages.length === 0) {
                container.innerHTML = '<div class="no-messages">No messages found</div>';
                return;
            }

            let html = '';
            messages.forEach(message => {
                const statusClass = `status-${message.status}`;
                const statusText = message.status.charAt(0).toUpperCase() + message.status.slice(1);
                
                html += `
                    <div class="message-item" data-id="${message.id}">
                        <div class="message-header">
                            <div class="message-meta">
                                <span><i class="fas fa-calendar"></i> ${new Date(message.created_at).toLocaleString()}</span>
                                <span><i class="fas fa-globe"></i> ${message.user_ip}</span>
                                <span><i class="fas fa-id-badge"></i> ${message.session_id}</span>
                            </div>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                        
                        <div class="message-content">
                            <div class="user-message">
                                <strong>User:</strong> ${escapeHtml(message.user_message)}
                            </div>
                            <div class="bot-response">
                                <strong>Bot Response:</strong> ${escapeHtml(message.bot_response)}
                            </div>
                            ${message.admin_reply ? `
                                <div class="admin-reply">
                                    <strong>Admin Reply:</strong> ${escapeHtml(message.admin_reply)}
                                    <br><small>Replied by: ${message.admin_email || 'Unknown'} at ${new Date(message.admin_replied_at).toLocaleString()}</small>
                                </div>
                            ` : ''}
                        </div>
                        
                        <div class="action-buttons">
                            ${message.status !== 'closed' ? `
                                <button class="btn btn-primary" onclick="toggleReplyForm(${message.id})">
                                    <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="btn btn-danger" onclick="closeMessage(${message.id})">
                                    <i class="fas fa-times"></i> Close
                                </button>
                            ` : ''}
                        </div>
                        
                        <div class="reply-form" id="replyForm${message.id}">
                            <textarea class="reply-textarea" id="replyText${message.id}" placeholder="Type your reply here..."></textarea>
                            <button class="btn btn-success" onclick="sendReply(${message.id})">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                            <button class="btn btn-secondary" onclick="toggleReplyForm(${message.id})">
                                Cancel
                            </button>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function toggleReplyForm(messageId) {
            const form = document.getElementById(`replyForm${messageId}`);
            const textarea = document.getElementById(`replyText${messageId}`);
            
            if (form.classList.contains('active')) {
                form.classList.remove('active');
            } else {
                form.classList.add('active');
                textarea.focus();
            }
        }

        function sendReply(messageId) {
            const replyText = document.getElementById(`replyText${messageId}`).value.trim();
            
            if (!replyText) {
                alert('Please enter a reply message');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'reply');
            formData.append('message_id', messageId);
            formData.append('admin_reply', replyText);

            fetch('admin_chatbot_messages.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reply sent successfully');
                    loadMessages(currentStatus);
                } else {
                    alert('Error sending reply: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error sending reply');
            });
        }

        function closeMessage(messageId) {
            if (!confirm('Are you sure you want to close this message?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'close');
            formData.append('message_id', messageId);

            fetch('admin_chatbot_messages.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Message closed successfully');
                    loadMessages(currentStatus);
                } else {
                    alert('Error closing message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error closing message');
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            loadMessages(currentStatus);
        }, 30000);
    </script>
</body>
</html>
