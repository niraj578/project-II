<?php
session_start();
require_once 'db.php';

echo "<h2>Debug Admin Messages</h2>";

// Check admin login
if (!isset($_SESSION['login'])) {
    echo "<p style='color: red;'>❌ Not logged in as admin. <a href='admin_login.php'>Login here</a></p>";
    exit();
}

echo "<p style='color: green;'>✅ Logged in as admin: " . $_SESSION['login']['email'] . "</p>";

try {
    // Test the exact query used in admin panel
    $sql = "SELECT cm.*, a.email as admin_email FROM chatbot_messages cm 
            LEFT JOIN admin a ON cm.admin_replied_by = a.id 
            ORDER BY cm.created_at DESC";
    
    echo "<h3>Testing Query:</h3>";
    echo "<code>" . htmlspecialchars($sql) . "</code><br><br>";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Query Results:</h3>";
    echo "Number of messages found: " . count($messages) . "<br><br>";
    
    if (count($messages) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr>";
        echo "<th>ID</th><th>User Message</th><th>Bot Response</th><th>Status</th>";
        echo "<th>Admin Reply</th><th>Created At</th>";
        echo "</tr>";
        
        foreach ($messages as $msg) {
            echo "<tr>";
            echo "<td>" . $msg['id'] . "</td>";
            echo "<td>" . htmlspecialchars(substr($msg['user_message'], 0, 30)) . "...</td>";
            echo "<td>" . htmlspecialchars(substr($msg['bot_response'], 0, 30)) . "...</td>";
            echo "<td>" . $msg['status'] . "</td>";
            echo "<td>" . ($msg['admin_reply'] ? htmlspecialchars(substr($msg['admin_reply'], 0, 20)) . "..." : 'No reply') . "</td>";
            echo "<td>" . $msg['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No messages found in database.</p>";
        echo "<p>This could mean:</p>";
        echo "<ul>";
        echo "<li>The chatbot_messages table is empty</li>";
        echo "<li>No one has used the chatbot yet</li>";
        echo "<li>There's an issue with message saving</li>";
        echo "</ul>";
        echo "<p><strong>To test:</strong> Go to your main website, open the chatbot, and send a message.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}

echo "<br><hr>";
echo "<p><a href='test_chatbot_db.php'>Run Full Database Test</a> | ";
echo "<a href='admin_chatbot_messages.php'>Go to Admin Panel</a> | ";
echo "<a href='index.php'>Test Chatbot</a></p>";
?>
