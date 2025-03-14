<?php
require '../includes/config.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RescueNet');
$pdf->SetTitle('Incident Reports');

// Set margins
$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->AddPage();

// Add BFP Logo
$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/RESCUEFLOW/incidents/uploads/bfplgo.png';

$pdf->Image($logoPath, 85, 10, 40, 40, 'PNG', '', '', true);

// Add Header Text
$pdf->SetFont('Helvetica', 'B', 14);
$pdf->Ln(45); // Move below logo
$pdf->Cell(0, 10, 'Republic of the Philippines', 0, 1, 'C');
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(0, 10, 'Department of the Interior and Local Government', 0, 1, 'C');
$pdf->Cell(0, 10, 'BFP Taguig City - Fire Station 1', 0, 1, 'C');
$pdf->Cell(0, 10, 'Radian Road, Arca South ( Formerly FTI Complex), Western Bicutan, Taguig City, Taguig, Philippines', 0, 1, 'C');
$pdf->Cell(0, 10, 'Incident Reports', 0, 1, 'C');

// Line Break
$pdf->Ln(10);

// Fetch incidents
$sql = "SELECT i.incident_id, i.incident_type, 
               b.barangay_name, i.address, i.reported_by, 
               i.reported_time, s.level AS severity, 
               i.cause
        FROM incidents i
        LEFT JOIN barangays b ON i.barangay_id = b.barangay_id
        LEFT JOIN severity s ON i.severity_id = s.id
        ORDER BY i.reported_time DESC";

$result = $conn->query($sql);

// Table Header
$html = '<style>
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid black; padding: 6px; text-align: center; }
            th { background-color: #f2f2f2; font-weight: bold; }
         </style>
         <h3 style="text-align:center;">Incident Reports</h3>
         <table>
            <tr>
                <th>Incident ID</th>
                <th>Incident Type</th>
                <th>Severity</th>
                <th>Barangay</th>
                <th>Address</th>
                <th>Reported By</th>
                <th>Date</th>
                <th>Time</th>
                <th>Cause</th>
            </tr>';

// Add Table Data
while ($row = $result->fetch_assoc()) {
    $reported_time = $row['reported_time'];
    $formatted_date = date('Y-m-d', strtotime($reported_time));
    $formatted_time = date('h:i A', strtotime($reported_time));
    
    $html .= '<tr>
                <td>' . htmlspecialchars($row['incident_id']) . '</td>
                <td>' . htmlspecialchars($row['incident_type']) . '</td>
                <td>' . htmlspecialchars($row['severity'] ?? 'Not Specified') . '</td>
                <td>' . htmlspecialchars($row['barangay_name']) . '</td>
                <td>' . htmlspecialchars($row['address'] ?? 'N/A') . '</td>
                <td>' . htmlspecialchars($row['reported_by'] ?? 'Unknown') . '</td>
                <td>' . htmlspecialchars($formatted_date) . '</td>
                <td>' . htmlspecialchars($formatted_time) . '</td>
                <td>' . htmlspecialchars($row['cause'] ?? 'Not specified') . '</td>
              </tr>';
}

$html .= '</table>';

// Write content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Add Signature
$pdf->Ln(15);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 10, '_________________________', 0, 1, 'R');
$pdf->Cell(0, 10, 'Signature & Name of Officer', 0, 1, 'R');
$pdf->Cell(0, 10, 'Bureau of Fire Protection', 0, 1, 'R');

// Close connection
$conn->close();

// Output PDF to browser (force download)
$pdf->Output('incident_reportsBFP.pdf', 'D');

?>