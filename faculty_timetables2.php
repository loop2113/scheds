<?php
include 'db.php';
$currentYear = date('Y');
$nextYear = $currentYear + 1;

// Fetch distinct rooms from the database
$faculties = $conn->query("SELECT DISTINCT name FROM faculty ORDER BY name");
$facultyOptions = '';
while ($faculty = $faculties->fetch_assoc()) {
    $facultyOptions .= "<option value='{$faculty['name']}'>{$faculty['name']}</option>";
}
$selectedFaculty = isset($_GET['faculty']) ? $_GET['faculty'] : null;

// $getFaculty =  $conn->query("SELECT DISTINCT * FROM faculty WHERE name = $selectedFaculty");


$facultyCondition = '';
if ($selectedFaculty) {
    $facultyCondition = " AND faculty.name = '$selectedFaculty'";
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>

<style>
             @media print {
        body {
            font-size: 10pt;
        }

        .table th, .table td {
            padding: 4px;
            background-color:red !important;
        }

        .container {
            max-width: 100%;
        }

        /* .evsu-logo, */
        .btn,
        .h6,
        .form-select,
        .itsLabel {
            display: none;
        }

        /* Apply background color to table header */
        .table th {
            background-color: #bf1111 !important;
            color: black;
        }
    }
    
    body {
        background-color: #f8f9fa;
        color: #333;
    }

    .container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #007bff;
    }

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
        background-color: #ffcc00 !important;
        padding: 2px;
        margin: 0;
        line-height: 1.2;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .evsu-logo {
        float: left;
        margin-right: -260px;
        margin-left: 150px;
        margin-top: 10px;
    }

    .h5 {
        float: left;
        margin-bottom: 5px;
    }

    .btn {
        float: right;
    }

    .lunchtime {
    background-color: #bf1111 !important;
    color: black;
    width: 100%;
    height: 80px;
    display: flex;
    align-items: center; /* Vertically center align */
    justify-content: center; /* Horizontally center align */
}

    .bg-danger {
        color: white;
    }

    @media only screen and (min-width : 1200px) {

    .container { max-width: 1500px; }

    }

</style>
<?php 
$facultyname = $_GET['faculty']
?>
<body>
    <div class="container">
        <img src="assets/img/evsu_logo.png" width="10%" class="evsu-logo">
        <button class="btn btn-primary" onclick=window.print()><i class="fa-solid fa-print"></i></button>
        <h6 class="text-center">Republic of the Philippines</h6>
        <h5 class="text-center">EASTERN VISAYAS STATE UNIVERSITY</h5>
        <h5 class="text-center">TANAUAN CAMPUS</h5>
        <h6 class="text-center mb-5">Tanauan, Leyte</h6>
        <h2 class="text-center mb-4">FACULTY SCHEDULE <?= strtoupper($facultyname) ?></h2>
        <h5 class="mb-5">A.Y. <?php echo $currentYear; ?> - <?php echo $nextYear; ?></h5>
        <form action="" method="get" class="mb-3">
            <label for="faculty" class="form-label">Select faculty:</label>
            <select name="faculty" id="faculty" class="form-select" onchange="this.form.submit()">
                <option value="" <?php echo !$selectedFaculty ? 'selected' : ''; ?>>All Faculty</option>
                <?php echo $facultyOptions; ?>
            </select>
        </form>
        <!-- <form action="" method="get" class="mb-3">
            <label for="room" class="form-label">Select Room:</label>
            <select name="room" id="room" class="form-select" onchange="this.form.submit()">
                <option value="" <?php echo !$selectedRoom ? 'selected' : ''; ?>>All Rooms</option>
                <?php echo $roomOptions; ?>
            </select>
        </form> -->
        <div class="table-responsive">
            <div class="timetable">
                <?php
                    
                    $query = "SELECT * FROM schedules 
                    INNER JOIN faculty ON schedules.faculty_id = faculty.id
                    INNER JOIN subjects ON schedules.subject_id = subjects.id
                    INNER JOIN sections ON schedules.section_id = sections.id
                    INNER JOIN rooms ON schedules.room_id = rooms.id 
                    WHERE 1 $facultyCondition

                    ORDER BY weekday, start_time";
                    $result = $conn->query($query);
                    
                    
                    $timetable = array();

                    while ($row = $result->fetch_assoc()) {
                        $weekday = $row['weekday'];
                        $startTime = new DateTime($row['start_time']);
                        $endTime = new DateTime($row['end_time']);

                        $weekdayIndex = $weekday - 1;

                        $startTimeSlot = floor($startTime->getTimestamp() / (30 * 60));
                        $endTimeSlot = floor($endTime->getTimestamp() / (30 * 60));
                        $timetable[] = [
                            'start_time' => $startTime->format('h:i A'),
                            'starttime' => $startTime->format('h:i'),
                            'end_time' => $endTime->format('h:i A'),
                            'endtime' => $endTime->format('h:i'),
                            'title' => $row['title'],
                            'name' => $row['name'],
                            'room' => $row['room'],
                            'section' => $row['section'],
                            'subject' => $row['subject'],
                            'weekdayIndex' => $weekdayIndex,
                            'startTimeSlot' => $startTimeSlot,
                            'endTimeSlot' => $endTimeSlot
                        ];
                    }


                    $startTime = new DateTime('07:00:00');
                    $endTime = new DateTime('20:00:00');
                    $interval = new DateInterval('PT1H');

                    $timeSlots = array();
                    $currentSlot = clone $startTime;

                    while ($currentSlot < $endTime) {
                        $endTimeSlot = clone $currentSlot;
                        $endTimeSlot->add($interval);

                        $timeSlots[] = [
                            'start_time' => $currentSlot->format('h:i A'),
                            'end_time' => $endTimeSlot->format('h:i A'),
                            'slotIndex' => floor($currentSlot->getTimestamp() / (30 * 60))
                        ];
                        $currentSlot->add($interval);
                    }

                    echo "<table class='table' border='1'><tr><th width='10%' style='background-color: #bf1111;'>Time</th><th style='background-color: #bf1111;'>Monday</th><th style='background-color: #bf1111;'>Tuesday</th><th style='background-color: #bf1111;'>Wednesday</th><th style='background-color: #bf1111;'>Thursday</th><th style='background-color: #bf1111;'>Friday</th><th style='background-color: #bf1111;'>Saturday</th><th style='background-color: #bf1111;'>Sunday</th></tr>";

                    foreach ($timeSlots as $timeSlot) {
                        echo "<tr>";
                        $start12PM = new DateTime('12:00:00');
                        $end1PM = new DateTime('13:00:00');
                        $startTimeSlot = new DateTime($timeSlot['start_time']);
                        $endTimeSlot = new DateTime($timeSlot['end_time']);
                        $timeSlotRange = [$startTimeSlot, $endTimeSlot];
                        echo "<td rowspan='2'>" . $timeSlot['start_time'] . ' - ' . $timeSlot['end_time'] . "</td>";

                        for ($day = 0; $day < 7; $day++) {
                            echo "<td colspan='1'>";

                            $schedulesForSlot = array_filter($timetable, function ($schedule) use ($day, $timeSlot) {
                                return $schedule['weekdayIndex'] === $day &&
                                    ($schedule['startTimeSlot'] <= $timeSlot['slotIndex'] && $schedule['endTimeSlot'] > $timeSlot['slotIndex']);
                            });

                                if ($startTimeSlot >= $start12PM && $endTimeSlot <= $end1PM) {
                                    // echo "<td class='schedule-cell lunchtime' colspan='7'>LUNCH TIME</td>";
                                    // echo "</tr><tr>";
                                    // break;
                                    echo "<div class='schedule-cell lunchtime' colspan='7'><b>LUNCH</b></div>";
                                    // break;
                                }

                            foreach ($schedulesForSlot as $schedule) {
                                echo "<div class='schedule-cell'>" . $schedule['room'] . '<br>' . $schedule['section'] . '<br>' . $schedule['subject'] . "<br><b>"  . $schedule['name'] . '</b><br>' . $schedule['starttime'] . ' to ' . $schedule['endtime'] ."</div>";
                            }

                            echo "</td>";
                        }
                        echo "</tr><tr>";

                        // for ($day = 0; $day < 7; $day++) {
                        //     echo "<td class='thirty-min-slot'>";

                        //     $schedulesForSlot = array_filter($timetable, function ($schedule) use ($day, $timeSlot) {
                        //         return $schedule['weekdayIndex'] === $day &&
                        //             ($schedule['startTimeSlot'] <= $timeSlot['slotIndex'] && $schedule['endTimeSlot'] > $timeSlot['slotIndex']);
                        //     });

                        //     foreach ($schedulesForSlot as $schedule) {
                        //         echo "<div class='schedule-cell'>"."</div>";
                        //     }

                        //     echo "</td>";
                        // }
                        // echo "</tr>";
                    }

                    echo "</table>";
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

</html>

