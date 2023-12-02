<?php
require_once('tcpdf/tcpdf.php');
include 'db.php';


$startTime = new DateTime('07:00:00');
$endTime = new DateTime('20:00:00');
$interval = new DateInterval('PT30M');

$timeSlots = array();
$currentSlot = clone $startTime;

while ($currentSlot <= $endTime) {
    $timeSlots[] = $currentSlot->format('h:i A');
    $currentSlot->add($interval);
}


$sql = "SELECT * FROM schedules
INNER JOIN faculty ON schedules.faculty_id = faculty.id
INNER JOIN subjects ON schedules.subject_id = subjects.id
INNER JOIN sections ON schedules.section_id = sections.id
INNER JOIN rooms ON schedules.room_id = rooms.id
ORDER BY weekday, start_time";
$result = $conn->query($sql);

$timetable = array_fill(0, count($timeSlots), array_fill(0, 7, null));

// Populate the timetable with fetched schedules
while ($row = $result->fetch_assoc()) {
    $startDateTime = new DateTime($row["start_time"]);
    $endDateTime = new DateTime($row["end_time"]);
    $weekdayIndex = $row["weekday"] - 1;

    $startIndex = null;
    $endIndex = null;

    foreach ($timeSlots as $index => $timeSlot) {
        $currentTime = DateTime::createFromFormat('h:i A', $timeSlot);
        if ($startDateTime <= $currentTime && $endDateTime >= $currentTime) {
            if ($startIndex === null) {
                $startIndex = $index;
            }
            $endIndex = $index;
        }
    }

    if ($startIndex !== null && $endIndex !== null) {
        for ($i = $startIndex; $i <= $endIndex; $i++) {
            $timetable[$i][$weekdayIndex] = implode('<br><b>', array($row["name"], $row["section"], $row["room"]));
        }
    }
}

$pdf = new TCPDF();
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();

$html = '<h2>Timetable</h2>';
$html .= '<table border="1"><tr><th width="15%">Time</th><th width="15%">Monday</th><th width="15%">Tuesday</th><th width="15%">Wednesday</th><th width="15%">Thursday</th><th width="15%">Friday</th><th width="15%">Saturday</th><th width="15%">Sunday</th></tr>';
foreach ($timeSlots as $index => $timeSlot) {
    $html .= '<tr><td>'.$timeSlot.'</td>';
    for ($day = 0; $day < 7; $day++) {
        $html .= '<td>';
        if (isset($timetable[$index][$day])) {
            $html .= $timetable[$index][$day];
        }
        $html .= '</td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('timetable.pdf', 'D');
?>
