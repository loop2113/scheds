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

$faculty = $_GET['faculty'];

if($faculty === ''){
    $faculty = 'All Faculty';
}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>


<style>
         @media print {
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
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        overflow-x: hidden;
        }

        /* .evsu-logo, */
        .btn,
        .h6,
        .h7,
        .h8,
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
        overflow-x: hidden;
    }

    .container {
        background-color: #ffffff;
        max-width:100%;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        
    }

    .itsLabel, .form-select {
        margin-top:;
        margin-left:200px;
        width: 500px;
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
        padding: 4px;
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

    .container { max-width: 100%; }

    }

</style>

<body>

    <?php 

$selectedFaculty = '';
$selectedFacultyId = '';
$selectedDepartment = '';
$selectedRoom = '';
$selectedBuilding = '';
$selectedSemester = '';


$getSectionSchedule = $conn->query("SELECT * FROM schedules
INNER JOIN faculty ON schedules.faculty_id = faculty.id
INNER JOIN subjects ON schedules.subject_id = subjects.id
INNER JOIN sections ON schedules.section_id = sections.id
INNER JOIN rooms ON schedules.room_id = rooms.id 
INNER JOIN department ON schedules.department_id = department.id 
INNER JOIN semesters ON schedules.semester_id = semesters.id 
WHERE 1 $facultyCondition"
);
if ($getSectionSchedule) {
    while ($row = $getSectionSchedule->fetch_assoc()) {
        $selectedFaculty = $row['name'];
        $selectedFacultyId = $row['faculty_id'];
        $selectedDepartment = $row['dept'];
        $selectedRoom = $row['room'];
        $selectedBuilding = $row['description'];
        $selectedSemester = $row['semester_name'];
    }
} else {
    // Handle query errors
    echo "Error: " . $conn->error;
}








?>

<div class="row justify-content-center">
  <div class="col-6">
  <form action="" method="get" class="mb-3">
            <label for="faculty " class="form-label itsLabel">Select faculty:</label>
            <select name="faculty" id="faculty" class="form-select" onchange="this.form.submit()">
                <option value=""><?= $faculty ?></option>
                <?php echo $facultyOptions; ?>
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
            <img src="assets/img/evsu_logo.png" width="35%" class="evsu-logo">
            </div>
            <div class="col">
                <center>
            <h7 class="text-center">Republic of the Philippines</h7>
            <h6 class="text-center">EASTERN VISAYAS STATE UNIVERSITY</h6>
            <h6 class="text-center">TANAUAN CAMPUS</h6>
            <h7 class="text-center mb-2">Tanauan, Leyte</h7>
            <h5 class="text-center mb-2"><?= $selectedDepartment; ?> Department</h5></center>
            </div>
            <div class="col">
            <?php 
            if ($selectedDepartment === 'Engineering'){
                $logoImg = '<img src="assets/img/engineering.jpg" style="border-radius:50%;" width="35%" class="evsu-logo-engineer">';
            } else {
                $logoImg = '';
            }
            
            ?>
            <?= $logoImg;?>
            </div>
        </div>
        <br>
        
        
  
        <div class="row justify-content-center">
        <div class="col-sm text-left">
        <h8 class="mb-2"><b>Instructor:</b> <?= strtoupper($_GET['faculty']) ?> </h8>
        </div>
        <div class="col-sm text-left">
            <h8 class=""><b>A.Y.</b> <?php echo $currentYear; ?> - <?php echo $nextYear; ?></h8>
        </div>
        <div class="col-sm text-left">
            <h8 class=""><b>Semester:</b> <?= $selectedSemester; ?></h8>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm text-left">
            
        </div>
        <div class="col-sm text-left">
            <h8><b>Bldg:</b> <?= strtoupper($selectedBuilding); ?></h8>
        </div>
        <div class="col-sm text-left">
            <h8></h8>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm text-left">
            
        </div>
        <div class="col-sm text-left">
            <h8><b>Room No.</b> <?= strtoupper($selectedRoom); ?></h8>
        </div>
        <div class="col-sm text-left">

        </div>
    </div>

       

        
        
        
        
        
   
        <div class="table-responsive">
            <div class="timetable">
                <?php
                    // Fetch all schedules from the database
                    $query = "SELECT * FROM schedules
                    INNER JOIN faculty ON schedules.faculty_id = faculty.id
                    INNER JOIN subjects ON schedules.subject_id = subjects.id
                    INNER JOIN sections ON schedules.section_id = sections.id
                    INNER JOIN rooms ON schedules.room_id = rooms.id
                    WHERE 1 $facultyCondition
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
                            'start_time' => $startTime->format('h:i '),
                            'starttime' => $startTime->format('h:i'),
                            'endtime' => $endTime->format('h:i '),
                            'end_time' => $endTime->format('h:i'),
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
                            'start_time' => $currentSlot->format('h:i '),
                            'end_time' => $endTimeSlot->format('h:i '),
                            'slotIndex' => floor($currentSlot->getTimestamp() / (30 * 60)) // Current time slot index
                        ];
                        $currentSlot->add($interval);
                    }

                    // Display the timetable
                    echo "<table class='table' border='1'><tr><th width='10%' style='background-color: #bf1111;'>Time</th><th style='background-color: #bf1111;'>Monday</th><th style='background-color: #bf1111;'>Tuesday</th><th style='background-color: #bf1111;'>Wednesday</th><th style='background-color: #bf1111;'>Thursday</th><th style='background-color: #bf1111;'>Friday</th><th style='background-color: #bf1111;'>Saturday</th><th style='background-color: #bf1111;'>Sunday</th></tr>";

                    
                    foreach ($timeSlots as $timeSlot) {
                        
                        $start12PM = new DateTime('12:00:00');
                        $end1PM = new DateTime('13:00:00');
                        $startTimeSlot = new DateTime($timeSlot['start_time']);
                        $endTimeSlot = new DateTime($timeSlot['end_time']);
                        $timeSlotRange = [$startTimeSlot, $endTimeSlot];
                        echo "<tr>";
                        echo "<td rowspan='2'>" . $timeSlot['start_time'] . ' - ' . $timeSlot['end_time'] . "</td>";

                        for ($day = 0; $day < 7; $day++) {
                            echo "<td colspan='1'>";

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
                                echo "<div class='schedule-cell'>" . $schedule['room'] . '<br>'. $schedule['subject']  .'<br><b>' .$schedule['name'] .'</b>' ."</div>";
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
                                // echo "<div class='schedule-cell'>" . $schedule['starttime'] . ' to ' . $schedule['endtime'] . "</div>";
                            }

                            echo "</td>";
                        }
                        echo "</tr>";
                    }

                    echo "</table>";
                    
                ?>


<?php 

$getSubjectFaculty = " AND faculty_id = $selectedFacultyId ";
$subjectQuery = [];
if ($selectedFacultyId){
    
$subjectQuery = $conn->query("SELECT subjects.*, COUNT(schedules.id) as total 
FROM subjects
INNER JOIN schedules ON schedules.subject_id = subjects.id
INNER JOIN sections ON schedules.section_id = sections.id
INNER JOIN faculty ON schedules.faculty_id = faculty.id 
WHERE 1 $getSubjectFaculty
GROUP BY subjects.id");

$lecTotal = 0;
$labTotal = 0;
$unitTotal = 0;

$totalDocuments = $subjectQuery->num_rows;
// Process and display the retrieved data
while ($row = $subjectQuery->fetch_assoc()) {
    if ( $row['lec']){

        $lecTotal += $row['lec'];
    }
    if ($row['lab']){
        $labTotal += $row['lab'];
    }
    if ($row['lec'] && $row['lab']){
        $unitTotal += $row['lec'] + $row['lab'];
    }
}
}


    $show = 'none';
    if($selectedFacultyId){
        $show = '';
    }
?>

 
 
    <table class="table table-bordered" style="display:<?= $show;?>;">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Descriptive Title</th>
                <th>Lec</th>
                <th>Lab</th>
                <th>Units</th>
            </tr>
        </thead>
        <tbody>
            
            <?php foreach ($subjectQuery as $row): ?>
                <tr>
                    <td><?= $row['subject']; ?></td>
                    <td><?= $row['description']; ?></td>
                    <td><?= $row['lec']; ?></td>
                    <td><?= $row['lab']; ?></td>
                    <td><?= $row['lec'] + $row['lab']; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                
            </tr>
            <tr>
            <td colspan="1"></td>
                <td><b>Total:</b> <?= $totalDocuments; ?></td>
                <td><?= $lecTotal; ?></td>
                <td><?= $labTotal; ?></td>
                <td><?= $unitTotal; ?></td>
            </tr>
        </tbody>
    </table>




        </div>

<br>
        <div class="row">
  <div class="col"></div>
  <div class="col"></div>
  <div class="col"><b>Prepared by:</b></div>
  <div class="col"></div>
</div>
<div class="row">
  <div class="col-8"></div>
  <div class="col-4">
    <p><b>VICENTE D. CARILLO JR., Ph.D.</b></p>
    <p style="padding-left: 45px;">Department Head</p>
</div>
</div>

<div class="">
  <div class="row">
    <div class="col">
     
    </div>
    <div class="col-6">
      <b> Approved:</b>
    </div>
    <div class="col">
     
    </div>
  </div>
  <div class="row">
    <div class="col">
    </div>
    <div class="col">
     <p><b>ANNABELLE B. PILAPIL, Ph.D.</b></p>
     <p style="padding-left: 45px;">Campus Director</p>
    </div>
    <div class="col">
     
    </div>
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

