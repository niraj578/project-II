<?php
/**
 * Booking Algorithm to check car availability
 * 
 * @param mysqli $conn Database connection
 * @param string $carId ID of the car
 * @param string $bookingFrom Start date of the new booking
 * @param string $bookingTo End date of the new booking
 * @return bool True if available, False if not available
 */
function isCarAvailable($conn, $carId, $bookingFrom, $bookingTo) {
    $overlapCount = 0;
    
    // Check Availability Algorithm Query
    $checkStmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM bookings 
        WHERE carid = ? 
        AND status IN ('pending', 'approved') 
        AND (booking_from <= ? AND booking_to >= ?)
    ");
    $checkStmt->bind_param("sss", $carId, $bookingTo, $bookingFrom);
    $checkStmt->execute();
    $checkStmt->bind_result($overlapCount);
    $checkStmt->fetch();
    $checkStmt->close();

    return $overlapCount == 0;
}
?>
