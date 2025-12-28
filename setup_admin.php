<?php
session_start();
require_once 'db.php';

// Check if admin table exists and create default admin
try {
    // Check if admin table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'admin'");
    if ($stmt->rowCount() == 0) {
        echo "Admin table doesn't exist. Please run the database_schema.sql file first.";
        exit();
    }
    
    // Check if any admin exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        // Create default admin account
        $email = 'admin@carrental.com';
        $password = 'admin123'; // You should change this in production
        
        $stmt = $pdo->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $password]);
        
        echo "Default admin account created successfully!<br>";
        echo "Email: admin@carrental.com<br>";
        echo "Password: admin123<br>";
        echo "<br><a href='admin_login.php'>Go to Admin Login</a>";
    } else {
        echo "Admin accounts already exist in the database.";
        echo "<br><a href='admin_login.php'>Go to Admin Login</a>";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
