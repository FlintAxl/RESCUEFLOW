<?php
session_start();
include('../includes/check_admin.php');
include('../includes/config.php');


// Fetch user's member_id from the session
$user_id = $_SESSION['user_id']; 

$query_member = "SELECT member_id FROM users WHERE user_id = '$user_id'";
$result_member = mysqli_query($conn, $query_member);
$row_member = mysqli_fetch_assoc($result_member);
$member_id = $row_member['member_id'];

if ($is_admin) {
    // Admins see all shifts
    $query = "SELECT s.shift_id, m.member_id, m.first_name, m.last_name, 
                     s.start_time, s.end_time, s.shift_day
              FROM shifts s
              JOIN members m ON s.member_id = m.member_id
              ORDER BY m.last_name ASC, FIELD(s.shift_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
} else {
    // Regular members see only their shifts
    $query = "SELECT s.shift_id, m.member_id, m.first_name, m.last_name, 
                     s.start_time, s.end_time, s.shift_day
              FROM shifts s
              JOIN members m ON s.member_id = m.member_id
              WHERE s.member_id = '$member_id'
              ORDER BY FIELD(s.shift_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
}


$result = mysqli_query($conn, $query);

$shifts = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Convert time to 12-hour format
    $start_time = date("h:i A", strtotime($row['start_time']));
    $end_time = date("h:i A", strtotime($row['end_time']));
    
    $shifts[$row['member_id']]['name'] = $row['first_name'] . ' ' . $row['last_name'];
    $shifts[$row['member_id']]['shifts'][$row['shift_day']] = $start_time . ' - ' . $end_time;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Shift Management</h4>
        </div>
        <div class="card-body">
            <?php if ($is_admin): ?>
                <div class="d-flex justify-content-between mb-3">
                    <a href="add_shift.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Shift
                    </a>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Member</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                            <?php if ($is_admin): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shifts as $member_id => $memberData): ?>
                            <tr>
                                <td><?= htmlspecialchars($memberData['name']); ?></td>
                                <?php 
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    foreach ($days as $day) {
                                        echo "<td>" . (isset($memberData['shifts'][$day]) ? $memberData['shifts'][$day] : '-') . "</td>";
                                    }
                                ?>
                                <?php if ($is_admin): ?>
                                    <td>
                                        <a href="edit_shift.php?member_id=<?= $member_id ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="delete_shift.php?member_id=<?= $member_id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($shifts)): ?>
                            <tr>
                                <td colspan="<?= $is_admin ? 9 : 8 ?>" class="text-center text-muted">No shifts assigned yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
