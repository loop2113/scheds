<?php
require_once('tcpdf/tcpdf.php');
include 'db.php';

class CustomPDF extends TCPDF {
    public function Header() {
        // Add header content here
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Class Schedules', 0, false, 'C');
    }

    public function Footer() {
        // Add footer content here
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Fetch schedules from the database
$sql = "SELECT * FROM schedules
        INNER JOIN faculty ON schedules.faculty_id = faculty.id
        INNER JOIN subjects ON schedules.subject_id = subjects.id
        INNER JOIN sections ON schedules.section_id = sections.id
        INNER JOIN rooms ON schedules.room_id = rooms.id
        ORDER BY weekday, start_time";
$result = $conn->query($sql);

// Generate time slots from 07:00 AM to 09:00 AM with 30-minute intervals
$startTime = new DateTime('07:00:00');
$endTime = new DateTime('20:00:00');
$interval = new DateInterval('PT30M'); // 30 minutes interval

$timeSlots = array();
$currentSlot = clone $startTime;

while ($currentSlot <= $endTime) {
    $timeSlots[] = $currentSlot->format('h:i A');
    $currentSlot->add($interval);
}

// Create a timetable array to store schedules
$timetable = array_fill(0, count($timeSlots), array_fill(0, 7, null)); // Initialize timetable with null values

// Populate the timetable with fetched schedules
while ($row = $result->fetch_assoc()) {
    $startDateTime = new DateTime($row["start_time"]);
    $endDateTime = new DateTime($row["end_time"]);
    $weekdayIndex = $row["weekday"] - 1; // Subtract 1 to get the correct array index

    // Calculate the start and end indices for the timetable
    $startIndex = null;
    $endIndex = null;

    // Iterate through time slots to find the matching indices
    foreach ($timeSlots as $index => $timeSlot) {
        $currentTime = DateTime::createFromFormat('h:i A', $timeSlot);
        if ($startDateTime <= $currentTime && $endDateTime >= $currentTime) {
            if ($startIndex === null) {
                $startIndex = $index;
            }
            $endIndex = $index;
        }
    }

    // Fill timetable array with schedule details
    if ($startIndex !== null && $endIndex !== null) {
        for ($i = $startIndex; $i <= $endIndex; $i++) {
            $timetable[$i][$weekdayIndex] = implode('<br><b>', array($row["name"], $row["section"], $row["room"]));
        }
    }
}

// Create a new PDF document
$pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Schedules');
$pdf->SetSubject('Schedules PDF');
$pdf->SetKeywords('Schedules, PDF, TCPDF');

// Set default header data
$pdf->SetHeaderData('', 0, 'Schedules', '');

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set font
$pdf->SetFont('helvetica', '', 8);

// Add a page
$pdf->AddPage('L'); // 'L' for landscape

// Your existing code goes here
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(20, 15, 'Time', 1, 0, 'C', 1);
$pdf->Cell(35, 15, 'Mon', 1, 0, 'C', 1);
$pdf->Cell(35, 15, 'Tue', 1, 0, 'C', 1);
$pdf->Cell(35, 15, 'Wed', 1, 0, 'C', 1);
$pdf->Cell(35, 15, 'Thu', 1, 0, 'C', 1);
$pdf->Cell(35, 15, 'Fri', 1, 0, 'C', 1);
$pdf->Cell(35, 15, 'Sat', 1, 0, 'C', 1);
$pdf->Cell(35, 15, 'Sun', 1, 1, 'C', 1);

foreach ($timeSlots as $index => $timeSlot) {
    $pdf->Cell(20, 10, $timeSlot, 1, 0, 'C');
    for ($day = 0; $day < 7; $day++) {
        $pdf->Cell(35, 10, isset($timetable[$index][$day]) ? $timetable[$index][$day] : "", 1);
    }
    $pdf->Ln();
}

// Output PDF to browser
$pdf->Output('schedules.pdf', 'I');
?>
