<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $role_id = $_POST['role_id'] ?? null;
    $rank_id = $_POST['rank_id'] ?? null;
    
    $image = null; // Default value

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../personel/images/";  // Ensure correct folder path
        $image = time() . "_" . basename($_FILES["image"]["name"]); // Prevent duplicate filenames
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Image successfully uploaded
        } else {
            echo "Error uploading the image.";
            exit();
        }
    }

    // Insert into the database
    $sql = "INSERT INTO members (first_name, last_name, email, phone, role_id, rank_id, image) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', $role_id, $rank_id, '$image')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
