<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
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

// Check if the car ID is set
if (isset($_GET['id'])) {
    $carid = $_GET['id'];

    // Fetch the car details
    $stmt = $conn->prepare("SELECT * FROM cars WHERE carid = ?");
    $stmt->bind_param("s", $carid);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();

    if (!$car) {
        echo "Car not found.";
        exit();
    }
}

// Handle form submission for updating the car
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carName = $_POST['carName'];
    $carModel = $_POST['carModel'];
    $carYear = $_POST['carYear'];
    $carPrice = $_POST['carPrice'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE cars SET name = ?, model = ?, year = ?, price = ? WHERE carid = ?");
    $stmt->bind_param("ssids", $carName, $carModel, $carYear, $carPrice, $carid);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Car updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to available cars page
    header('Location: available_cars.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Car - Admin Dashboard</title>
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8, #c9d6e3); /* Professional gradient from light to soft blue */
            font-family: Arial, sans-serif; /* Ensure a clean font */
        }

        /* Styles for the form */
        form {
            background-color: white; /* White background for the form */
            border-radius: 8px; /* Rounded corners */
            padding: 20px; /* Padding inside the form */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            max-width: 500px; /* Maximum width of the form */
            margin: 20px auto; /* Center the form */
        }

        div {
            margin-bottom: 15px; /* Space between form fields */
        }

        label {
            display: block; /* Block display for labels */
            margin-bottom: 5px; /* Space below labels */
            font-weight: bold; /* Bold labels */
        }

        input[type="text"],
        input[type="number"] {
            width: 100%; /* Full width inputs */
            padding: 10px; /* Padding inside inputs */
            border: 1px solid #ccc; /* Light border */
            border-radius: 4px; /* Rounded corners for inputs */
            box-sizing: border-box; /* Include padding in width */
        }

        button {
            background-color: #007bff; /* Button color */
            color: white; /* Button text color */
            border: none; /* No border */
            padding: 10px 15px; /* Padding for button */
            border-radius: 4px; /* Rounded corners for button */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth transition for hover */
        }

        button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        /* ... existing styles ... */
    </style>
</head>
<body>
    <h1>Update Car</h1>
    <form action="update_car.php?id=<?php echo $carid; ?>" method="POST">
        <div>
            <label for="carName">Car Name:</label>
            <input type="text" id="carName" name="carName" value="<?php echo htmlspecialchars($car['name']); ?>" required>
        </div>
        <div>
            <label for="carModel">Car Model:</label>
            <input type="text" id="carModel" name="carModel" value="<?php echo htmlspecialchars($car['model']); ?>" required>
        </div>
        <div>
            <label for="carYear">Year:</label>
            <input type="number" id="carYear" name="carYear" value="<?php echo htmlspecialchars($car['year']); ?>" required>
        </div>
        <div>
            <label for="carPrice">Price per Day (NRS):</label>
            <input type="number" id="carPrice" name="carPrice" value="<?php echo htmlspecialchars($car['price']); ?>" required>
        </div>
        <button type="submit">Update Car</button>
    </form>
</body>
</html> 