<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data safely
    $training_name = $_POST['training_name'] ?? null;
    $description = $_POST['description'] ?? null;
    $scheduled_date = $_POST['scheduled_date'] ?? null;

    // Check if any required field is empty
    if (!$training_name || !$description || !$scheduled_date) {
        die("Error: All fields are required.");
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO trainings (training_name, description, scheduled_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $training_name, $description, $scheduled_date);

    // Execute the query
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
