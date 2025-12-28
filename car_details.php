<?php
session_start(); // Start the session

// // Database connection (update with your own connection details)
// $servername = "localhost";
// $username = "root"; // Your database username
// $password = ""; // Your database password
// $dbname = "carrentaldb"; // Your database name

// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
include 'connection.php';
// Get car ID from URL
$carId = $_GET['id'];
$sql = "SELECT * FROM cars WHERE carid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $carId);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['name']); ?> Details</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($car['name']); ?></h1>
        <p>Model: <?php echo htmlspecialchars($car['model']); ?></p>
        <p>Year: <?php echo htmlspecialchars($car['year']); ?></p>
        <p>Price per Day: $<?php echo htmlspecialchars($car['price']); ?></p>
        <a href="store.php" class="back-button">Back to Store</a>
    </div>
</body>
</html> 