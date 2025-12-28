<?php
// // Database connection parameters
// $servername = "localhost"; // Change if necessary
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

// $error = ''; // Initialize an error variable

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Check the contents of the $_POST array
    // var_dump($_POST); // Uncomment this line to see the contents of $_POST

    // Get form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    // Validate passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }
    
    // Check if the phone key exists in the POST array
    if (isset($phone_number)) {
        // Validate phone number
        if (!preg_match('/^\d{10}$/', $phone_number)) {
            
            $error = 'Invalid phone number. Please enter a 10-digit number.'; // Set the error message
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone_number, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $phone_number, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                // Registration successful, show alert and redirect
                // echo"im here";
                echo "<script>
                        alert('Registered successfully!');
                        window.location.href = 'login.php';
                      </script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement and connection
            $stmt->close();
        }
    } else {
        $error = 'Phone number is required.'; // Set error if phone is not provided
    }
}

$conn->close();
?>