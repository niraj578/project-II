<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// Database connection
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "carrentaldb"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carid = $_POST['carid'];
    $carName = $_POST['carName'];
    $carModel = $_POST['carModel'];
    $carYear = $_POST['carYear'];
    $carPrice = $_POST['carPrice'];

    // Handle file upload
    $targetDir = "uploads/"; // Directory to save uploaded images

    // Check if the uploads directory exists, if not, create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true); // Create the directory with permissions
    }

    $targetFile = $targetDir . basename($_FILES["carImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["carImage"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["carImage"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["carImage"]["tmp_name"], $targetFile)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO cars (carid, name, model, year, price, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $carid, $carName, $carModel, $carYear, $carPrice, $targetFile);

            // Execute the statement
            if ($stmt->execute()) {
                echo "New car added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car - CAR RENTAL SERVICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #e0f7fa, #80deea); /* Professional gradient from light cyan to teal */
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif; /* Ensure a clean font */
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px; /* Limit the width of the form */
        }

        form div {
            margin-bottom: 15px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        form input:focus {
            border-color: #007BFF; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%; /* Make button full width */
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Add a Car</h1>
    <form action="add_car.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="carid">Car ID:</label>
            <input type="text" id="carid" name="carid" required>
        </div>
        <div>
            <label for="carName">Car Name:</label>
            <input type="text" id="carName" name="carName" required>
        </div>
        <div>
            <label for="carModel">Car Model:</label>
            <input type="text" id="carModel" name="carModel" required>
        </div>
        <div>
            <label for="carYear">Year:</label>
            <input type="number" id="carYear" name="carYear" required>
        </div>
        <div>
            <label for="carPrice">Price per Day (NRS):</label>
            <input type="number" id="carPrice" name="carPrice" required>
        </div>
        <div>
            <label for="carImage">Car Image:</label>
            <input type="file" id="carImage" name="carImage" accept="image/*" required>
        </div>
        <button type="submit">Add Car</button>
    </form>
    <div class="message">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "New car added successfully.";
        }
        ?>
    </div>
</body>
</html> 