<?php
ob_start(); // Prevent output before headers
require '../includes/config.php';
include('../includes/restrict_admin.php');
include('../includes/check_admin.php');

// Get incident ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid incident ID.");
}
$incident_id = intval($_GET['id']);

// Fetch incident details
$sql = "SELECT i.incident_id, i.incident_type, i.location, i.reported_by, i.status_id, i.severity_id, i.actions_taken, i.cause, s.level as severity_level
        FROM incidents i
        LEFT JOIN severity s ON i.severity_id = s.id
        WHERE i.incident_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $incident_id);
$stmt->execute();
$result = $stmt->get_result();
$incident = $result->fetch_assoc();
$stmt->close();

if (!$incident) {
    die("Incident not found.");
}

// Fetch members for dropdown
$members_sql = "SELECT member_id, CONCAT(first_name, ' ', last_name) AS full_name FROM members ORDER BY first_name ASC";
$members_result = $conn->query($members_sql);

// Fetch severity levels for dropdown
$severity_sql = "SELECT id, level FROM severity ORDER BY id ASC";
$severity_result = $conn->query($severity_sql);

// Fetch statuses for dropdown
$status_sql = "SELECT status_id, status_name FROM status ORDER BY status_id ASC";
$status_result = $conn->query($status_sql);

// Fetch ENUM values for cause
$cause_enum_query = "SHOW COLUMNS FROM incidents LIKE 'cause'";
$cause_enum_result = $conn->query($cause_enum_query);
$cause_enum_row = $cause_enum_result->fetch_assoc();
preg_match_all("/'([^']*)'/", $cause_enum_row['Type'], $matches);
$causes = $matches[1]; // Extract ENUM values

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Incident Report</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4">Edit Incident Report</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Back to List</a>
    
    <form action="update.php" method="POST" class="needs-validation" novalidate>
        <input type="hidden" name="incident_id" value="<?php echo $incident['incident_id']; ?>">
        
        <div class="mb-3">
            <label for="incident_type" class="form-label">Incident Type</label>
            <input type="text" class="form-control" id="incident_type" name="incident_type" value="<?php echo htmlspecialchars($incident['incident_type']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($incident['location']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="reported_by" class="form-label">Reported By</label>
            <select class="form-control" id="reported_by" name="reported_by" required>
                <option value="">Select a Member</option>
                <?php while ($member = $members_result->fetch_assoc()): ?>
                    <option value="<?php echo $member['member_id']; ?>" <?php echo ($incident['reported_by'] == $member['member_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($member['full_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="severity_id" class="form-label">Severity Level</label>
            <select class="form-control" id="severity_id" name="severity_id" required>
                <option value="">Select Severity</option>
                <?php while ($severity = $severity_result->fetch_assoc()): ?>
                    <option value="<?php echo $severity['id']; ?>" <?php echo ($incident['severity_id'] == $severity['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($severity['level']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="status_id" class="form-label">Status</label>
            <select class="form-control" id="status_id" name="status_id">
                <option value="">Select Status</option>
                <?php while ($status = $status_result->fetch_assoc()): ?>
                    <option value="<?php echo $status['status_id']; ?>" <?php echo ($incident['status_id'] == $status['status_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($status['status_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="actions_taken" class="form-label">Actions Taken</label>
            <textarea class="form-control" id="actions_taken" name="actions_taken" rows="4" required><?php echo htmlspecialchars($incident['actions_taken']); ?></textarea>
        </div>
        
        <div class="mb-3">
    <label for="cause" class="form-label">Cause</label>
    <select class="form-control" id="cause" name="cause" required>
        <option value="">Select Cause</option>
        <?php foreach ($causes as $cause_option): ?>
            <option value="<?php echo htmlspecialchars($cause_option); ?>" <?php echo ($incident['cause'] == $cause_option) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cause_option); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

        
        <button type="submit" class="btn btn-primary">Update</button>
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
