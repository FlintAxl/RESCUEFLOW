<?php
ob_start(); // Prevent output before headers

require '../includes/config.php';
include('../includes/check_admin.php');

// Handle Create & Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $incident_id = $_POST['incident_id'] ?? null;
    $incident_type = trim($_POST['incident_type']);
    $severity_id = $_POST['severity_id'] ?? null;
    $location = trim($_POST['location']);
    $reported_by = !empty($_POST['reported_by']) ? intval($_POST['reported_by']) : null;
    $status = $_POST['status'] ?? 'Pending';
    $actions_taken = trim($_POST['actions_taken']); // New field for actions taken
    $attachments = [];
    $cause = trim($_POST['cause']); // New field for cause

    // Verify reported_by exists in members table
    if (!is_null($reported_by)) {
        $check_member_sql = "SELECT member_id FROM members WHERE member_id = ?";
        $check_stmt = $conn->prepare($check_member_sql);
        $check_stmt->bind_param("i", $reported_by);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows == 0) {
            die("Error: The reported_by ID does not exist in the members table.");
        }
        $check_stmt->close();
    }

    // Handle file uploads
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['attachments']['name'][0])) {
        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['attachments']['error'][$key] == 0) {
                $file_name = time() . "_" . basename($_FILES['attachments']['name'][$key]);
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($tmp_name, $file_path)) {
                    $attachments[] = $file_path;
                }
            } else {
                die("Error uploading file: " . $_FILES['attachments']['error'][$key]);
            }
        }
    }

    // Retrieve existing attachments if updating
    if ($incident_id) {
        $sql = "SELECT attachments FROM incidents WHERE incident_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $incident_id);
        $stmt->execute();
        $stmt->bind_result($existing_attachments);
        $stmt->fetch();
        $stmt->close();

        if (!empty($existing_attachments)) {
            $existing_files = explode(',', $existing_attachments);
            $attachments = array_merge($existing_files, $attachments);
        }
    }

    $attachments_string = !empty($attachments) ? implode(',', $attachments) : null;

    if ($incident_id) {
        // Update Incident
        $sql = "UPDATE incidents SET incident_type=?, severity_id=?, location=?, reported_by=?, status=?, actions_taken=?, cause=?, attachments=? WHERE incident_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sississsi", $incident_type, $severity_id, $location, $reported_by, $status, $actions_taken, $cause, $attachments_string, $incident_id);
    } else {
        // Insert New Incident
        $sql = "INSERT INTO incidents (incident_type, severity_id, location, reported_by, status, actions_taken, cause, attachments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sississs", $incident_type, $severity_id, $location, $reported_by, $status, $actions_taken, $cause, $attachments_string);
    }


    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php");
        exit();
    } else {
        die("Database error: " . $stmt->error);
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $incident_id = intval($_GET['delete']);
    $sql = "DELETE FROM incidents WHERE incident_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $incident_id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php");
        exit();
    } else {
        die("Delete error: " . $stmt->error);
    }
}

// Fetch incidents with severity name, member name, status name, and actions taken
$sql = "SELECT i.*, 
               COALESCE(CONCAT(m.first_name, ' ', m.last_name), 'Unknown') AS reporter_name, 
               s.level AS severity,
               st.status_name 
        FROM incidents i
        LEFT JOIN members m ON i.reported_by = m.member_id
        LEFT JOIN severity s ON i.severity_id = s.id
        LEFT JOIN status st ON i.status_id = st.status_id
        ORDER BY i.reported_time DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4">Incident Reports</h1>
    <a href="create.php" class="btn btn-success mb-3">Add New Incident</a>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
        <thead class="table-dark">
    <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Severity</th>
        <th>Location</th>
        <th>Reported By</th>
        <th>Time</th>
        <th>Cause</th>
        <th>Attachments</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['incident_id']); ?></td>
        <td><?php echo htmlspecialchars($row['incident_type']); ?></td>
        <td><?php echo htmlspecialchars($row['severity'] ?? 'Not Specified'); ?></td>
        <td><?php echo htmlspecialchars($row['location']); ?></td>
        <td><?php echo htmlspecialchars($row['reporter_name']); ?></td>
        <td><?php echo htmlspecialchars($row['reported_time']); ?></td>
        <td><?php echo htmlspecialchars($row['cause'] ?? 'No cause recorded.'); ?></td>
        <td>
            <?php 
            if (!empty($row['attachments'])) {
                $files = explode(',', $row['attachments']);
                foreach ($files as $file) {
                    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        echo '<img src="' . htmlspecialchars(trim($file)) . '" alt="Attachment" style="max-width: 100px; max-height: 100px; margin-right: 5px;">';
                    } else {
                        echo '<a href="' . htmlspecialchars(trim($file)) . '" target="_blank">View File</a><br>';
                    }
                }
            } else {
                echo 'No Attachments';
            }
            ?>
        </td>
        <td>
            <a href="edit.php?id=<?php echo $row['incident_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="?delete=<?php echo $row['incident_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>

    </div>
</body>
</html>

<?php
$conn->close();
ob_end_flush(); // End output buffering
?>