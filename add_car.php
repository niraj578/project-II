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
    $carType = $_POST['carType'];

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
            $stmt = $conn->prepare("INSERT INTO cars (carid, name, model, year, price, image, type) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $carid, $carName, $carModel, $carYear, $carPrice, $targetFile, $carType);

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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ff4b2b 0%, #ff416c 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --input-bg: rgba(255, 255, 255, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030303;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Background Effects */
        .background-iframe-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
        }

        .background-iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            pointer-events: none;
            transform: scale(1.1);
            filter: brightness(0.2) blur(10px);
        }

        .overlay-vignette {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
        }

        .main-content {
            width: 95%;
            max-width: 800px;
            padding: 50px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            position: relative;
            z-index: 10;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-btn:hover {
            color: var(--text-main);
            transform: translateX(-5px);
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .full-width {
            grid-column: span 2;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
            margin-left: 5px;
        }

        input, select {
            background: var(--input-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 14px 18px;
            color: white;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #ff4b2b;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(255, 75, 43, 0.1);
        }

        input[type="file"] {
            padding: 10px;
            cursor: pointer;
        }

        button {
            grid-column: span 2;
            padding: 16px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 10px 20px rgba(255, 65, 108, 0.2);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 65, 108, 0.4);
        }

        .message {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 15px 25px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            z-index: 100;
            animation: slideIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <header>
            <h1>Add Vehicle</h1>
            <a href="admin_dash.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </header>

        <form action="add_car.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="carid">Car ID</label>
                <input type="text" id="carid" name="carid" placeholder="e.g. CAR001" required>
            </div>
            <div class="form-group">
                <label for="carName">Car Name</label>
                <input type="text" id="carName" name="carName" placeholder="e.g. Toyota Prado" required>
            </div>
            <div class="form-group">
                <label for="carModel">Car Model</label>
                <input type="text" id="carModel" name="carModel" placeholder="e.g. VX" required>
            </div>
            <div class="form-group">
                <label for="carYear">Year</label>
                <input type="number" id="carYear" name="carYear" placeholder="e.g. 2024" required>
            </div>
            <div class="form-group">
                <label for="carPrice">Price per Day (NRS)</label>
                <input type="number" id="carPrice" name="carPrice" placeholder="e.g. 5000" required>
            </div>
            <div class="form-group">
                <label for="carType">Car Type</label>
                <select id="carType" name="carType" required>
                    <option value="" disabled selected>Select type</option>
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Hatchback">Hatchback</option>
                    <option value="Luxury">Luxury</option>
                    <option value="Vintage">Vintage</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label for="carImage">Vehicle Image</label>
                <input type="file" id="carImage" name="carImage" accept="image/*" required>
            </div>
            <button type="submit">Submit to Fleet</button>
        </form>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="message <?php echo $uploadOk ? 'success' : 'error'; ?>">
            <i class="fas <?php echo $uploadOk ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php
            if ($uploadOk) {
                echo "Success: New vehicle added to the fleet.";
            } else {
                echo "Error: Could not add vehicle. Please check file format.";
            }
            ?>
        </div>
    <?php endif; ?>
</body>
</html>