<?php
session_start();
include('../includes/check_admin.php');

include('../includes/config.php');
include('../includes/restrict_admin.php');
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $training_name = $_POST['training_name'];
    $description = $_POST['description'];
    $scheduled_date = $_POST['scheduled_date'];

    // Insert data into the trainings table
    $sql = "INSERT INTO trainings (training_name, description, scheduled_date) 
            VALUES ('$training_name', '$description', '$scheduled_date')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../trainings/index.php");
exit();


    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Training</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add New Training</h1>
        <form method="POST" action="store_training.php" >
            <div class="mb-3">
                <label for="training_name" class="form-label">Training Name</label>
                <input type="text" name="training_name" id="training_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Training Description</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="scheduled_date" class="form-label">Scheduled Date</label>
                <input type="date" name="scheduled_date" id="scheduled_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
