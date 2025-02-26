<?php
session_start();
include('../includes/config.php');
include('../includes/check_admin.php');

// Fetch status options
$statusQuery = "SELECT * FROM status";
$statusResult = mysqli_query($conn, $statusQuery);
$statuses = [];
while ($row = mysqli_fetch_assoc($statusResult)) {
    $statuses[] = $row;
}

if (isset($_POST["submit_location"])) {
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $status_id = 2; // Default to "In progress"
    $dispatched_unit = "Firetruck";

    $sql = "INSERT INTO dispatches (location, dispatched_unit, status_id) VALUES ('$location', '$dispatched_unit', '$status_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>Location saved successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Handle status updates
if (isset($_POST["update_status"])) {
    $dispatch_id = $_POST["dispatch_id"];
    $new_status = $_POST["status"];

    $updateSql = "UPDATE dispatches SET status_id = '$new_status' WHERE disp_id = '$dispatch_id'";

    if (mysqli_query($conn, $updateSql)) {
        echo "<p style='color: green;'>Status updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<form method="POST">
    <p>
        <input type="text" name="location" placeholder="Enter Dispatch Location" required>
    </p>
    <input type="submit" name="submit_location" value="Dispatch">
</form>

<h2>Update Dispatch Status</h2>
<form method="POST">
    <select name="dispatch_id">
        <?php
        $dispatchQuery = "SELECT * FROM dispatches WHERE status_id != 3 ORDER BY dispatched_at DESC";
        $dispatchResult = mysqli_query($conn, $dispatchQuery);
        while ($dispatch = mysqli_fetch_assoc($dispatchResult)) {
            echo "<option value='{$dispatch['disp_id']}'>{$dispatch['location']} (Status: {$dispatch['status_id']})</option>";

        }
        ?>
    </select>

    <select name="status">
        <?php foreach ($statuses as $status): ?>
            <option value="<?php echo $status['status_id']; ?>"><?php echo $status['status_name']; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" name="update_status" value="Update Status">
</form>
