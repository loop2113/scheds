<?php
include 'db.php';
$currentYear = date('Y');
$nextYear = $currentYear + 1;

// Fetch distinct sections from the database
$sections = $conn->query("SELECT DISTINCT section FROM sections ORDER BY section");
$sectionOptions = '';
while ($section = $sections->fetch_assoc()) {
    $sectionOptions .= "<option value='{$section['section']}'>{$section['section']}</option>";
}

// Fetch distinct rooms from the database
$rooms = $conn->query("SELECT DISTINCT room FROM rooms ORDER BY room");
$roomOptions = '';
while ($room = $rooms->fetch_assoc()) {
    $roomOptions .= "<option value='{$room['room']}'>{$room['room']}</option>";
}



// Check if a section and room are selected
$selectedSection = isset($_GET['section']) ? $_GET['section'] : null;
$selectedRoom = isset($_GET['room']) ? $_GET['room'] : null;

// Additional condition for the SQL query based on selected room
$roomCondition = '';
if ($selectedRoom) {
    $roomCondition = " AND rooms.room = '$selectedRoom'";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>



</head>

<style>
     @media print {

        body {
            background-color: #f8f9fa;
        }

        /* table th,
        .table td {
            background-color: black !important; 
            color: red;
            padding: 8px;
           
        } */

            .table-container {
    overflow-x: hidden;
    width: 100%; /* Ensure it takes the full width */
    max-width: 100%; /* Adjust the maximum width as needed */
}
    body,
    .text-center,
    .btn,
    .evsu-logo {
        font-size: 10px;
    }

        .table th, .table td {
            padding: 4px;
            background-color:red !important;
        }

        .container {
        background-color: #ffffff;
        max-width:100%;
        padding: 20px;
        border-radius: 10px;
        /* box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1); */
        overflow-x: hidden;
        }

        /* .evsu-logo, */
        .btn,
        .h6,
        .h7,
        .div,
        .h4,
        .h5,
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


    /* Add custom styles to your timetable */
    body {
        background-color: #f8f9fa;
        color: #333;
    }
    .itsLabel, .form-select {
        margin-top:;
        margin-left:200px;
        width: 500px;
    }

    .container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        /* box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1); */
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
        background-color: #ffcc00 !important; /* Set your desired background color here */
        padding: 2px;
        margin: 0;
        line-height: 1.2;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .evsu-logo {
        float: left; /* Align the logo to the left */
        margin-right: -260px; /* Add some right margin for spacing */
        margin-left: 150px;
        margin-top: 10px;
    }
    .evsu-logo-engineer {
        float: right; /* Align the logo to the left */
        margin-right: 150px; /* Add some right margin for spacing */
        margin-left:  -260px;
        margin-top: 10px;
    }

    .h5 {
        float: left;
        margin-bottom: 5px;
    }

    .lunchtime {
    background-color: #bf1111 !important;
    color: white;
    width: 100%;
    height: 80px;
    display: flex;
    align-items: center; /* Vertically center align */
    justify-content: center; /* Horizontally center align */
}

    .btn {
        float: right;
    }

    .bg-danger {
        color: white;
    }

    @media only screen and (min-width : 1200px) {

    .container { max-width: 100%;}

    }

</style>
<?php 
$room = $_GET['room'];


if($room === ''){
    $room = 'All Room';
}

$selectedDepartment = "Engineering";
?>
<body>

<div class="row justify-content-center">
  <div class="col-6">
    <form action="" method="get" class="mb-3 itsLabel">
      <label for="section" class="form-label itsLabel">Select Room:</label>
      <select name="room" id="room" class="form-select" onchange="this.form.submit()">
        <option value=""><?= $room ?></option>
        <?php echo $roomOptions; ?>
      </select>
    </form>
  </div>
  <div class="col-6 d-flex align-items-center">
    <button class="btn btn-primary me-3" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
    <button class="btn btn-danger" id="download-pdf"><i class="fa-solid fa-print"></i> Download PDF</button>
    <div class="col d-flex justify-content-end">
    <a href="index.php" class="btn btn-primary me-3"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
  </div>
</div>







<div class="container">
<div class="row">
            <div class="col">
            <img src="assets/img/evsu_logo.png" width="30%" class="evsu-logo">
            </div>
            <div class="col">
                <center>
            <div class="text-center">Republic of the Philippines</div>
            <h6 class="text-center">EASTERN VISAYAS STATE UNIVERSITY</h6>
            <h6 class="text-center">TANAUAN CAMPUS</h6>
            <div class="text-center mb-2">Tanauan, Leyte</div>
            <h5 class="text-center mb-2">Room UTILIZATION</h5></center>
            </div>
            <div class="col">
            <?php 
            if ($selectedDepartment === 'Engineering'){
                $logoImg = '<img src="assets/img/engineering.jpg" style="border-radius:50%;" width="30%" class="evsu-logo-engineer">';
            } else {
                $logoImg = '';
            }
            
            ?>
            <?= $logoImg;?>
            
            </div><center><br>
            <div class="row">
                <div class="col"><b><?= $room;?></b></div>
                <div class="col"><div class=""><b>A.Y. <?php echo $currentYear; ?> - <?php echo $nextYear; ?></b> </div></div>
                <div class="col"><b>FIRST</b></div>
            </div>
            <div class="row">
                <div class="col">ROOM</div>
                <div class="col">SCHOOL YEAR</div>
                <div class="col">SEMESTER</div>
            </div>
            </center>
        </div>
        <br>
        
        <div class="table-responsive">
            <div class="timetable">
                    <?php
                    // Fetch all schedules from the database
                    $query = "SELECT * FROM schedules 
                    INNER JOIN faculty ON schedules.faculty_id = faculty.id
                    INNER JOIN subjects ON schedules.subject_id = subjects.id
                    INNER JOIN sections ON schedules.section_id = sections.id
                    INNER JOIN rooms ON schedules.room_id = rooms.id
                    WHERE 1 $roomCondition
                    ORDER BY weekday, start_time";
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
                    echo "<table class='table' border='1'><tr><th width='10%' style='background-color: #bf1111;'>Time</th><th style='background-color: #bf1111;'>Monday</th><th style='background-color: #bf1111;'>Tuesday</th><th style='background-color: #bf1111;'>Wednesday</th><th style='background-color: #bf1111;'>Thursday</th><th style='background-color: #bf1111;'>Friday</th><th style='background-color: #bf1111;'>Saturday</th><th style='background-color: #bf1111;'>Sunday</th></tr>";



                    foreach ($timeSlots as $timeSlot) {
                        echo "<tr>";
                        $start12PM = new DateTime('12:00:00');
                        $end1PM = new DateTime('13:00:00');
                        $startTimeSlot = new DateTime($timeSlot['start_time']);
                        $endTimeSlot = new DateTime($timeSlot['end_time']);
                        $timeSlotRange = [$startTimeSlot, $endTimeSlot];
                        echo "<td rowspan='2'>" . $timeSlot['start_time'] . ' <br>to<br> ' . $timeSlot['end_time'] . "</td>";

                        
                        for ($day = 0; $day < 7; $day++) {
                            echo "<td colspan='0'>";

                            // Filter schedules for the current day and slot
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
                                echo "<div class='schedule-cell'>" . $schedule['room'] .  '<br>' . $schedule['section'] . '<br> <b>' . $schedule['name'] . '</b><br>' . $schedule['starttime'] . ' to ' . $schedule['endtime'] . "</div>";
                            }

                            echo "</td>";
                        }
                        echo "</tr>";
                        echo "<tr>";

                        // for ($day = 0; $day < 7; $day++) {
                        //     echo "<td class='thirty-min-slot'>";

                        //     // Filter schedules for the current day and 30-minute slot
                        //     $schedulesForSlot = array_filter($timetable, function ($schedule) use ($day, $timeSlot) {
                        //         return $schedule['weekdayIndex'] === $day &&
                        //             ($schedule['startTimeSlot'] <= $timeSlot['slotIndex'] && $schedule['endTimeSlot'] > $timeSlot['slotIndex']);
                        //     });

                        //     foreach ($schedulesForSlot as $schedule) {
                        //         echo "<div class='schedule-cell'>" . $schedule['name'] . '<br>' . $schedule['starttime'] . ' to ' . $schedule['endtime'] . "</div>";
                        //     }

                        //     echo "</td>";
                        }
                        echo "</tr>";
                    //}

                    echo "</table>";
                    ?>
                    
                    <div class="container">
                    <div class="row" style="overflow:none;">
                        <div class="col">Prepared by:</div>
                        <div class="col">Recommending Approval:</div>
                        <div class="col"></div>
                        <div class="col">Approved:</div>
                    </div><br>
                    <div class="row">
                        <div class="col"><b>JOBELLE C. PADAL</b><br>Secretary, Enegineering Department</div>
                        <div class="col"><b>DANIEL AMIDA, D.M.</b><br>Head, BEM Department</div>
                        <div class="col"><b>VICENTE D. CARILLO JR., Ph.D.</b><br>Head, Engineering Department</div>
                        <div class="col"><b>RONALD B. MADERA Ph. D.</b><br>Campus Director</div>
                    </div><br>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col"><b>EGBERT G. DEL PILLAR, Ed.D.</b><br>Head, Technology Department</div>
                        <div class="col"><b>LORETA R. CINCO, M.A ED.</b><br>Head, Education Department</div>
                        <div class="col"></div>
                    </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

        <script>
            document.getElementById('download-pdf').addEventListener('click', function () {
    const element = document.querySelector('.container'); // Replace '.container' with the selector for your content

    // Options for the PDF generation (optional)
    const options = {
        margin: 0,
        filename: 'timetable.pdf',
        image: { type: 'jpeg', quality: 100  },
        html2canvas: { scale: 5 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };

    // Generate PDF from the specified element
    html2pdf()
        .from(element)
        .set(options)
        .save();
});
        </script>
</body>

</html>

