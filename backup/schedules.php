<?php 
include 'db.php';
session_start();
if(!isset($_SESSION["username"]))
header("location:login.php");
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    </head>
    <style>
    .fc-time{
    display : none;
    }
    .fc-title{
    font-weight: bold;
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
                        <h1 class="mt-4">Schedules <button type="button" class="btn btn-primary btns" data-bs-toggle="modal" data-bs-target="#addSched">+ Add Schedule</button></h1>
                        <select id="selectIDs" class="form-control mb-3" style="width: 45%; float: left;">
                            <option value="" selected>Select Department</option>
                            <?php
                                $query = "SELECT * from department";
                                $resultx = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_array($resultx)){
                                ?> 
                                <option value ="<?php echo $row['id'];?>"><?php echo $row['dept'];?></option>
                            <?php } ?>
                        </select>
                        <select id="faculty" class="form-control mb-3" style="width: 45%; float: right;">
                        </select>
                        <ol class="breadcrumb mb-4">
                        </ol>
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
                                <label for="weekday">Weekday:</label>
                                <select name="weekday" class="form-control" required>
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="startHour">Start Hour:</label>
                                    <input type="number" class="form-control" name="start_hour" min="1" max="12" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="startMinute">Start Minute:</label>
                                    <input type="number" class="form-control" name="start_minute" min="0" max="59" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="startMinute">Start Minute:</label>
                                    <select name="end_am_pm" class="form-control" required>
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="endHour">End Hour:</label>
                                    <input type="number" class="form-control" name="end_hour" min="1" max="12" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="endMinute">End Minute:</label>
                                    <input type="number" class="form-control" name="end_minute" min="0" max="59" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="endAmPm">End Time:</label>
                                    <select name="end_am_pm" class="form-control" required>
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
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
        <script src="js/datatables-simple-demo.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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
    </body>
</html>
<script>
  $(document).ready(function(){
     $('#selectIDs').change(function(){
      var Stdid = $('#selectIDs').val(); 
 
      $.ajax({
        type: 'POST',
        url: 'fetch_faculty_sched.php',
        data: {id:Stdid},  
        success: function(data)  
         {
            $('#faculty').html(data);
         }
        });

    });
  });
</script>
<?php
if(isset($_POST['save'])){
    $weekday = $_POST['weekday'];
    $start_am_pm = $_POST['start_am_pm'];
    $start_hour = $_POST['start_hour'];
    $start_minute = $_POST['start_minute'];
    $end_am_pm = $_POST['end_am_pm'];
    $end_hour = $_POST['end_hour'];
    $end_minute = $_POST['end_minute'];
    $title = $_POST['title'];
    $facultyId = $_POST['facultyId'];
    $subjectId = $_POST['subjectId'];
    $sectionId = $_POST['sectionId'];
    $roomId = $_POST['roomId'];

    // Convert start and end times to 24-hour format
    if ($start_am_pm == 'PM' && $start_hour != 12) {
        $start_hour = $start_hour + 12;
    } elseif ($start_am_pm == 'AM' && $start_hour == 12) {
        $start_hour = 0;
    }
    
    if ($end_am_pm == 'PM' && $end_hour != 12) {
        $end_hour = $end_hour + 12;
    } elseif ($end_am_pm == 'AM' && $end_hour == 12) {
        $end_hour = 0;
    }

    // Check for time conflict with lunchtime
    if (($start_hour >= 12 && $start_hour <= 12 && $start_minute >= 0 && $start_minute <= 59) || ($end_hour >= 12 && $end_hour <= 12 && $end_minute >= 0 && $end_minute <= 59)) {
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
        // Check for time conflict with existing schedules based on weekdays and time slots
        $query = "SELECT * FROM schedules WHERE 
                (weekday = '$weekday' AND 
                ((start_hour < $end_hour OR (start_hour = $end_hour AND start_minute <= $end_minute)) AND 
                (end_hour > $start_hour OR (end_hour = $start_hour AND end_minute >= $start_minute))))";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // There is a time conflict with other schedules, handle it (e.g., show an error message)
            ?>
            <script>
                Swal.fire({
                    title: 'Conflict',
                    text: 'Another schedule with the same time and weekday already exists.',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000
                }).then(function() {
                    window.location.href = "";
                });
            </script>
            <?php
        } else {
            // No conflicts, proceed with inserting into the database
            $insert_query = "INSERT INTO schedules (title, weekday, start_hour, start_minute, end_hour, end_minute, faculty_id, subject_id, section_id, room_id) VALUES ('$title', '$weekday', '$start_hour', '$start_minute', '$end_hour', '$end_minute', '$facultyId', '$subjectId', '$sectionId', '$roomId')";
            mysqli_query($conn, $insert_query);
            ?>
            <script>
                Swal.fire({
                    title: 'Success',
                    text: 'Assigned Successfully',
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
}
?>
