<?php
// Rule-based Recommendation Algorithm
// Recommends cars based on:
// 1. User's previous booking history (Preferred Type).
// 2. If no history, defaults to displaying all or specific featured cars.

function getRecommendations($conn, $userEmail) {
    $recommendations = [];
    $reason = "";

    // Priority 1: Session Preference (Freshly selected in popup)
    if (isset($_SESSION['preferred_model'])) {
        $prefModel = $_SESSION['preferred_model'];
        $sql_pref = "SELECT * FROM cars WHERE model = ?";
        $stmt_pref = $conn->prepare($sql_pref);
        $stmt_pref->bind_param("s", $prefModel);
        $stmt_pref->execute();
        $res_pref = $stmt_pref->get_result();
        
        while($car = $res_pref->fetch_assoc()) {
            $recommendations[] = $car;
        }
        $stmt_pref->close();

        if (!empty($recommendations)) {
            return ["cars" => $recommendations, "reason" => "Because you're interested in " . $prefModel];
        }
    }

    // Priority 2: Booking History
    // Find the most frequently booked car type by this user
    $sql_history = "SELECT c.type, COUNT(*) as count 
                    FROM bookings b 
                    JOIN cars c ON b.carid = c.carid 
                    WHERE b.email = ? 
                    GROUP BY c.type 
                    ORDER BY count DESC 
                    LIMIT 1";
    
    $stmt = $conn->prepare($sql_history);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $preferredType = null;
    if ($row = $result->fetch_assoc()) {
        $preferredType = $row['type'];
    }

    if ($preferredType) {
        // User has a preference, recommend cars of that type
        $sql_rec = "SELECT * FROM cars WHERE type = ?";
        $stmt_rec = $conn->prepare($sql_rec);
        $stmt_rec->bind_param("s", $preferredType);
        $stmt_rec->execute();
        $res_rec = $stmt_rec->get_result();
        while($car = $res_rec->fetch_assoc()) {
            $recommendations[] = $car;
        }
        
        $reason = "Because you booked " . $preferredType . " previously.";
    } else {
        // Priority 3: Default (Cheapest)
        // Let's recommend the top 3 cheapest cars for new users
        $sql_cheap = "SELECT * FROM cars ORDER BY price ASC LIMIT 3";
        $res_cheap = $conn->query($sql_cheap);
        while($car = $res_cheap->fetch_assoc()) {
            $recommendations[] = $car;
        }
        $reason = "Popular budget-friendly choices for new customers.";
    }
    
    return ["cars" => $recommendations, "reason" => $reason];
}
?>
