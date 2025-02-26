<?php
session_start();

include('../includes/config.php');
include('../includes/restrict_admin.php');
include('../includes/check_admin.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asset_name = $_POST['asset_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $last_maintenance_date = $_POST['last_maintenance_date'];

    // Validate required fields
    if (empty($asset_name) || empty($status)) {
        echo "All required fields must be filled!";
    } else {
        // Insert into database
        $sql = "INSERT INTO assets (asset_name, description, status, last_maintenance_date) VALUES ('$asset_name', '$description', '$status', '$last_maintenance_date')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Asset added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Asset</title>
</head>
<body>
    <h2>Add Asset</h2>
    <form action="store.php" method="post" enctype="multipart/form-data">
    <label for="asset_name">Asset Name:</label>
    <input type="text" name="asset_name" required><br>
    
    <label for="description">Description:</label>
    <textarea name="description"></textarea><br>
    
    <label for="status">Status:</label>
    <select name="status">
        <option value="Available">Available</option>
        <option value="In Use">In Use</option>
        <option value="Maintenance">Maintenance</option>
        <option value="Damaged">Damaged</option>
    </select><br>
    
    <label for="last_maintenance_date">Last Maintenance Date:</label>
    <input type="date" name="last_maintenance_date"><br>

    <label for="images">Upload Images:</label>
    <input type="file" name="images[]" multiple><br> <!-- Allow multiple files -->
    
    <button type="submit">Add Asset</button>
</form>

</body>
</html>
