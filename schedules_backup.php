<?php
include 'db.php';
session_start();
if(!isset($_SESSION["username"])) {
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Schedules</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="vendor/fontawesome/js/all.js" crossorigin="anonymous"></script>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
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

.lunchtime {
        background-color: red !important;
        color: white;
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
    <div id="layoutSidenav">
        <?php include 'sidenav.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Schedules <button style="float: right;" type="button" class="btn btn-primary btns" data-bs-toggle="modal" data-bs-target="#addSched">+ Add Schedule</button></h1>
                    <div class="timetable">
                    <?php
                    // Fetch all schedules from the database
                    $query = "SELECT * FROM schedules";
                    $result = $conn->query($query);

                    // Initialize an array to hold the fetched schedules
                    $timetable = array();

                    // Fetch schedules data and store it in the timetable array
                    while ($row = $result->fetch_assoc()) {
                        $weekday = $row['weekday'];
                        $startTime = new DateTime($row['start_time']);
                        $endTime = new DateTime($row['end_time']);

                        // Convert weekday to an index (0-6)
                        $weekdayIndex = $weekday - 1;

                        // Calculate the time slots for the schedule
                        $startTimeSlot = floor($startTime->getTimestamp() / (30 * 60)); // Start time slot in 30-minute intervals
                        $endTimeSlot = floor($endTime->getTimestamp() / (30 * 60)); // End time slot in 30-minute intervals

                        // Store the data in the timetable array based on start and end times
                        $timetable[] = [
                            'start_time' => $startTime->format('h:i A'),
                            'end_time' => $endTime->format('h:i A'),
                            'title' => $row['title'],
                            'weekdayIndex' => $weekdayIndex,
                            'startTimeSlot' => $startTimeSlot,
                            'endTimeSlot' => $endTimeSlot
                        ];
                    }

                    // Generate time slots from 07:00 to 20:00 with 30-minute intervals
                    $startTime = new DateTime('07:00:00');
                    $endTime = new DateTime('20:00:00');
                    $interval = new DateInterval('PT1H'); // 30-minute interval

                    $timeSlots = array();
                    $currentSlot = clone $startTime;

                    while ($currentSlot < $endTime) {
                        $endTimeSlot = clone $currentSlot;
                        $endTimeSlot->add($interval);

                        $timeSlots[] = [
                            'start_time' => $currentSlot->format('h:i A'),
                            'end_time' => $endTimeSlot->format('h:i A'),
                            'slotIndex' => floor($currentSlot->getTimestamp() / (30 * 60)) // Current time slot index
                        ];
                        $currentSlot->add($interval);
                    }

                    // Display the timetable
                    echo "<table class='table' border='1'><tr><th width='10%'>Time</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>";

                    foreach ($timeSlots as $timeSlot) {
                        echo "<tr>";
                        echo "<td rowspan='2'>" . $timeSlot['start_time'] . ' - ' . $timeSlot['end_time'] . "</td>";

                        for ($day = 0; $day < 7; $day++) {
                            echo "<td colspan='1' rowspan='1'>";

                            // Filter schedules for the current day and slot
                            $schedulesForSlot = array_filter($timetable, function ($schedule) use ($day, $timeSlot) {
                                return $schedule['weekdayIndex'] === $day &&
                                    ($schedule['startTimeSlot'] <= $timeSlot['slotIndex'] && $schedule['endTimeSlot'] > $timeSlot['slotIndex']);
                            });

                            foreach ($schedulesForSlot as $schedule) {
                                echo "<div class='schedule-cell'>" . $schedule['title'] . "</div>";
                            }

                            echo "</td>";
                        }
                        echo "</tr><tr>";

                        for ($day = 0; $day < 7; $day++) {
                            echo "<td class='thirty-min-slot'>";

                            // Filter schedules for the current day and 30-minute slot
                            $schedulesForSlot = array_filter($timetable, function ($schedule) use ($day, $timeSlot) {
                                return $schedule['weekdayIndex'] === $day &&
                                    ($schedule['startTimeSlot'] <= $timeSlot['slotIndex'] && $schedule['endTimeSlot'] > $timeSlot['slotIndex']);
                            });

                            foreach ($schedulesForSlot as $schedule) {
                                echo "<div class='schedule-cell'>" . $schedule['title'] . "</div>";
                            }

                            echo "</td>";
                        }
                        echo "</tr>";
                    }

                    echo "</table>";
                    ?>
                    </div>
                </div>
            </main>
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
                                <select class="form-control" name="semester_id" required>
                                <option disabled selected>Select Semester</option>
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
                                <label>Faculty</label>
                                <select name="facultyId" id="show2" class="form-control" required>
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
                                <label for="weekday">Weekday:</label><br>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="vendor/sweetalert2/sweetalert2.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
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
                    url: 'fetch_faculty.php',
                    data: {id:Stdid},
                    success: function(data)
                    {
                        $('#show2').html(data);
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
        <script>
            $('#mySelect2').select2({
                dropdownParent: $('#addSched')
            });
        </script>
</body>
</html>
<?php
if (isset($_POST['save'])) {

    // Sanitize and validate input (consider using functions like mysqli_real_escape_string or prepared statements)
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $facultyId = mysqli_real_escape_string($conn, $_POST['facultyId']);
    $subjectId = mysqli_real_escape_string($conn, $_POST['subjectId']);
    $sectionId = mysqli_real_escape_string($conn, $_POST['sectionId']);
    $roomId = mysqli_real_escape_string($conn, $_POST['roomId']);
    $semester_id = mysqli_real_escape_string($conn, $_POST['semester_id']);
    $weekdays = isset($_POST['weekday']) ? $_POST['weekday'] : [];

    // Convert start_time and end_time to hours and minutes separately
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
                    window.location.href = "schedules.php";
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
                            window.location.href = "schedules.php";
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
                    window.location.href = "schedules.php";
                });
            </script>
            <?php
        }
    }
}
?>