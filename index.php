<?php
include 'db.php';
session_set_cookie_params(0);
session_start();
if(!isset($_SESSION["username"]))
header("location:loginf.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="vendor/select2/select2.min.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include 'sidenav.php'; ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Welcome <?php echo $_SESSION['fullname']; ?>! <button style="float: right;" type="button" class="btn btn-primary btns" data-bs-toggle="modal" data-bs-target="#addSched">+ Add Schedule</button></h1></h1>
                        
                        <ol class="breadcrumb mb-4">
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Schedule List
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <!-- <th>Title</th> -->
                                            <th>Time</th>
                                            <th>Weekdays</th>
                                            <th>Section</th>
                                            <th>Subject</th>
                                            <th>Room</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $result = mysqli_query($conn,"SELECT schedules.id, faculty.name, schedules.title, schedules.start_time, schedules.end_time, schedules.weekday, sections.section, subjects.subject, rooms.room, faculty.id as fid, faculty.emp_id, faculty.email FROM schedules
                                    INNER JOIN faculty ON schedules.faculty_id = faculty.id
                                    INNER JOIN subjects ON schedules.subject_id = subjects.id
                                    INNER JOIN sections ON schedules.section_id = sections.id
                                    INNER JOIN rooms ON schedules.room_id = rooms.id
                                    ORDER BY faculty.name ASC");
                                    while($row = mysqli_fetch_assoc($result)){
                                        $timeS = $row['start_time'];
                                        $startTime = date("h:i A", strtotime($timeS));
                                        $timeE = $row['end_time'];
                                        $endTime = date("h:i A", strtotime($timeE));
                                        $weekdayValue = $row['weekday'];
                                        $weekdayString = date('l', strtotime("Sunday +{$weekdayValue} days"));
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?>. <?php echo $row['name']; ?></td>
                                            <!-- <td><?php 
                                            // echo $row['title']; 
                                            ?></td> -->
                                            <td><?php echo $startTime; ?> - <?php echo $endTime; ?></td>
                                            <td><?php echo $weekdayString; ?></td>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><?php echo $row['subject']; ?></td>
                                            <td><?php echo $row['room']; ?></td>
                                            <td><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewFaculty<?php echo $row['fid'];?>"><i class="fa-solid fa-eye"></i></button> <a href='?deleteeid=<?php echo $row['id']?>' class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                        </tr>
                                    <?php
                                    $no++;
                                    include 'include/viewFacultyIndex.php';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
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
                            <div class="mb-3" style="display:none;">
                                <label for="faculty">Title:</label>
                                <input type="text" class="form-control" name="title" required>
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
                                
                               
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?= $row['id']; $room_id = $row['id']; ?>"><?= $row['room']; ?></option>
                                        <?php
                                    }
                                } else {
                                    
                                    echo "<option value=''>No rooms available</option>";
                                }
                                print_r($room_id);
                                ?>
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

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script src="vendor/sweetalert2/sweetalert2.js"></script>
        <script src="vendor/select2/select2.min.js"></script>
    </body>
</html>
<?php
if (isset($_GET["deleteeid"])) {
    $deleteEid = $_GET["deleteeid"];

    $deleteSql = "DELETE FROM schedules WHERE schedules.id = $deleteEid";
    if ($conn->query($deleteSql) === TRUE) {
        ?>
        <script>
            Swal.fire({
            title: 'Schedule Deleted',
            text: 'Deleted Successfully',
            icon: 'success'
            }).then(function() {
                window.location.href = "index.php";
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire({
            title: 'Deletion Failed',
            text: 'Please Try Again',
            icon: 'error'
            }).then(function() {
                window.location.href = "index.php";
            });
        </script>
        <?php
    }
}
?>

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
            $query = "SELECT * FROM schedules  WHERE room_id = $room_id  AND 
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
                    window.location.href = "index.php";
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
                            window.location.href = "index.php";
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
                    window.location.href = "index.php";
                });
            </script>
            <?php
        }
    }
}
?>
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

       $('#mySelect2').select2({
                dropdownParent: $('#addSched')
            });
</script>