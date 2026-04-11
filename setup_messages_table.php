<?php
// Script to create the messages table
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carrental";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully to database: $dbname\n\n";

// Create messages table
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    admin_reply TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'messages' created successfully (or already exists)\n";
} else {
    echo "✗ Error creating table: " . $conn->error . "\n";
}

// Create indexes
$indexes = [
    "CREATE INDEX IF NOT EXISTS idx_messages_status ON messages(status)",
    "CREATE INDEX IF NOT EXISTS idx_messages_created_at ON messages(created_at)"
];

foreach ($indexes as $index_sql) {
    if ($conn->query($index_sql) === TRUE) {
        echo "✓ Index created successfully\n";
    } else {
        // Ignore error if index already exists
        if (strpos($conn->error, 'Duplicate key name') === false) {
            echo "✗ Error creating index: " . $conn->error . "\n";
        }
    }
}

echo "\n✓ Database setup complete!\n";
echo "You can now use the messages feature in the admin panel.\n";

$conn->close();
?>
