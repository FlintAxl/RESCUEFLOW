<?php
session_start();
include('../includes/check_admin.php');

include('../includes/config.php');
include('../includes/restrict_admin.php');
// Initialize variables
$training_id = $training_name = $description = $scheduled_date = "";

// Check if 'training_id' is provided in the URL and is numeric
if (isset($_GET['training_id']) && is_numeric($_GET['training_id'])) {
    $training_id = $_GET['training_id'];

    // Fetch training details
    $query = "SELECT training_id, training_name, description, scheduled_date FROM trainings WHERE training_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $training_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $training_name = $row['training_name'];
        $description = $row['description'];
        $scheduled_date = $row['scheduled_date'];
    } else {
        echo "<p style='color:red;'>Error: Training not found!</p>";
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    die("Error: Invalid or missing training ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Training</title>
</head>
<body>
    <h2>Edit Training</h2>
    
    <form action="update_training.php" method="POST">
        <input type="hidden" name="training_id" value="<?= htmlspecialchars($training_id) ?>">

        <label>Training Name:</label>
        <input type="text" name="training_name" value="<?= htmlspecialchars($training_name) ?>" required><br>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($description) ?></textarea><br>

        <label>Scheduled Date:</label>
        <input type="date" name="scheduled_date" value="<?= htmlspecialchars($scheduled_date) ?>" required><br>

        <button type="submit">Update</button>
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
