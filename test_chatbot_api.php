<?php
// Simple test for chatbot API
require_once 'db.php';

echo "<h2>Chatbot API Test</h2>";

// Test 1: Check database connection
echo "<h3>1. Database Connection Test</h3>";
try {
    $stmt = $pdo->query("SELECT 1");
    echo "✅ Database connection working<br>";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    exit();
}

// Test 2: Check if chatbot_messages table exists
echo "<h3>2. Table Check</h3>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'chatbot_messages'");
    if ($stmt->rowCount() > 0) {
        echo "✅ chatbot_messages table exists<br>";
    } else {
        echo "❌ chatbot_messages table does NOT exist<br>";
        echo "You need to run the database_schema.sql file<br>";
        exit();
    }
} catch (PDOException $e) {
    echo "❌ Error checking table: " . $e->getMessage() . "<br>";
    exit();
}

// Test 3: Test chatbot API directly
echo "<h3>3. Chatbot API Test</h3>";
echo "<form method='post'>";
echo "<input type='text' name='test_message' placeholder='Type a test message' value='Hello, this is a test'>";
echo "<button type='submit' name='test_api'>Send Test Message</button>";
echo "</form>";

if (isset($_POST['test_api'])) {
    $test_message = $_POST['test_message'];
    
    // Simulate the chatbot API call
    session_start();
    
    // Include the chatbot class
    require_once 'chatbot_api.php';
    
    echo "<h4>Test Results:</h4>";
    
    // Test database insert directly
    try {
        $user_message = $test_message;
        $bot_response = "This is a test response from the bot.";
        $user_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $session_id = session_id() ?: 'test_session_' . time();
        
        $stmt = $pdo->prepare("INSERT INTO chatbot_messages (user_message, bot_response, user_ip, session_id) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$user_message, $bot_response, $user_ip, $session_id]);
        
        if ($result) {
            echo "✅ Message inserted successfully!<br>";
            echo "Message ID: " . $pdo->lastInsertId() . "<br>";
            echo "User Message: " . htmlspecialchars($user_message) . "<br>";
            echo "Bot Response: " . htmlspecialchars($bot_response) . "<br>";
            echo "Session ID: " . $session_id . "<br>";
            echo "IP: " . $user_ip . "<br>";
        } else {
            echo "❌ Failed to insert message<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Database error: " . $e->getMessage() . "<br>";
    }
}

// Test 4: Check current messages
echo "<h3>4. Current Messages in Database</h3>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM chatbot_messages");
    $result = $stmt->fetch();
    echo "Total messages: " . $result['count'] . "<br>";
    
    if ($result['count'] > 0) {
        echo "<h4>Latest 5 Messages:</h4>";
        $stmt = $pdo->query("SELECT * FROM chatbot_messages ORDER BY created_at DESC LIMIT 5");
        $messages = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>User Message</th><th>Bot Response</th><th>Status</th><th>Created At</th></tr>";
        foreach ($messages as $msg) {
            echo "<tr>";
            echo "<td>" . $msg['id'] . "</td>";
            echo "<td>" . htmlspecialchars(substr($msg['user_message'], 0, 30)) . "...</td>";
            echo "<td>" . htmlspecialchars(substr($msg['bot_response'], 0, 30)) . "...</td>";
            echo "<td>" . $msg['status'] . "</td>";
            echo "<td>" . $msg['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "❌ Error reading messages: " . $e->getMessage() . "<br>";
}

echo "<br><hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>If the test above works, try using the actual chatbot on your website</li>";
echo "<li>If it doesn't work, check your database setup</li>";
echo "<li>Make sure you've run the database_schema.sql file</li>";
echo "</ol>";
echo "<p><a href='index.php'>Go to Website</a> | <a href='admin_chatbot_messages.php'>Admin Panel</a></p>";
?>
