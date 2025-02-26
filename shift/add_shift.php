<?php
session_start();
include('../includes/check_admin.php');

include('../includes/config.php');
include('../includes/restrict_admin.php');
$members = $conn->query("SELECT * FROM members");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Shift</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h2 class="mb-4">Add Shift</h2>
            <form action="store.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Member:</label>
                    <select name="member_id" class="form-select" required>
                        <?php while ($m = $members->fetch_assoc()): ?>
                            <option value="<?= $m['member_id'] ?>">
                                <?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Time:</label>
                    <input type="datetime-local" name="start_time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Time:</label>
                    <input type="datetime-local" name="end_time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Shift</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
