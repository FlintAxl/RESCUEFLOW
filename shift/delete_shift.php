<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $member_id = $_GET['id'];

    // Delete all shifts for the member
    $stmt = $conn->prepare("DELETE FROM shifts WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: index.php");
exit();
?>
