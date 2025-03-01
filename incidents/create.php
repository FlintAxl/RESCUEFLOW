<?php
ob_start(); // Prevent output before headers
require '../includes/config.php';
include '../includes/header.php';

// Fetch severity levels
$severity_sql = "SELECT id, level FROM severity ORDER BY id ASC";
$severity_result = $conn->query($severity_sql);
if (!$severity_result) {
    die("Database error: " . $conn->error);
}

// Fetch status options
$status_sql = "SELECT status_id, status_name FROM status ORDER BY status_id ASC";
$status_result = $conn->query($status_sql);
if (!$status_result) {
    die("Database error: " . $conn->error);
}

// Fetch barangays
$barangay_sql = "SELECT barangay_id, barangay_name FROM barangays ORDER BY barangay_name ASC";
$barangay_result = $conn->query($barangay_sql);
if (!$barangay_result) {
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
    $barangay_id = $_POST['barangay_id'] ?? null;
    $address = trim($_POST['address']);
    $reported_by = trim($_POST['reported_by']);
    $status_id = $_POST['status_id'] ?? null;
    $actions_taken = trim($_POST['actions_taken'] ?? '');
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
        $sql = "UPDATE incidents SET incident_type=?, severity_id=?, barangay_id=?, address=?, reported_by=?, status_id=?, actions_taken=?, cause=?, attachments=? WHERE incident_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siississsi", $incident_type, $severity_id, $barangay_id, $address, $reported_by, $status_id, $actions_taken, $cause, $attachments_string, $incident_id);
    } else {
        $sql = "INSERT INTO incidents (incident_type, severity_id, barangay_id, address, reported_by, status_id, actions_taken, cause, attachments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siississs", $incident_type, $severity_id, $barangay_id, $address, $reported_by, $status_id, $actions_taken, $cause, $attachments_string);
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

      
        </select>
        <div class="mb-3">
<label for="barangay_id">Barangay</label>
<select name="barangay_id" required>
    <option value="">Select Barangay</option>
    <?php while ($barangay = $barangay_result->fetch_assoc()): ?>
        <option value="<?php echo $barangay['barangay_id']; ?>" <?php echo (isset($incident['barangay_id']) && $incident['barangay_id'] == $barangay['barangay_id']) ? 'selected' : ''; ?>>
    <?php echo htmlspecialchars($barangay['barangay_name']); ?>
</option>


    <?php endwhile; ?>
</select>
</div>
        <div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($incident['address'] ?? ''); ?>" required>
</div>


        <div class="mb-3">
    <label for="reported_by" class="form-label">Reported By</label>
    <input type="text" class="form-control" id="reported_by" name="reported_by" value="<?php echo htmlspecialchars($incident['reported_by'] ?? ''); ?>" required>
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
    <select class="form-control" id="cause" name="cause" required>
        <option value="">Select a Cause</option>
        <?php
        $causes = [
            'Electrical Faults', 'Unattended Cooking', 'Candles & Open Flames',
            'Smoking Indoors', 'Gas Leaks', 'Flammable Liquids',
            'Children Playing with Fire', 'Heating Equipment',
            'Faulty Appliances', 'Arson'
        ];
        foreach ($causes as $cause_option): ?>
            <option value="<?php echo $cause_option; ?>" <?php echo (isset($incident['cause']) && $incident['cause'] == $cause_option) ? 'selected' : ''; ?>>
                <?php echo $cause_option; ?>
            </option>
        <?php endforeach; ?>
    </select>
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
