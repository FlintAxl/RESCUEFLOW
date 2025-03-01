<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust this path if necessary

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

// Fetch emergency details for the carousel (swipable history)
$emergencyHistoryQuery = "SELECT * FROM emergency_details ORDER BY timestamp DESC";
$emergencyHistoryResult = mysqli_query($conn, $emergencyHistoryQuery);
$emergencyHistory = [];
while ($row = mysqli_fetch_assoc($emergencyHistoryResult)) {
    $emergencyHistory[] = $row;
}

// Handle Dispatch Submission
if (isset($_POST["submit_location"])) {
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $status_id = 2; // Default to "In progress"
    $dispatched_unit = "Firetruck";

    $sql = "INSERT INTO dispatches (location, dispatched_unit, status_id) VALUES ('$location', '$dispatched_unit', '$status_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>Location saved successfully!</p>";

        // Fetch on-duty members
        $onDutyQuery = "SELECT m.email FROM shifts s 
                        JOIN members m ON s.member_id = m.member_id 
                        WHERE NOW() BETWEEN s.start_time AND s.end_time";
        $onDutyResult = mysqli_query($conn, $onDutyQuery);

        // Email details
        $subject = "🚨 Emergency Alert: Dispatch to $location";
        $message = "An emergency is ongoing at $location. Firetruck dispatched.\n\nPlease take necessary actions.";

        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'flintaxl.celetaria@gmail.com';
            $mail->Password = 'whif dedq ytly ryfo';  // App Password, NOT your actual password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('flintaxl.celetaria@gmail.com', 'RescueFlow Dispatch');
            $mail->isHTML(false);  // Plain text email

            // Send email to each on-duty member
            while ($row = mysqli_fetch_assoc($onDutyResult)) {
                $mail->addAddress($row['email']);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->send();
                $mail->clearAddresses();  // Clear addresses for the next email
            }
            echo "<p style='color: green;'>Emails sent successfully!</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Mailer Error: " . $mail->ErrorInfo . "</p>";
        }
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

// Handle emergency data entry
if (isset($_POST['submit_emergency_info'])) {
    $what = mysqli_real_escape_string($conn, $_POST['what']);
    $where = mysqli_real_escape_string($conn, $_POST['where']);
    $why = mysqli_real_escape_string($conn, $_POST['why']);
    $caller_name = mysqli_real_escape_string($conn, $_POST['caller_name']);
    $caller_phone = mysqli_real_escape_string($conn, $_POST['caller_phone']);
    $dispatch_id = $_POST['dispatch_id']; 

    $sql = "INSERT INTO emergency_details (dispatch_id, what, `where`, `why`, caller_name, caller_phone) 
    VALUES ('$dispatch_id', '$what', '$where', '$why', '$caller_name', '$caller_phone')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>Emergency information saved successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firefighter Dispatch System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 900px;
        }
        h2 {
            margin-top: 30px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
        }
        .form-control {
            margin-bottom: 10px;
        }
        .alert {
            margin-top: 20px;
        }
        .carousel-item {
            padding: 10px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="text-center mb-4">Firefighter Dispatch System</h1>

    <!-- Dispatch Location Form -->
    <div class="card p-4 mb-4">
        <h2>Dispatch Location</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="location" placeholder="Enter Dispatch Location" required>
            </div>
            <button type="submit" name="submit_location" class="btn btn-success w-100">Dispatch</button>
        </form>
    </div>

    <!-- Update Dispatch Status Form -->
    <div class="card p-4 mb-4">
        <h2>Update Dispatch Status</h2>
        <form method="POST">
            <div class="mb-3">
                <select name="dispatch_id" class="form-select" required>
                    <?php
                    $dispatchQuery = "SELECT * FROM dispatches WHERE status_id != 3 ORDER BY dispatched_at DESC";
                    $dispatchResult = mysqli_query($conn, $dispatchQuery);
                    while ($dispatch = mysqli_fetch_assoc($dispatchResult)) {
                        echo "<option value='{$dispatch['disp_id']}'>{$dispatch['location']} (Status: {$dispatch['status_id']})</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <select name="status" class="form-select" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo $status['status_id']; ?>"><?php echo $status['status_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="update_status" class="btn btn-primary w-100">Update Status</button>
        </form>
    </div>

    <!-- Emergency Details Form -->
    <div class="card p-4 mb-4">
        <h2>Emergency Details Input</h2>
        <form method="POST">
            <input type="hidden" name="dispatch_id" value="1">
            <div class="mb-3">
                <label for="what">What happened?</label>
                <textarea name="what" placeholder="Describe the emergency" required></textarea>
            </div>
            <div class="mb-3">
                <label for="where">Where did it happen?</label>
                <textarea name="where" placeholder="Provide the location" required></textarea>
            </div>
            <div class="mb-3">
                <label for="why">Why is this an emergency?</label>
                <textarea name="why" placeholder="Explain the situation" required></textarea>
            </div>
            <div class="mb-3">
                <label for="caller_name">Caller Name</label>
                <input type="text" class="form-control" name="caller_name" placeholder="Enter caller's name" required>
            </div>
            <div class="mb-3">
                <label for="caller_phone">Caller Phone Number</label>
                <input type="text" class="form-control" name="caller_phone" placeholder="Enter caller's phone number" required>
            </div>
            <button type="submit" name="submit_emergency_info" class="btn btn-warning w-100">Save Emergency Info</button>
        </form>
    </div>

    <!-- Button to trigger emergency history view -->
    <div class="card p-4 mb-4">
        <h2>View Emergency History</h2>
        <button class="btn btn-info w-100" data-bs-toggle="collapse" data-bs-target="#emergencyHistory" aria-expanded="false" aria-controls="emergencyHistory">
            View Emergency History
        </button>

        <!-- Emergency History (Carousel) -->
        <div class="collapse mt-4" id="emergencyHistory">
            <div id="historyCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($emergencyHistory as $index => $record): ?>
                        <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                            <div class="card p-4">
                                <h5><?php echo "Caller: " . htmlspecialchars($record['caller_name']); ?></h5>
                                <p><strong>What:</strong> <?php echo htmlspecialchars($record['what']); ?></p>
                                <p><strong>Where:</strong> <?php echo htmlspecialchars($record['where']); ?></p>
                                <p><strong>Why:</strong> <?php echo htmlspecialchars($record['why']); ?></p>
                                <p><strong>Caller Phone:</strong> <?php echo htmlspecialchars($record['caller_phone']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#historyCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#historyCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Scroll Buttons -->
            <div class="mt-3 text-center">
                <button class="btn btn-primary" id="scrollPrev">Scroll Previous</button>
                <button class="btn btn-primary" id="scrollNext">Scroll Next</button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Scroll to previous item in the carousel
    document.getElementById('scrollPrev').addEventListener('click', function () {
        var carousel = new bootstrap.Carousel(document.getElementById('historyCarousel'));
        carousel.prev();
    });

    // Scroll to next item in the carousel
    document.getElementById('scrollNext').addEventListener('click', function () {
        var carousel = new bootstrap.Carousel(document.getElementById('historyCarousel'));
        carousel.next();
    });
</script>

</body>
</html>
