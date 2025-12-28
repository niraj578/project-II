<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// // Database connection
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
// Initialize search results
$searchResults = [];
$searchQuery = "";

// Handle search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST['search_query'];
    $sql = "SELECT * FROM cars WHERE name LIKE ? OR model LIKE ? OR year LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
    $stmt->close();
}

// Fetch available cars from the database
$sql = "SELECT * FROM cars"; // Adjust this query based on your actual table name
$result = $conn->query($sql);

// Assuming you have a connection to the database
$isLoggedIn = isset($_SESSION['login']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    $carId = $_POST['carid']; // Get the car ID from the form
    $userId = $_SESSION['login']['id']; // Assuming user ID is stored in session

    // Insert booking into the database
    $stmt = $conn->prepare("INSERT INTO bookings (id, carid) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $carId);
    $stmt->execute();

    // Redirect or show a success message
    header("Location: my_bookings.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['login']['full_name']; // Assuming full_name is stored in session
$userEmail = $_SESSION['login']['email']; // Get email from session
$userPhone = $_SESSION['login']['phone_number']; // Get phone from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service - Store</title>
    <link rel="stylesheet" href="style.css"> <!-- Ensure your CSS file is linked -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ff7e5f, #feb47b); /* Gradient background */
            color: #333;
        }
        h1, h2 {
            text-align: center;
            color: #fff; /* Change text color for better contrast */
        }
        .car-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px auto;
            width: 80%;
        }
        .car-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }
        .car-item {
            flex: 1;
            margin: 0 10px;
            padding: 15px;
            border: 1px solid #ddd; /* Optional: Add border for better visibility */
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .car-item h3 {
            margin: 0 0 10px;
        }
        .book-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .book-btn:hover {
            background-color: #45a049;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 0 auto;
            padding: 25px;
            border: 1px solid #888;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 10px;
            position: relative;
            transform: translateY(-10%);
        }
        #bookingForm {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        #bookingForm label {
            margin-bottom: 0;
            font-weight: bold;
        }
        #bookingForm input {
            padding: 8px;
            margin-bottom: 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        #bookingForm button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        #bookingForm button:hover {
            background-color: #45a049;
        }
        .close {
            position: absolute;
            right: 15px;
            top: 10px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            margin-left: 20px;
            color: #fff;
            text-decoration: none;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            text-align: center;
        }
        .back-button:hover {
            background-color: #218838;
        }
        /* Image Styles */
        .image-container {
            display: flex;
            justify-content: center; /* Center images */
            margin: 20px; /* Space around images */
        }
        .image-container img {
            width: 100%; /* Responsive width */
            max-width: 300px; /* Maximum width for images */
            border: 5px solid #4CAF50; /* Green border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Shadow effect */
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for effects */
        }
        .image-container img:hover {
            transform: scale(1.05); /* Slightly enlarge on hover */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5); /* Darker shadow on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-button">Back to Home</a>
        <h1>Available Cars</h1>
        <h2>Available Cars</h2>
        <div class="car-list">
            <?php if (!empty($searchResults)): ?>
                <?php foreach ($searchResults as $car): ?>
                    <div class="car-item">
                        <h3><?php echo htmlspecialchars($car['name']); ?></h3>
                        <p>Model: <?php echo htmlspecialchars($car['model']); ?></p>
                        <p>Year: <?php echo htmlspecialchars($car['year']); ?></p>
                        <p>Price per Day: NRS <?php echo htmlspecialchars($car['price']); ?></p>
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
                        </div>
                        <a href="car_details.php?id=<?php echo $car['carid']; ?>" class="book-btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php if ($result->num_rows > 0): ?>
                    <?php $count = 0; ?>
                    <div class="car-row">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="car-item">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p>Model: <?php echo htmlspecialchars($row['model']); ?></p>
                                <p>Year: <?php echo htmlspecialchars($row['year']); ?></p>
                                <p>Price per Day: NRS <?php echo htmlspecialchars($row['price']); ?></p>
                                <div class="image-container">
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </div>
                                <button class="book-btn" data-carid="<?php echo $row['carid']; ?>"
                                    data-carname="<?php echo htmlspecialchars($row['name']); ?>" data-carmodel="<?php
                                    echo htmlspecialchars($row['model']); ?>" data-carprice="<?php echo htmlspecialchars($row['price']);
                                    ?>">Book Now</button>
                            </div>
                            <?php $count++; ?>
                            <?php if ($count % 3 == 0): ?>
                                </div><div class="car-row">
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No cars available at the moment.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- The Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <span class="close">&times;</span>
            <h2>Book Car</h2>
            <form id="bookingForm" action="add_booking.php" method="POST">
                <input type="hidden" name="carid" id="carid" value="">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly></label><br>
                <label>Phone Number: <input type="tel" name="phone" value="<?php echo htmlspecialchars($userPhone); ?>" readonly></label><br>
                <label>Pickup Location: <input type="text" name="pickup_location" required></label><br>
                <label>Drop-off Location: <input type="text" name="dropoff_location" required></label><br>
                <label>Booking From: <input type="date" name="booking_from" id="booking_from" required min="<?php echo date('Y-m-d'); ?>"></label><br>
                <label>Booking To: <input type="date" name="booking_to" id="booking_to" required></label><br>
                <label>Booking Time: <input type="time" name="booking_time" required></label><br>
                <button type="submit">Confirm Booking</button>
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("bookingModal");
        modal.style.display = "none"; // Ensure the modal is hidden by default

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Get all the "Book Now" buttons
        var bookButtons = document.querySelectorAll('.book-btn');

        // When the user clicks on a button, open the modal and fill in the car details
        bookButtons.forEach(function(button) {
            button.onclick = function() { 
                var carId = this.getAttribute('data-carid');
                var carName = this.getAttribute('data-carname');
                var carModel = this.getAttribute('data-carmodel');
                var carPrice = this.getAttribute('data-carprice');

                // Set the car ID in the form
                document.getElementById('carid').value = carId;

                // Open the modal
                modal.style.display = "block";
            };
        });

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>