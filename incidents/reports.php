<?php
ob_start(); // Prevent output before headers

require '../includes/config.php';
include('../includes/check_admin.php');
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Handle Create & Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $incident_id = $_POST['incident_id'] ?? null;
    $incident_type = isset($_POST['incident_type']) ? trim($_POST['incident_type']) : '';
    $severity_id = $_POST['severity_id'] ?? null;
    $barangay_id = $_POST['barangay_id'] ?? null; // Use barangay_id instead of location

    // Ensure 'reported_by' is provided, or set a default value (e.g., "Anonymous")
    $reported_by = !empty($_POST['reported_by']) ? trim($_POST['reported_by']) : 'Anonymous'; 
    $status_id = isset($_POST['status']) ? $_POST['status'] : 'Pending'; 
    $actions_taken = isset($_POST['actions_taken']) ? trim($_POST['actions_taken']) : '';
    $attachments = [];
    $cause = isset($_POST['cause']) ? trim($_POST['cause']) : ''; 

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


    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}


// Fetch incidents with barangay name instead of location
$sql = "SELECT i.*, 
               i.reported_by AS reporter_name, 
               s.level AS severity,
               st.status_name,
               i.address,
               b.barangay_name AS barangay
        FROM incidents i
        LEFT JOIN severity s ON i.severity_id = s.id
        LEFT JOIN status st ON i.status_id = st.status_id
        LEFT JOIN barangays b ON i.barangay_id = b.barangay_id 
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
    
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Barangay</th>
                    <th>Address</th>
                    <th>Reported By</th>
                    <th>Time</th>
                    <th>Cause</th>
                    <th>Attachments</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['incident_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['incident_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['severity'] ?? 'Not Specified'); ?></td>
                        <td><?php echo htmlspecialchars($row['barangay']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
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
                
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>
