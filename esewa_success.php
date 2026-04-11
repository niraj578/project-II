<?php
session_start();
include 'connection.php';

if (!isset($_GET['data'])) {
    header("Location: dashboard.php");
    exit();
}

$data = json_decode(base64_decode($_GET['data']), true);

if (!$data) {
    echo "Invalid data received from eSewa.";
    echo "<br><a href='booking_payment.php'>Go Back</a>";
    exit();
}

$transaction_code = $data['transaction_code'] ?? '';
$status = $data['status'] ?? '';
$total_amount = $data['total_amount'] ?? '';
$transaction_uuid = $data['transaction_uuid'] ?? '';
$product_code = $data['product_code'] ?? '';

if ($status === 'COMPLETE') {
    // Extract booking ID from transaction_uuid (e.g., "Booking_123_1612...")
    $parts = explode('_', $transaction_uuid);
    $bookingId = intval($parts[1]);
    
    // Verify using eSewa API
    $verification_url = "https://rc-epay.esewa.com.np/api/epay/transaction/status/?product_code=EPAYTEST&total_amount=$total_amount&transaction_uuid=$transaction_uuid";
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $verification_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    
    $verification = json_decode($response, true);
    
    if (isset($verification['status']) && $verification['status'] === 'COMPLETE') {
        if ($bookingId) {
            $sql = "UPDATE bookings SET transaction_id = ?, esewa_status = 'Completed' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $transaction_code, $bookingId);
            
            if ($stmt->execute()) {
                 header("Location: complete_booking.php?payment_success=1&transaction_id=" . $transaction_code);
                 exit();
            } else {
                 echo "Error updating database: " . $conn->error;
            }
        } else {
            echo "Could not identify booking ID.";
        }
    } else {
        echo "Payment verification failed.";
        echo "<br><a href='booking_payment.php'>Try Again</a>";
    }
} else {
    echo "Payment was not completed.";
    echo "<br><a href='booking_payment.php'>Try Again</a>";
}
$conn->close();
?>
