<?php
ob_start(); // Prevent output before headers
require '../includes/config.php';
include '../includes/header.php';

// Fetch members for the dropdown
$members_sql = "SELECT member_id, CONCAT(first_name, ' ', last_name) AS full_name FROM members ORDER BY first_name ASC";
$members_result = $conn->query($members_sql);

if (!$members_result) {
    die("Database error: " . $conn->error);
}

// Fetch severity levels
$severity_sql = "SELECT id, level FROM severity ORDER BY id ASC";
$severity_result = $conn->query($severity_sql);

if (!$severity_result) {
    die("Database error: " . $conn->error);
}

// Fetch status options for the dropdown
$status_sql = "SELECT status_id, status_name FROM status ORDER BY status_id ASC";
$status_result = $conn->query($status_sql);

if (!$status_result) {
    die("Database error: " . $conn->error);
}

// Check if editing an incident
$incident_id = $_GET['id'] ?? null;
$incident = null;

if ($incident_id) {
    $incident_sql = "SELECT * FROM incidents WHERE incident_id = ?";
    $stmt = $conn->prepare($incident_sql);
    $stmt->bind_param("i", $incident_id);
    $stmt->execute();
    $incident = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle POST request for creating or editing incident
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $incident_id = $_POST['incident_id'] ?? null;
    $incident_type = trim($_POST['incident_type']);
    $severity_id = $_POST['severity_id'] ?? null;
    $location = trim($_POST['location']);
    $reported_by = $_POST['reported_by'] ?? null;
    $status_id = $_POST['status_id'] ?? 1; // Default to 'Pending'
    $actions_taken = trim($_POST['actions_taken']);
    $cause = trim($_POST['cause']);
    $attachments = [];

    // File upload handling
    if (!empty($_FILES['attachments']['name'][0])) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['attachments']['error'][$key] == 0) {
                $file_name = time() . "_" . basename($_FILES['attachments']['name'][$key]);
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($tmp_name, $file_path)) {
                    $attachments[] = $file_path;
                }
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

    // SQL query to insert or update incident
    if ($incident_id) {
        // Update Incident
        $sql = "UPDATE incidents SET incident_type=?, severity_id=?, location=?, reported_by=?, status_id=?, actions_taken=?, cause=?, attachments=? WHERE incident_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sississsi", $incident_type, $severity_id, $location, $reported_by, $status_id, $actions_taken, $cause, $attachments_string, $incident_id);
    } else {
        // Insert New Incident
        $sql = "INSERT INTO incidents (incident_type, severity_id, location, reported_by, status_id, actions_taken, cause, attachments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sississs", $incident_type, $severity_id, $location, $reported_by, $status_id, $actions_taken, $cause, $attachments_string);
    }

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php");
        exit();
    } else {
        die("Database error: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $incident ? "Edit" : "Create"; ?> Incident Report</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4"><?php echo $incident ? "Edit" : "Create"; ?> Incident Report</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Back to List</a>

    <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="incident_id" value="<?php echo $incident['incident_id'] ?? ''; ?>">

        <!-- Other form fields remain unchanged -->
        <div class="mb-3">
            <label for="incident_type" class="form-label">Incident Type</label>
            <input type="text" class="form-control" id="incident_type" name="incident_type" value="<?php echo htmlspecialchars($incident['incident_type'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label for="severity_id" class="form-label">Severity</label>
            <select class="form-control" id="severity_id" name="severity_id" required>
                <option value="">Select Severity Level</option>
                <?php while ($severity = $severity_result->fetch_assoc()): ?>
                    <option value="<?php echo $severity['id']; ?>" <?php echo isset($incident['severity_id']) && $incident['severity_id'] == $severity['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($severity['level']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($incident['location'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label for="reported_by" class="form-label">Reported By</label>
            <select class="form-control" id="reported_by" name="reported_by" required>
                <option value="">Select a Member</option>
                <?php while ($member = $members_result->fetch_assoc()): ?>
                    <option value="<?php echo $member['member_id']; ?>" <?php echo isset($incident['reported_by']) && $incident['reported_by'] == $member['member_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($member['full_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="status_id" class="form-label">Status</label>
            <select class="form-control" id="status_id" name="status_id" required>
                <option value="">Select Status</option>
                <?php while ($status = $status_result->fetch_assoc()): ?>
                    <option value="<?php echo $status['status_id']; ?>" <?php echo isset($incident['status_id']) && $incident['status_id'] == $status['status_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($status['status_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="actions_taken" class="form-label">Actions Taken</label>
            <textarea class="form-control" id="actions_taken" name="actions_taken" rows="4" required><?php echo htmlspecialchars($incident['actions_taken'] ?? ''); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="attachments" class="form-label">Attachments</label>
            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
            <?php if (!empty($incident['attachments'])): ?>
                <p>Existing Attachments:</p>
                <?php
                $files = explode(',', $incident['attachments']);
                foreach ($files as $file): ?>
                    <a href="<?php echo htmlspecialchars($file); ?>" target="_blank">View File</a><br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="cause" class="form-label">Cause</label>
            <textarea class="form-control" id="cause" name="cause" rows="4" required><?php echo htmlspecialchars($incident['cause'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <script>
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
