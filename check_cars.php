<?php
include 'connection.php';
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["carid"]. " - Name: " . $row["name"]. " - Model: " . $row["model"]. "\n";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
