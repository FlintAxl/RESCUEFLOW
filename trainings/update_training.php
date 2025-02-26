<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['training_id']) || empty($_POST['training_id']) || !is_numeric($_POST['training_id'])) {
        die("Error: Missing or invalid training ID.");
    }

    // Get training_id from POST
    $training_id = $_POST['training_id'];

    // Fetch input values & sanitize
    $training_name = mysqli_real_escape_string($conn, $_POST['training_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $scheduled_date = mysqli_real_escape_string($conn, $_POST['scheduled_date']);

    // Update query using prepared statement
    $update_query = "UPDATE trainings SET training_name = ?, description = ?, scheduled_date = ? WHERE training_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssi", $training_name, $description, $scheduled_date, $training_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating training: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    die("Invalid request.");
}
?>
