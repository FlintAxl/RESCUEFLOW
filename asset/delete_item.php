<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete images related to the asset
    $sqlImages = "SELECT img_path FROM assets_image WHERE asset_id = $id";
    $result = $conn->query($sqlImages);

    while ($row = $result->fetch_assoc()) {
        $imagePath = '../asset/images/' . $row['img_path'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the actual file
        }
    }

    // Delete from assets_image table
    $sqlDeleteImages = "DELETE FROM assets_image WHERE asset_id = $id";
    $conn->query($sqlDeleteImages);

    // Delete from assets table
    $sqlDeleteAsset = "DELETE FROM assets WHERE asset_id = $id";
    if ($conn->query($sqlDeleteAsset) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
