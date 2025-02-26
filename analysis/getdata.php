<?php
header('Content-Type: application/json');
require '../includes/config.php';

// Fetch incident data grouped by date and location
$sql = "SELECT DATE(reported_time) AS incident_date, location, COUNT(*) as count 
        FROM incidents 
        GROUP BY incident_date, location 
        ORDER BY incident_date ASC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
