<?php
session_start();
include('./includes/config.php');
include('./includes/check_admin.php');

if (!isset($conn)) {
    die("Database connection failed.");
}

$sql = "SELECT * FROM dispatches WHERE status_id != 3 ORDER BY dispatched_at DESC"; 
$result = $conn->query($sql);

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5"> <!-- Refresh page every 5 seconds -->
    <title>Dispatch Locations</title>
</head>
<body>
    <h2>Saved Dispatch Locations</h2>

    <?php if (empty($locations)): ?>
        <p style="color: green;">The incident has been resolved.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($locations as $location): ?>
                <li>
                    <strong><?php echo htmlspecialchars($location['location']); ?></strong> 
                    (Submitted on <?php echo $location['dispatched_at']; ?>)
                    <br>
                    <iframe width="100%" height="300" src="https://maps.google.com/maps?q=<?php echo urlencode($location['location']); ?>&output=embed"></iframe>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
