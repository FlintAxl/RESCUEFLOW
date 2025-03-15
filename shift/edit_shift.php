<?php
session_start();
include('../includes/check_admin.php');
include('../includes/config.php');
include('../includes/restrict_admin.php');

$member_id = $_GET['member_id'] ?? null; // Get member_id, prevent errors if missing

if (!$member_id) {
    echo "<script>alert('No member selected!'); window.location.href='index.php';</script>";
    exit();
}

// Fetch shifts of this member
$query = $conn->prepare("SELECT * FROM shifts WHERE member_id = ?");
$query->bind_param("i", $member_id);
$query->execute();
$result = $query->get_result();
$shifts = $result->fetch_all(MYSQLI_ASSOC); // Fetch all shifts

$members = $conn->query("SELECT * FROM members");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shift</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Edit Shifts for Member</h4>
        </div>
        <div class="card-body">
            <form action="update_shift.php" method="post">
                <input type="hidden" name="member_id" value="<?= $member_id ?>">

                <div class="mb-3">
                    <label class="form-label">Member:</label>
                    <select name="member_id" class="form-select" required>
                        <?php while ($m = $members->fetch_assoc()): ?>
                            <option value="<?= $m['member_id'] ?>" <?= $m['member_id'] == $member_id ? 'selected' : '' ?>>
                                <?= $m['first_name'] . ' ' . $m['last_name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Shift Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shifts as $shift): ?>
                            <input type="hidden" name="shift_ids[]" value="<?= $shift['shift_id'] ?>">
                            <tr>
                                <td><?= $shift['shift_day'] ?></td>
                                <td><input type="time" name="start_times[]" class="form-control" value="<?= $shift['start_time'] ?>" required></td>
                                <td><input type="time" name="end_times[]" class="form-control" value="<?= $shift['end_time'] ?>" required></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Update Shifts</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
