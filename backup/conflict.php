<?php
if(isset($_POST['save'])){
    // Get start and end times from the form or request
    $start_date = $_POST['start_date']; // Assuming you get this from a form field
    $end_date = $_POST['end_date']; // Assuming you get this from a form field
    $title = $_POST['title'];
    $facultyId = $_POST['facultyId'];
    $subjectId = $_POST['subjectId'];
    $sectionId = $_POST['sectionId'];
    $roomId = $_POST['roomId'];

    $day_of_week = date('N', strtotime($start_date));

    // Check for time conflict
    $query = " SELECT * FROM schedules WHERE 
                ((start_date BETWEEN '$start_date' AND '$end_date' OR 
                end_date BETWEEN '$start_date' AND '$end_date') AND 
                (subject_id = '$subjectId' OR 
                faculty_id = '$facultyId' OR 
                section_id = '$sectionId' OR 
                room_id = '$roomId'))";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // There is a time conflict, handle it (e.g., show an error message)
        ?>
        <script>
            Swal.fire({
            title: 'Conflict',
            text: 'Another schedule with the same time and subject, already exists.',
            icon: 'error',
            showConfirmButton: false,
            timer: 2000
            }).then(function() {
            window.location.href = "";
            });
        </script>
        <?php
    } else {
        // No time conflict, proceed with inserting into the database
        $insert_query = "INSERT INTO schedules (title, start_date, end_date, faculty_id, subject_id, section_id, room_id) VALUES ('$title', '$start_date', '$end_date', '$facultyId', '$subjectId', '$sectionId', '$roomId')";
        mysqli_query($conn, $insert_query);
        ?>
        <script>
            Swal.fire({
            title: 'Success',
            text: 'Assigned Successfull',
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
            }).then(function() {
            window.location.href = "";
            });
        </script>
        <?php
    }
}
?>