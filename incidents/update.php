<?php
ob_start();
require '../includes/config.php';
include('../includes/restrict_admin.php');
include('../includes/check_admin.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $incident_id = intval($_POST['incident_id']);
    $incident_type = $_POST['incident_type'];
    $location = $_POST['location'];
    $reported_by = intval($_POST['reported_by']);
    $severity_id = intval($_POST['severity_id']);
    $status_id = intval($_POST['status_id']);  
    $actions_taken = $_POST['actions_taken'];  
    $cause = $_POST['cause'];  // Capture the cause field

    // Fetch current attachments from the database (if any)
    $sql = "SELECT attachments FROM incidents WHERE incident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $incident_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existing_attachments = $row ? $row['attachments'] : '';  
    $stmt->close();

    // Update query including cause
    $sql = "UPDATE incidents SET
            incident_type = ?,
            location = ?,
            reported_by = ?,
            severity_id = ?,
            status_id = ?,
            actions_taken = ?,
            cause = ?
            WHERE incident_id = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiissi", $incident_type, $location, $reported_by, $severity_id, $status_id, $actions_taken, $cause, $incident_id);

    // Execute the update
    if ($stmt->execute()) {
        header("Location: index.php?id=" . $incident_id);
        exit();
    } else {
        echo "Error updating the incident.";
    }
}
?>
