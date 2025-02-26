<?php
ob_start(); // Start output buffering
session_start();
include('../includes/check_admin.php');

include('../includes/config.php');
include('../includes/restrict_admin.php');
// Get the member details
$member_id = $_GET['member_id'] ?? null;
if (!$member_id) {
    die("Error: Invalid or missing member ID.");
}

$query = "SELECT first_name, last_name, email, role_id, rank_id, image FROM members WHERE member_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $member_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $role_id = $row['role_id'];
    $rank_id = $row['rank_id'];
    $old_image = $row['image']; // Store the old image
} else {
    die("Error: Member not found.");
}
mysqli_stmt_close($stmt);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];
    $rank_id = $_POST['rank_id'];

    $image = $old_image; // Default to the old image if no new one is uploaded

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../personel/images/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        // Check if an old image exists and delete it (excluding default.jpg)
        if ($old_image && $old_image !== "default.jpg" && file_exists($target_dir . $old_image)) {
            unlink($target_dir . $old_image);
        }

        // Move new uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $image_name; // Update the image name for the database
        } else {
            echo "Error uploading new image.";
            exit();
        }
    }

    // Update database with new values
    $update_query = "UPDATE members SET first_name=?, last_name=?, email=?, role_id=?, rank_id=?, image=? WHERE member_id=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssissi", $first_name, $last_name, $email, $role_id, $rank_id, $image, $member_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?status=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Personnel</title>
</head>
<body>
    <h2>Edit Personnel</h2>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="member_id" value="<?= htmlspecialchars($member_id) ?>">

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($first_name) ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($last_name) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="">Select Role</option>
                <?php
                $roles_query = "SELECT * FROM roles";
                $roles_result = mysqli_query($conn, $roles_query);
                while ($role = mysqli_fetch_assoc($roles_result)) {
                    $selected = ($role['role_id'] == $role_id) ? "selected" : "";
                    echo "<option value='{$role['role_id']}' $selected>{$role['role_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="rank_id" class="form-label">Rank</label>
            <select name="rank_id" class="form-select" required>
                <option value="">Select Rank</option>
                <?php
                $ranks_query = "SELECT * FROM ranks";
                $ranks_result = mysqli_query($conn, $ranks_query);
                while ($rank = mysqli_fetch_assoc($ranks_result)) {
                    $selected = ($rank['rank_id'] == $rank_id) ? "selected" : "";
                    echo "<option value='{$rank['rank_id']}' $selected>{$rank['rank_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Update Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <label>Current Profile Picture:</label><br>
            <img src="../personel/images/<?= htmlspecialchars($old_image) ?>" width="100" height="100" class="rounded-circle">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>

