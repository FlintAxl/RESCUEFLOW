<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['member_id']) || empty($_POST['member_id']) || empty($_POST['shift_days'])) {
        die("Error: Member ID and at least one shift day are required.");
    }

    $member_id = $_POST['member_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $shift_days = $_POST['shift_days']; // Array of selected days
    $assigned_by = 1; // Replace with logged-in admin ID

    $stmt = $conn->prepare("INSERT INTO shifts (member_id, start_time, end_time, shift_day, assigned_by) VALUES (?, ?, ?, ?, ?)");

    foreach ($shift_days as $day) {
        $stmt->bind_param("isssi", $member_id, $start_time, $end_time, $day, $assigned_by);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
    
    header("Location: index.php");
    exit();
}
