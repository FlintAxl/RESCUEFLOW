<?php
session_start();
include('../includes/check_admin.php');

include('../includes/config.php');
include('../includes/restrict_admin.php');

// Fetch members data

    $sql = "SELECT trainings.training_id, trainings.training_name, trainings.description, trainings.scheduled_date 
    FROM trainings";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Training Management</h1>
        <a href="create_training.php" class="btn btn-primary mb-3">Add New Training</a>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Training Name</th>
                    <th>Training Description</th>
                    <th>Scheduled Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
    <td><?= $row['training_id']; ?></td> <!-- Fix: Add training ID -->
    <td><?= $row['training_name']; ?></td>
    <td><?= $row['description']; ?></td>
    <td><?= $row['scheduled_date']; ?></td>
    <td>
        <a href="edit_training.php?training_id=<?= $row['training_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="delete_training.php?training_id=<?= $row['training_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
    </td>
</tr>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No training found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
?>