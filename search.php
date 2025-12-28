<?php
session_start(); // Start the session

// // Include database connection
// $servername = "localhost"; // Your database server
// $username = "root"; // Your database username
// $password = ""; // Your database password
// $dbname = "carrentaldb"; // Your database name

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Search</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    
        <div class="results">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $searchQuery = htmlspecialchars($_POST['search_query']);
                
                // Prepare and execute the search query
                $sql = "SELECT * FROM cars WHERE name LIKE ?";
                $stmt = $conn->prepare($sql);
                $searchTerm = "%" . $searchQuery . "%";
                $stmt->bind_param("s", $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                // Display results
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='car-result'>";
                        echo "<h3>" . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['model']) . ")</h3>";
                        echo "<p>Year: " . htmlspecialchars($row['year']) . "</p>";
                        echo "<p>Price: $" . htmlspecialchars($row['price']) . "/day</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No cars found.</p>";
                }

                $stmt->close();
            }
            ?>
        </div>
    </div>

    
    <!-- Close the database connection -->
    <?php $conn->close(); ?>
</body>
</html> 