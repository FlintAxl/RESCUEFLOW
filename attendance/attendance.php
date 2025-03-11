<?php 
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "rescuenet";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Time-IN (Mark Attendance)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_attendance'])) {
    $member_id = $_POST['member_id'];
    $shift_id = $_POST['shift_id'];

    if (!empty($member_id) && !empty($shift_id)) {
        // Insert new time-in
        $stmt = $conn->prepare("INSERT INTO attendance (member_id, shift_id, timestamp) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $member_id, $shift_id);

        if ($stmt->execute()) {
            $update_stmt = $conn->prepare("UPDATE shifts SET status = 'On Duty' WHERE shift_id = ?");
            $update_stmt->bind_param("i", $shift_id);
            $update_stmt->execute();
            $update_stmt->close();

            echo "<script>alert('Time-IN recorded successfully!'); window.location.href='attendance.php';</script>";
        } else {
            echo "<script>alert('Error marking Time-IN: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Handle Time-OUT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['timeout'])) {
    $member_id = $_POST['member_id'];
    $shift_id = $_POST['shift_id'];

    if (!empty($member_id) && !empty($shift_id)) {
        $stmt = $conn->prepare("UPDATE attendance SET time_out = NOW() WHERE member_id = ? AND shift_id = ? AND time_out IS NULL");
        $stmt->bind_param("ii", $member_id, $shift_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $update_stmt = $conn->prepare("UPDATE shifts SET status = 'Off Duty' WHERE shift_id = ?");
            $update_stmt->bind_param("i", $shift_id);
            $update_stmt->execute();
            $update_stmt->close();

            echo "<script>alert('Time-OUT recorded successfully!'); window.location.href='attendance.php';</script>";
        } else {
            echo "<script>alert('Error recording Time-OUT: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Handle Clear Time Record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_record'])) {
    $member_id = $_POST['member_id'];
    $shift_id = $_POST['shift_id'];

    if (!empty($member_id) && !empty($shift_id)) {
        $stmt = $conn->prepare("DELETE FROM attendance WHERE member_id = ? AND shift_id = ?");
        $stmt->bind_param("ii", $member_id, $shift_id);

        if ($stmt->execute()) {
            echo "<script>alert('Time record cleared successfully!'); window.location.href='attendance.php';</script>";
        } else {
            echo "<script>alert('Error clearing record: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Fetch members and their assigned shifts
$sql = "SELECT m.member_id, m.first_name, m.last_name, s.shift_id, s.start_time, s.end_time, 
            COALESCE(s.status, 'Off Duty') AS status, 
            a.timestamp, a.time_out
        FROM members m
        LEFT JOIN shifts s ON m.member_id = s.member_id
        LEFT JOIN attendance a ON m.member_id = a.member_id AND s.shift_id = a.shift_id
        ORDER BY a.timestamp DESC";

$result = $conn->query($sql);

// Display results in table
echo "<table border='1'>
<tr>
<th>Member Name</th>
<th>Shift Time</th>
<th>Shift Status</th>
<th>Time-IN</th>
<th>Time-OUT</th>
<th>Action</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</td>";
        echo "<td>" . ($row['start_time'] ? htmlspecialchars($row['start_time'] . " - " . $row['end_time']) : "No Shift Assigned") . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . ($row['timestamp'] ?? "Not Recorded") . "</td>";
        echo "<td>" . ($row['time_out'] ?? "Not Recorded") . "</td>";

        if (!empty($row['shift_id'])) {
            echo "<td>
                    <form method='post'>
                        <input type='hidden' name='member_id' value='" . htmlspecialchars($row['member_id']) . "'>
                        <input type='hidden' name='shift_id' value='" . htmlspecialchars($row['shift_id']) . "'>";

            if ($row['timestamp'] && !$row['time_out']) {
                echo "<button type='submit' name='timeout'>Time-OUT</button>";
            } else {
                echo "<button type='submit' name='mark_attendance'>Time-IN</button>";
            }

            echo "<button type='submit' name='clear_record'>Clear Time-Record</button>
                    </form>
                </td>";
        } else {
            echo "<td>No Shift</td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No members found</td></tr>";
}

echo "</table>";

// Close connection
$conn->close();
?>