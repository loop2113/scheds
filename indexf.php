<?php
include 'db.php';

session_start();
 session_destroy();
if(!isset($_SESSION["emp_id"]))
header("location:loginf.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="vendor/select2/select2.min.css" rel="stylesheet" />
</head>
<style>
.table {
    border-collapse: collapse;
    width: 100%;
}

.table th, .table td {
    border: 1px solid black;
    padding: 8px;
    text-align: center;
}

.schedule-cell {
    background-color: #ffcc00 !important; /* Set your desired background color here */
    padding: 2px;
    margin: 0;
    line-height: 1.2;
}
.btns {
    float: right;
    box-shadow: 10px 10px 5px -1px rgba(0,0,0,0.75);
    -webkit-box-shadow: 10px 10px 5px -1px rgba(0,0,0,0.75);
    -moz-box-shadow: 10px 10px 5px -1px rgba(0,0,0,0.75);
}
</style>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
        <div id="layoutSidenav_content">
            <main style="margin-top: 100px;">
                <div class="container-fluid px-4">
                    <h1 class="mt-4">My Schedules <button style="float: right;" type="button" class="btn btn-primary btns" data-bs-toggle="modal" data-bs-target="#addSched">+ Add Schedule</button></h1>
                    <div class="timetable">
                        <?php
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

                        // Fetch schedules from the database
                        $sql = "SELECT * FROM schedules
                        INNER JOIN faculty ON schedules.faculty_id = faculty.id
                        INNER JOIN subjects ON schedules.subject_id = subjects.id
                        INNER JOIN sections ON schedules.section_id = sections.id
                        INNER JOIN rooms ON schedules.room_id = rooms.id
                        WHERE faculty_id = '" . $_SESSION['id'] . "'
                        ORDER BY weekday, start_time";
                        $result = $conn->query($sql);

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
                                    $timetable[$i][$weekdayIndex] = implode('<br><b>', array($row["subject"], $row["section"], $row["room"]));
                                }
                            }
                        }
                        ?>
                        <?php
                        echo "<table class='table' border='1'><tr><th width='10%'>Time</th><th width='10%'>Monday</th><th width='10%'>Tuesday</th><th width='10%'>Wednesday</th><th width='10%'>Thursday</th><th width='10%'>Friday</th><th width='10%'>Saturday</th><th width='10%'>Sunday</th></tr>";
                        foreach ($timeSlots as $index => $timeSlot) {
                            echo "<tr><td>".$timeSlot."</td>";
                            for ($day = 0; $day < 7; $day++) {
                                echo "<td";
                                if (isset($timetable[$index][$day])) {
                                    echo " class='schedule-cell'";
                                }
                                echo ">";
                                echo isset($timetable[$index][$day]) ? $timetable[$index][$day] : "";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                        ?>
                    </div>



                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
                <script src="js/datatables-simple-demo.js"></script>
                <script src="vendor/sweetalert2/sweetalert2.js"></script>
                <script>
                    $(document).ready(function(){
                        $('#selectID').change(function(){
                        var Stdid = $('#selectID').val();

                            $.ajax({
                            type: 'POST',
                            url: 'fetch_subjects.php',
                            data: {id:Stdid},
                            success: function(data)
                            {
                                $('#show').html(data);
                            }
                            });

                            $.ajax({
                            type: 'POST',
                            url: 'fetch_section.php',
                            data: {id:Stdid},
                            success: function(data)
                            {
                                $('#show3').html(data);
                            }
                            });

                        });
                    });
                </script>

            </div>
        </div>
    </div>

        <div class="modal fade" id="addSched" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="faculty">Title:</label>
                                <input type="text" class="form-control" name="title" required>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Semester</label>
                                <select name="semester_id" class="form-control" required>
                                <option disabled selected>Semester</option>
                                <?php
                                $sql = "SELECT * FROM semesters";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)) {?>
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['semester_name'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Course</label>
                                <select id="selectID" class="form-control" required>
                                <option>Select Course</option>
                                <?php
                                $sql = "SELECT * FROM courses";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)) {?>
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['course_name'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Subject</label>
                                <select name="subjectId" id="show" class="form-control" required>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Section</label>
                                <select name="sectionId" id="show3" class="form-control" required>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Room</label>
                                <select name="roomId" class="form-control" required>
                                <?php
                                $sql = "SELECT * FROM rooms";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)) {?>
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['room'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="weekday">Weekday:</label>
                                <select id="mySelect2" style="width: 100%;" name="weekday[]" multiple="multiple" required>
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="faculty">Start Time:</label>
                                <input type="time" class="form-control" name="start_time" required>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="faculty">End Time:</label>
                                <input type="time" class="form-control" name="end_time" required>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="save">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="js/jquery.min.js"></script>
        <script src="vendor/select2/select2.min.js"></script>
        <script>
            $('#mySelect2').select2({
                dropdownParent: $('#addSched')
            });
        </script>
</body>
</html>
<?php
if (isset($_POST['save'])) {

    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $facultyId = mysqli_real_escape_string($conn, $_SESSION['id']);
    $subjectId = mysqli_real_escape_string($conn, $_POST['subjectId']);
    $sectionId = mysqli_real_escape_string($conn, $_POST['sectionId']);
    $roomId = mysqli_real_escape_string($conn, $_POST['roomId']);
    $semester_id = mysqli_real_escape_string($conn, $_POST['semester_id']);
    $weekdays = isset($_POST['weekday']) ? $_POST['weekday'] : [];

    $start_hour = date('H', strtotime($start_time));
    $start_minute = date('i', strtotime($start_time));
    $end_hour = date('H', strtotime($end_time));
    $end_minute = date('i', strtotime($end_time));

    // Check for lunchtime conflict
    if (($start_hour == 12 && $start_minute >= 0 && $start_minute <= 59) || ($end_hour == 12 && $end_minute >= 0 && $end_minute <= 59)) {
        // There is a lunchtime conflict, handle it (e.g., show an error message)
        ?>
        <script>
            Swal.fire({
                title: 'Conflict',
                text: 'Scheduling during lunchtime is not allowed.',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000
            }).then(function() {
                window.location.href = "";
            });
        </script>
        <?php
    } else {
        // Check for conflicts with existing schedules based on specified criteria
        $conflict = false;

        foreach ($weekdays as $weekday) {
            $query = "SELECT * FROM schedules WHERE
                FIND_IN_SET('$weekday', weekday) > 0 AND
                (
                    (DATE_FORMAT(start_time, '%H:%i') < '$end_time' AND DATE_FORMAT(end_time, '%H:%i') > '$start_time') OR
                    (DATE_FORMAT('$start_time', '%H:%i') < end_time AND DATE_FORMAT('$end_time', '%H:%i') > start_time)
                ) AND
                (
                    room_id = '$roomId' OR
                    faculty_id = '$facultyId' OR
                    section_id = '$sectionId' OR
                    subject_id = '$subjectId' OR
                    (
                        DATE_FORMAT('$start_time', '%H:%i') BETWEEN DATE_FORMAT(start_time, '%H:%i') AND DATE_FORMAT(end_time, '%H:%i') OR
                        DATE_FORMAT('$end_time', '%H:%i') BETWEEN DATE_FORMAT(start_time, '%H:%i') AND DATE_FORMAT(end_time, '%H:%i')
                    )
                )";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // There is a conflict with other schedules
                $conflict = true;
                break;
            }
        }

        if ($conflict) {
            // Handle conflict (e.g., show an error message)
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
                Swal.fire({
                    title: 'Conflict',
                    text: 'Another schedule with the same time, weekday, or resources already exists.',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000
                }).then(function() {
                    window.location.href = "indexf.php";
                });
            </script>
            <?php
        } else {
            // No conflicts, proceed with inserting into the database
            foreach ($weekdays as $weekday) {
                $weekdayString = mysqli_real_escape_string($conn, $weekday);
                $insert_query = "INSERT INTO schedules (weekday, start_time, end_time, title, faculty_id, subject_id, section_id, room_id, semester_id) VALUES ('$weekdayString', '$start_time', '$end_time', '$title', '$facultyId', '$subjectId', '$sectionId', '$roomId', '$semester_id')";
                $query2 = mysqli_query($conn, $insert_query);

                if (!$query2) {
                    // Handle insertion error
                    ?>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                    <script>
                        Swal.fire({
                            title: 'Error',
                            text: 'Error inserting data into the database.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function() {
                            window.location.href = "indexf.php";
                        });
                    </script>
                    <?php
                    break;
                }
            }

            // Show success message
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
                Swal.fire({
                    title: 'Success',
                    text: 'Data Inserted Successfully',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "indexf.php";
                });
            </script>
            <?php
        }
    }
}
?>