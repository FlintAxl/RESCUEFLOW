<?php
ob_start(); // Start output buffering
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure all required fields are set
    if (isset($_POST['member_id'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['role_id'], $_POST['rank_id'])) {
        
        // Sanitize inputs
        $member_id = intval($_POST['member_id']);
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $role_id = intval($_POST['role_id']);
        $rank_id = intval($_POST['rank_id']);

        // Update query using prepared statements
        $query = "UPDATE members SET first_name = ?, last_name = ?, email = ?, role_id = ?, rank_id = ? WHERE member_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssiii", $firstname, $lastname, $email, $role_id, $rank_id, $member_id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            // Redirect to index.php with success message
            header("Location: index.php?status=success");
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Missing required fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
