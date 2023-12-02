<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schedId = $_POST['schedId'];

    $query = "DELETE FROM schedules WHERE schedule.id = $schedId";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Event deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting event']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>