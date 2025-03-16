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



// Handle Emergency Submission
if (isset($_POST['submit_emergency_info'])) {
    $what = mysqli_real_escape_string($conn, $_POST['what']);
    $where = mysqli_real_escape_string($conn, $_POST['where']);
    $why = mysqli_real_escape_string($conn, $_POST['why']);
    $caller_name = mysqli_real_escape_string($conn, $_POST['caller_name']);
    $caller_phone = mysqli_real_escape_string($conn, $_POST['caller_phone']);

    // Insert emergency report
    $sql = "INSERT INTO emergency_details (what, `where`, `why`, caller_name, caller_phone) 
            VALUES ('$what', '$where', '$why', '$caller_name', '$caller_phone')";

if (mysqli_query($conn, $sql)) {
    // Get the last inserted emergency report ID
    $emergency_id = mysqli_insert_id($conn);

    // Insert a notification with all details
    $notif_msg = "New emergency report: What - $what, Where - $where, Why - $why, Caller - $caller_name, Phone - $caller_phone.";
    $notif_sql = "INSERT INTO notifications (message, dispatch_id, what, `where`, `why`, caller_name, caller_phone) 
                  VALUES ('$notif_msg', '$emergency_id', '$what', '$where', '$why', '$caller_name', '$caller_phone')";
    mysqli_query($conn, $notif_sql);
}
 else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}



$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Locations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center text-primary">Submit Emergency Details</h2>
            <form method="POST" class="mt-3">
                <div class="mb-3">
                    <label for="what" class="form-label">What happened?</label>
                    <textarea class="form-control" name="what" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="where" class="form-label">Where did it happen?</label>
                    <textarea class="form-control" name="where" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="why" class="form-label">Why is this an emergency?</label>
                    <textarea class="form-control" name="why" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="caller_name" class="form-label">Caller Name</label>
                    <input type="text" class="form-control" name="caller_name" required>
                </div>
                <div class="mb-3">
                    <label for="caller_phone" class="form-label">Caller Phone</label>
                    <input type="text" class="form-control" name="caller_phone" required>
                </div>
                <button type="submit" name="submit_emergency_info" class="btn btn-primary w-100">Submit Report</button>
            </form>
        </div>
        
        <div class="card shadow p-4 mt-4">
            <h2 class="text-center text-success">Saved Dispatch Locations</h2>
            <?php if (empty($locations)): ?>
                <p class="text-center text-muted">The incident has been resolved.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($locations as $location): ?>
                        <li class="list-group-item">
                            <strong><?php echo htmlspecialchars($location['location']); ?></strong>
                            (Submitted on <?php echo $location['dispatched_at']; ?>)
                            <br>
                            <iframe width="100%" height="300" class="mt-2" src="https://maps.google.com/maps?q=<?php echo urlencode($location['location']); ?>&output=embed"></iframe>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>