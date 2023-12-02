<?php
include 'db.php';
session_start();

$facultyId = $_SESSION['id']; // Assuming you have the faculty ID in the session after login

$query = "SELECT schedules.id, subjects.subject, sections.section, rooms.room, title, start_date, end_date, faculty.name FROM schedules INNER JOIN faculty ON schedules.faculty_id = faculty.id INNER JOIN subjects ON schedules.subject_id = subjects.id INNER JOIN sections ON schedules.section_id = sections.id INNER JOIN rooms ON schedules.room_id = rooms.id WHERE schedules.faculty_id = $facultyId";
$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $unixTimestamp = strtotime($row['start_date']);
    $unixTimestamp2 = strtotime($row['end_date']);
    $readableDateTime = date("H:i A", $unixTimestamp);
    $readableDateTime2 = date("H:i A", $unixTimestamp2);

    $titleWithDetails = $readableDateTime . '-' . $readableDateTime2;
    $events[] = [
        'id' => $row['id'],
        'title' => $titleWithDetails,
        'title2' => $row['title'],
        'start' => $row['start_date'],
        'end' => $row['end_date'],
        'name' => $row['name'],
        'subject' => $row['subject'],
        'section' => $row['section'],
        'room' => $row['room']
    ];
}

echo json_encode($events);
?>
