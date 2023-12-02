<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $facultyId = $_GET['facultyId'];

    // Prepare SQL query to fetch faculty schedules based on $facultyId
    $sql = "SELECT schedules.id, subjects.subject, sections.section, rooms.room, title, start_date, end_date, faculty.name FROM schedules INNER JOIN faculty ON schedules.faculty_id = faculty.id INNER JOIN subjects ON schedules.subject_id = subjects.id INNER JOIN sections ON schedules.section_id = sections.id INNER JOIN rooms ON schedules.room_id = rooms.id WHERE schedules.faculty_id = $facultyId";

    $result = $conn->query($sql);

    // Process query results and prepare events array
    $events = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'start' => $row['start_date'],
                'end' => $row['end_date'],
                'name' => $row['name'],
                'subject' => $row['subject'],
                'section' => $row['section'],
                'room' => $row['room']
            );
        }
    }

    // Close the database connection
    $conn->close();

    // Return data as JSON
    echo json_encode($events);
}
?>
