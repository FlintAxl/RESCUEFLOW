<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
include('../includes/check_admin.php');

$id = $_GET['id'];
$sql = "SELECT * FROM assets WHERE asset_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$asset = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asset</title>
</head>
<body>
    <h2>Edit Asset</h2>
    <form action="item_update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="asset_id" value="<?php echo $asset['asset_id']; ?>">
        
        <label>Name:</label>
        <input type="text" name="asset_name" value="<?php echo htmlspecialchars($asset['asset_name']); ?>" required><br>
        
        <label>Description:</label>
        <textarea name="description"><?php echo htmlspecialchars($asset['description']); ?></textarea><br>
        
        <label>Status:</label>
        <select name="status">
            <option value="Available" <?php if ($asset['status'] == 'Available') echo 'selected'; ?>>Available</option>
            <option value="In Use" <?php if ($asset['status'] == 'In Use') echo 'selected'; ?>>In Use</option>
            <option value="Maintenance" <?php if ($asset['status'] == 'Maintenance') echo 'selected'; ?>>Maintenance</option>
            <option value="Damaged" <?php if ($asset['status'] == 'Damaged') echo 'selected'; ?>>Damaged</option>
        </select><br>
        
        <label>Last Maintenance Date:</label>
        <input type="date" name="last_maintenance_date" value="<?php echo $asset['last_maintenance_date']; ?>"><br>

        <label>Existing Images:</label><br>
        <?php
        $asset_id = $asset['asset_id'];
        $img_query = "SELECT * FROM assets_image WHERE asset_id = $asset_id";
        $img_result = $conn->query($img_query);

        while ($img_row = $img_result->fetch_assoc()) {
            echo '<img src="../' . $img_row['img_path'] . '" width="100" height="100" style="margin:5px;">';
        }
        ?><br>

        <label>Upload New Images:</label>
        <input type="file" name="images[]" multiple><br>
        
        <button type="submit">Update</button>
    </form>
</body>
</html>

<?php
$conn->close(); // Close connection at the end
?>
