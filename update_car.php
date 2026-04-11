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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ff4b2b 0%, #ff416c 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --accent-color: #ff4b2b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030303;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Effects */
        .background-iframe-container {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;
            overflow: hidden;
        }
        .background-iframe-container iframe {
            width: 100%; height: 100%; border: none; pointer-events: none;
            transform: scale(1.1); filter: brightness(0.2) blur(10px);
        }
        .overlay-vignette {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
        }

        /* Smoky Effect */
        .smoke-container {
            position: fixed; width: 100%; height: 100%; z-index: -1;
            overflow: hidden; pointer-events: none;
        }
        .smoke {
            position: absolute; width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(255, 65, 108, 0.1) 0%, rgba(255, 65, 108, 0) 70%);
            filter: blur(80px); border-radius: 50%; opacity: 0.4;
        }
        .smoke-1 { top: -200px; left: -200px; animation: drift 30s linear infinite alternate; }
        .smoke-2 { bottom: -200px; right: -200px; animation: drift 35s linear infinite alternate-reverse; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1) rotate(0deg); }
            to { transform: translate(150px, 150px) scale(1.3) rotate(360deg); }
        }

        /* Container */
        .main-content {
            width: 95%; max-width: 700px; margin: 100px auto 40px;
            padding: 40px; background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 24px;
            position: relative; z-index: 10;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Header */
        h1 {
            font-size: 32px; font-weight: 600; margin-bottom: 30px;
            background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-transform: uppercase; letter-spacing: 1px; text-align: center;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex; align-items: center; gap: 10px;
            margin-bottom: 30px; padding: 12px 24px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: var(--text-muted); text-decoration: none;
            border-radius: 12px; font-weight: 500;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            transform: translateX(-5px);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Form Styling */
        form {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--glass-border);
            border-radius: 16px; padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block; margin-bottom: 8px;
            font-size: 13px; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 1px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%; padding: 14px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px; color: var(--text-main);
            font-family: 'Outfit', sans-serif; font-size: 15px;
            transition: all 0.3s ease; outline: none;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.1);
        }

        input[type="text"]::placeholder,
        input[type="number"]::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Button */
        button[type="submit"] {
            width: 100%; padding: 16px;
            background: var(--primary-gradient);
            border: none; border-radius: 12px;
            color: white; font-size: 16px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
            cursor: pointer; transition: all 0.3s ease;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 75, 43, 0.3);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content { width: 90%; padding: 25px; margin-top: 60px; }
            h1 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="background-iframe-container">
        <iframe src="index.php" frameborder="0"></iframe>
        <div class="smoke-container">
            <div class="smoke smoke-1"></div>
            <div class="smoke smoke-2"></div>
        </div>
        <div class="overlay-vignette"></div>
    </div>

    <div class="main-content">
        <a href="available_cars.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Cars
        </a>
        
        <h1>Update Car Details</h1>
        
        <form action="update_car.php?id=<?php echo $carid; ?>" method="POST">
            <div class="form-group">
                <label for="carName"><i class="fas fa-car"></i> Car Name</label>
                <input type="text" id="carName" name="carName" value="<?php echo htmlspecialchars($car['name']); ?>" required placeholder="e.g., Toyota Camry">
            </div>
            
            <div class="form-group">
                <label for="carModel"><i class="fas fa-tag"></i> Car Model</label>
                <input type="text" id="carModel" name="carModel" value="<?php echo htmlspecialchars($car['model']); ?>" required placeholder="e.g., 2024 Hybrid">
            </div>
            
            <div class="form-group">
                <label for="carYear"><i class="fas fa-calendar"></i> Year</label>
                <input type="number" id="carYear" name="carYear" value="<?php echo htmlspecialchars($car['year']); ?>" required placeholder="e.g., 2024">
            </div>
            
            <div class="form-group">
                <label for="carPrice"><i class="fas fa-rupee-sign"></i> Price per Day (NRS)</label>
                <input type="number" id="carPrice" name="carPrice" value="<?php echo htmlspecialchars($car['price']); ?>" required placeholder="e.g., 5000">
            </div>
            
            <button type="submit">
                <i class="fas fa-save"></i> Update Car
            </button>
        </form>
    </div>
</body>
</html> 