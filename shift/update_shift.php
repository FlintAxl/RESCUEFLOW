<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $shift_ids = $_POST['shift_ids'];  // Array of shift IDs
    $start_times = $_POST['start_times'];  // Array of start times
    $end_times = $_POST['end_times'];  // Array of end times

    for ($i = 0; $i < count($shift_ids); $i++) {
        $stmt = $conn->prepare("UPDATE shifts SET start_time = ?, end_time = ? WHERE shift_id = ?");
        $stmt->bind_param("ssi", $start_times[$i], $end_times[$i], $shift_ids[$i]);
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
}
?>
