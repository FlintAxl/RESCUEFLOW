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

// Fetch incident details including barangay
$sql = "SELECT i.incident_id, i.incident_type, i.barangay_id, i.reported_by, i.severity_id, i.cause, i.address, s.level as severity_level
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

// Fetch barangays for dropdown
$barangay_sql = "SELECT barangay_id, barangay_name FROM barangays ORDER BY barangay_name ASC";
$barangay_result = $conn->query($barangay_sql);


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
        
         <!-- Replace Location with Barangay Dropdown -->
         <div class="mb-3">
            <label for="barangay_id" class="form-label">Barangay</label>
            <select class="form-control" id="barangay_id" name="barangay_id" required>
                <option value="">Select Barangay</option>
                <?php while ($barangay = $barangay_result->fetch_assoc()): ?>
                    <option value="<?php echo $barangay['barangay_id']; ?>" 
                        <?php echo ($incident['barangay_id'] == $barangay['barangay_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($barangay['barangay_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
         <!-- Add Address Field -->
         <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($incident['address']); ?>" required>
        </div>
        <div class="mb-3">
    <label for="reported_by" class="form-label">Reported By</label>
    <input type="text" class="form-control" id="reported_by" name="reported_by" value="<?php echo htmlspecialchars($incident['reported_by'] ?? ''); ?>" required>
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
