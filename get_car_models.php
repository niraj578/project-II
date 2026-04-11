<?php
include 'connection.php';

header('Content-Type: application/json');

$sql = "SELECT DISTINCT model FROM cars ORDER BY model ASC";
$result = $conn->query($sql);

$models = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $models[] = $row['model'];
    }
}

echo json_encode($models);
$conn->close();
?>
