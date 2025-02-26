<?php
session_start();

include('../includes/config.php');
include('../includes/restrict_admin.php');
// Check if member_id is provided
if (isset($_GET['training_id'])) {
    $training_id = $_GET['training_id'];

    // Delete the record from the members table
    $sql = "DELETE FROM trainings WHERE training_id = '$training_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../trainings/index.php"); // Redirect to index.php after deletion
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No member ID specified.";
}
