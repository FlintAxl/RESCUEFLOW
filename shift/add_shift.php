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
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Add Shift</h4>
        </div>
        <div class="card-body">
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
                    <label class="form-label">Select Shift Days:</label>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Day</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            foreach ($days as $day): ?>
                                <tr>
                                    <td><?= $day ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" name="shift_days[]" value="<?= $day ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Start Time:</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Time:</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Add Shift</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
