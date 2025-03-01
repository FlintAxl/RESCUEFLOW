<?php
header('Content-Type: application/json');
require '../includes/config.php';

$data = [];

// Fetch incident data grouped by date and barangay
$sql = "SELECT DATE(reported_time) AS incident_date, b.barangay_name AS barangay, COUNT(*) as count 
        FROM incidents i
        JOIN barangays b ON i.barangay_id = b.barangay_id
        GROUP BY incident_date, b.barangay_name
        ORDER BY incident_date ASC";
$result = $conn->query($sql);

$data['incidents'] = [];
while ($row = $result->fetch_assoc()) {
    $data['incidents'][] = $row;
}

// Fetch cause data for pie chart
$sql2 = "SELECT cause, COUNT(*) AS count FROM incidents WHERE cause IS NOT NULL GROUP BY cause ORDER BY count DESC";
$result2 = $conn->query($sql2);

$data['causes'] = [];
while ($row = $result2->fetch_assoc()) {
    $data['causes'][] = $row;
}

echo json_encode($data);
?>
