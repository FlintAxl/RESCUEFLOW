<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $assigned_by = 1; // Change this based on logged-in user

    $stmt = $conn->prepare("INSERT INTO shifts (member_id, start_time, end_time, assigned_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $member_id, $start_time, $end_time, $assigned_by);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
