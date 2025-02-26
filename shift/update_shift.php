<?php
session_start();

include('../includes/config.php');
include('../includes/restrict_admin.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shift_id = $_POST['shift_id'];
    $member_id = $_POST['member_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $stmt = $conn->prepare("UPDATE shifts SET member_id = ?, start_time = ?, end_time = ? WHERE shift_id = ?");
    $stmt->bind_param("issi", $member_id, $start_time, $end_time, $shift_id);
    $stmt->execute();
    header("Location: index.php");
}
?>