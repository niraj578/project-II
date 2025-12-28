<?php
session_start();
require_once 'db.php';

echo "<h2>Chatbot Database Test</h2>";

try {
    // Test database connection
    echo "<h3>1. Database Connection Test</h3>";
    echo "✅ Database connection successful<br><br>";
    
    // Check if chatbot_messages table exists
    echo "<h3>2. Table Existence Check</h3>";
    $stmt = $pdo->query("SHOW TABLES LIKE 'chatbot_messages'");
    if ($stmt->rowCount() > 0) {
        echo "✅ chatbot_messages table exists<br>";
    } else {
        echo "❌ chatbot_messages table does NOT exist<br>";
        echo "You need to run the database_schema.sql file<br><br>";
    }
    
    // Check if admin table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'admin'");
    if ($stmt->rowCount() > 0) {
        echo "✅ admin table exists<br>";
    } else {
        echo "❌ admin table does NOT exist<br>";
        echo "You need to run the database_schema.sql file<br>";
    }
    echo "<br>";
    
    // Check current messages in chatbot_messages table
    echo "<h3>3. Current Messages in Database</h3>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM chatbot_messages");
        $result = $stmt->fetch();
        echo "Total messages in database: " . $result['count'] . "<br>";
        
        if ($result['count'] > 0) {
            echo "<h4>Recent Messages:</h4>";
            $stmt = $pdo->query("SELECT id, user_message, bot_response, status, created_at FROM chatbot_messages ORDER BY created_at DESC LIMIT 5");
            $messages = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>User Message</th><th>Bot Response</th><th>Status</th><th>Created At</th></tr>";
            foreach ($messages as $msg) {
                echo "<tr>";
                echo "<td>" . $msg['id'] . "</td>";
                echo "<td>" . htmlspecialchars(substr($msg['user_message'], 0, 50)) . "...</td>";
                echo "<td>" . htmlspecialchars(substr($msg['bot_response'], 0, 50)) . "...</td>";
                echo "<td>" . $msg['status'] . "</td>";
                echo "<td>" . $msg['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No messages found in database.<br>";
            echo "<strong>To test:</strong> Go to your website, open the chatbot, and send a message.<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error reading messages: " . $e->getMessage() . "<br>";
    }
    
    echo "<br>";
    
    // Test admin session
    echo "<h3>4. Admin Session Check</h3>";
    if (isset($_SESSION['login'])) {
        echo "✅ Admin session is active<br>";
        echo "Admin ID: " . $_SESSION['login']['id'] . "<br>";
        echo "Admin Email: " . $_SESSION['login']['email'] . "<br>";
    } else {
        echo "❌ No admin session found<br>";
        echo "<a href='admin_login.php'>Login as admin first</a><br>";
    }
    
    echo "<br>";
    
    // Test chatbot API
    echo "<h3>5. Chatbot API Test</h3>";
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='test_message' value='1'>";
    echo "<input type='text' name='test_msg' placeholder='Test message' value='Hello, this is a test message'>";
    echo "<button type='submit'>Send Test Message</button>";
    echo "</form>";
    
    if (isset($_POST['test_message']) && isset($_POST['test_msg'])) {
        $test_msg = $_POST['test_msg'];
        
        // Simulate chatbot API call
        $test_response = "This is a test response to: " . $test_msg;
        
        // Insert test message
        try {
            $stmt = $pdo->prepare("INSERT INTO chatbot_messages (user_message, bot_response, user_ip, session_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$test_msg, $test_response, '127.0.0.1', 'test_session_' . time()]);
            echo "✅ Test message inserted successfully!<br>";
            echo "<a href='admin_chatbot_messages.php'>Go to Admin Panel to see the message</a><br>";
        } catch (PDOException $e) {
            echo "❌ Error inserting test message: " . $e->getMessage() . "<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

echo "<br><hr>";
echo "<h3>Quick Links:</h3>";
echo "<a href='admin_login.php'>Admin Login</a> | ";
echo "<a href='admin_chatbot_messages.php'>Chatbot Messages Panel</a> | ";
echo "<a href='index.php'>Main Website (to test chatbot)</a>";
?>
