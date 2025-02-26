<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');
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
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Training Name</th>
                    <th>Training Description</th>
                    <th>Scheduled Date</th>
                    
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