<?php 
include('../includes/config.php');
include('../includes/check_admin.php');

// Handle Time-IN (Mark Attendance)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_attendance'])) {
    $member_id = $_POST['member_id'];

    // Check if the user has an assigned shift
    $shift_check = $conn->prepare("SELECT shift_id FROM shifts WHERE member_id = ? AND NOW() BETWEEN start_time AND end_time");
    $shift_check->bind_param("i", $member_id);
    $shift_check->execute();
    $shift_result = $shift_check->get_result();
    $shift = $shift_result->fetch_assoc();
    $shift_check->close();

    if ($shift) {
        $shift_id = $shift['shift_id'];

        // Insert new time-in
        $stmt = $conn->prepare("INSERT INTO attendance (member_id, shift_id, timestamp) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $member_id, $shift_id);

        if ($stmt->execute()) {
            echo "<script>alert('Time-IN recorded successfully!'); window.location.href='attendance.php';</script>";
        } else {
            echo "<script>alert('Error marking Time-IN: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('You cannot Time-IN without an assigned shift!'); window.location.href='attendance.php';</script>";
    }
}

// Handle Time-OUT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['timeout'])) {
    $member_id = $_POST['member_id'];

    // Check if the user has an active Time-IN record
    $stmt = $conn->prepare("SELECT shift_id FROM attendance WHERE member_id = ? AND time_out IS NULL");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance = $result->fetch_assoc();
    $stmt->close();

    if ($attendance) {
        $shift_id = $attendance['shift_id'];

        $stmt = $conn->prepare("UPDATE attendance SET time_out = NOW() WHERE member_id = ? AND shift_id = ? AND time_out IS NULL");
        $stmt->bind_param("ii", $member_id, $shift_id);

        if ($stmt->execute()) {
            echo "<script>alert('Time-OUT recorded successfully!'); window.location.href='attendance.php';</script>";
        } else {
            echo "<script>alert('Error recording Time-OUT: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('No active Time-IN record found!'); window.location.href='attendance.php';</script>";
    }
}

// Fetch members and their assigned shifts
$sql = "SELECT m.member_id, m.first_name, m.last_name, s.shift_id, s.start_time, s.end_time, 
            COALESCE(a.timestamp, 'Not Recorded') AS time_in, 
            COALESCE(a.time_out, 'Not Recorded') AS time_out
        FROM members m
        LEFT JOIN shifts s ON m.member_id = s.member_id
        LEFT JOIN attendance a ON m.member_id = a.member_id AND s.shift_id = a.shift_id
        ORDER BY a.timestamp DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Attendance Management</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Member Name</th>
                        <th>Shift Time</th>
                        <th>Time-IN</th>
                        <th>Time-OUT</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                            <td><?= $row['start_time'] ? htmlspecialchars($row['start_time'] . " - " . $row['end_time']) : "No Shift Assigned"; ?></td>
                            <td><?= $row['time_in']; ?></td>
                            <td><?= $row['time_out']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="member_id" value="<?= htmlspecialchars($row['member_id']); ?>">

                                    <?php if (!empty($row['shift_id'])): ?>
                                        <?php if ($row['time_in'] === "Not Recorded"): ?>
                                            <button type="submit" name="mark_attendance" class="btn btn-primary">Time-IN</button>
                                        <?php elseif ($row['time_out'] === "Not Recorded"): ?>
                                            <button type="submit" name="timeout" class="btn btn-danger">Time-OUT</button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>No Shift</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
