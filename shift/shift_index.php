<?php
session_start();
include('../includes/check_admin.php');
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Fetch shifts and organize them by member and day
$query = "SELECT s.shift_id, m.member_id, m.first_name, m.last_name, 
                 s.start_time, s.end_time, s.shift_day
          FROM shifts s
          JOIN members m ON s.member_id = m.member_id
          ORDER BY m.last_name ASC, FIELD(s.shift_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

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
                                
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($shifts)): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No shifts assigned yet.</td>
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
