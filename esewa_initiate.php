<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Check if there is a pending booking
if (!isset($_SESSION['pending_payment_booking_id'])) {
    header("Location: dashboard.php");
    exit();
}

$bookingId = $_SESSION['pending_payment_booking_id'];
$amount = $_SESSION['pending_payment_amount']; 
$amount = preg_replace("/[^0-9.]/", "", $amount);

// For eSewa, we need integer or decimal float.
$amount = floatval($amount);
$tax_amount = 0;
$total_amount = $amount + $tax_amount;
$product_delivery_charge = 0;
$product_service_charge = 0;

$transaction_uuid = "Booking_" . $bookingId . "_" . time();
$product_code = "EPAYTEST";
$secret_key = "8gBm/:&EnhH.1/q";

// Signature calculation
$signed_field_names = "total_amount,transaction_uuid,product_code";
$message = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";
$signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));

$base_url = "http://localhost/carrental/"; 
$success_url = $base_url . "esewa_success.php";
$failure_url = $base_url . "booking_payment.php?error=payment_failed";

// Update database with the transaction_uuid 
$update_sql = "UPDATE bookings SET tidx = ? WHERE id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("si", $transaction_uuid, $bookingId);
$stmt->execute();
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa</title>
</head>
<body onload="document.forms[0].submit()">
    <p>Redirecting to eSewa. Please wait...</p>
    <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" style="display: none;">
        <input type="text" id="amount" name="amount" value="<?php echo $amount; ?>" required>
        <input type="text" id="tax_amount" name="tax_amount" value="<?php echo $tax_amount; ?>" required>
        <input type="text" id="total_amount" name="total_amount" value="<?php echo $total_amount; ?>" required>
        <input type="text" id="transaction_uuid" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>" required>
        <input type="text" id="product_code" name="product_code" value="<?php echo $product_code; ?>" required>
        <input type="text" id="product_service_charge" name="product_service_charge" value="<?php echo $product_service_charge; ?>" required>
        <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="<?php echo $product_delivery_charge; ?>" required>
        <input type="text" id="success_url" name="success_url" value="<?php echo $success_url; ?>" required>
        <input type="text" id="failure_url" name="failure_url" value="<?php echo $failure_url; ?>" required>
        <input type="text" id="signed_field_names" name="signed_field_names" value="<?php echo $signed_field_names; ?>" required>
        <input type="text" id="signature" name="signature" value="<?php echo $signature; ?>" required>
    </form>
</body>
</html>
