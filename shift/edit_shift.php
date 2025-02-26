<?php
session_start();
include('../includes/check_admin.php');

include('../includes/config.php');
include('../includes/restrict_admin.php');
$id = $_GET['id'];
$shift = $conn->query("SELECT * FROM shifts WHERE shift_id = $id")->fetch_assoc();
$members = $conn->query("SELECT * FROM members");
?>
<!DOCTYPE html>
<html>
<head><title>Edit Shift</title></head>
<body>
    <h2>Edit Shift</h2>
    <form action="update_shift.php" method="post">
        <input type="hidden" name="shift_id" value="<?= $shift['shift_id'] ?>">
        <label>Member:</label>
        <select name="member_id">
            <?php while ($m = $members->fetch_assoc()): ?>
                <option value="<?= $m['member_id'] ?>" <?= $m['member_id'] == $shift['member_id'] ? 'selected' : '' ?>>
                    <?= $m['first_name'] . ' ' . $m['last_name'] ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        <label>Start Time:</label> <input type="datetime-local" name="start_time" value="<?= $shift['start_time'] ?>" required><br>
        <label>End Time:</label> <input type="datetime-local" name="end_time" value="<?= $shift['end_time'] ?>" required><br>
        <button type="submit">Update Shift</button>
    </form>
</body>
</html>
