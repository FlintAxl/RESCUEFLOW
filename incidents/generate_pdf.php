<?php
require '../includes/config.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
// Create a new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RescueNet');
$pdf->SetTitle('Incident Reports');
$pdf->SetHeaderData('', 0, 'Incident Reports', '');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Fetch incidents
$sql = "SELECT i.incident_id, i.incident_type, i.location, 
               i.reported_by, i.reported_time, 
               s.level AS severity, i.cause
        FROM incidents i
        LEFT JOIN severity s ON i.severity_id = s.id
        ORDER BY i.reported_time DESC";
$result = $conn->query($sql);

// Table Header
$html = '<h2>Incident Reports</h2>
        <table border="1" cellspacing="0" cellpadding="5">
            <tr style="background-color:#f2f2f2;">
                <th><b>Incident ID</b></th>
                <th><b>Incident Type</b></th>
                <th><b>Severity</b></th>
                <th><b>Location</b></th>
                <th><b>Reported By</b></th>
                <th><b>Reported Time</b></th>
                <th><b>Cause</b></th>
            </tr>';

// Add table data
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['incident_id']) . '</td>
                <td>' . htmlspecialchars($row['incident_type']) . '</td>
                <td>' . htmlspecialchars($row['severity'] ?? 'Not Specified') . '</td>
                <td>' . htmlspecialchars($row['location']) . '</td>
                <td>' . htmlspecialchars($row['reported_by'] ?? 'Unknown') . '</td>
                <td>' . htmlspecialchars($row['reported_time']) . '</td>
                <td>' . htmlspecialchars($row['cause'] ?? 'Not specified') . '</td>
              </tr>';
}

$html .= '</table>';

// Write content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Close connection
$conn->close();

// Output PDF to browser (force download)
$pdf->Output('incident_reports.pdf', 'D');
?>
