<?php
session_start();
ob_start(); // Start output buffering

include('../includes/config.php');
include('../includes/check_admin.php');

if (!isset($conn)) {
    die("Database connection failed.");
}

// Mark notifications as read when clicked
if (isset($_GET['mark_as_read'])) {
    $notif_id = intval($_GET['mark_as_read']);
    $update_sql = "UPDATE notifications SET status = 1 WHERE id = $notif_id";

    if ($conn->query($update_sql)) {
        $_SESSION['success_message'] = "Notification marked as read successfully!";
    } else {
        $_SESSION['error_message'] = "Error marking notification as read.";
    }

    // Redirect to avoid reloading the same request
    header("Location: notif.php");
    exit();
}

// Fetch unread notifications
$sql = "SELECT n.id, n.message, e.what, e.`where`, e.why, e.caller_name, e.caller_phone, n.created_at 
        FROM notifications n
        JOIN emergency_details e ON n.dispatch_id = e.id
        WHERE n.status = 0
        ORDER BY n.created_at DESC";
$result = $conn->query($sql);



ob_end_flush(); // Flush output buffer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center text-primary">Notifications</h2>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <ul class="list-group">
                <?php while ($row = $result->fetch_assoc()): ?>
    <li class="list-group-item">
        <strong>Notification:</strong> <?php echo htmlspecialchars($row['message']); ?><br>
        <strong>What:</strong> <?php echo htmlspecialchars($row['what']); ?><br>
        <strong>Where:</strong> <?php echo htmlspecialchars($row['where']); ?><br>
        <strong>Why:</strong> <?php echo htmlspecialchars($row['why']); ?><br>
        <strong>Caller Name:</strong> <?php echo htmlspecialchars($row['caller_name']); ?><br>
        <strong>Caller Phone:</strong> <?php echo htmlspecialchars($row['caller_phone']); ?><br>
        <a href="notif.php?mark_as_read=<?php echo $row['id']; ?>" class="btn btn-success btn-sm mt-2">Mark as Read</a>
        <a href="/RESCUEFLOW/dispatch/dispatchindex.php?location=<?php echo urlencode($row['where']); ?>" class="btn btn-primary btn-sm mt-2">Approve</a>



    </li>
<?php endwhile; ?>

                </ul>
            <?php else: ?>
                <p class="text-center text-muted">No new notifications.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
