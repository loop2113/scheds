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
        <title>Faculty</title>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="vendor/fontawesome/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
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
            <div id="layoutSidenav_nav">
                <?php include 'sidenav.php'; ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Faculty <button type="button" class="btn btn-primary btns" data-bs-toggle="modal" data-bs-target="#addFaculty">+ Add Faculty</button></h1>
                        <ol class="breadcrumb mb-4">
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Faculty List
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Employee Code</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Course Handled</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = mysqli_query($conn,"SELECT faculty.id, faculty.emp_id, faculty.name, department.dept, courses.course_name, faculty.email, subjects.subject, faculty.sub_id FROM faculty INNER JOIN courses ON faculty.course_id = courses.id INNER JOIN department ON faculty.dept_id = department.id INNER JOIN subjects ON faculty.sub_id = subjects.id");
                                    while($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['emp_id']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['dept']; ?></td>
                                            <td><?php echo $row['course_name']; ?></td>
                                            <td>
                                            <?php
                                             $sub_id_strings = explode(',', $row['sub_id']);
                                             foreach ( $sub_id_strings as $sub_id_string ) {
                                                $sub_id_string = trim($sub_id_string);
                                                $sub_query = mysqli_query($conn,'SELECT * FROM subjects WHERE id = '. $sub_id_string);
                                                while($subrow = mysqli_fetch_array($sub_query)) {
                                                   echo $subrow['subject']; ?>  <?php 
                                                }
                                             }
                                             

                                             ?>
                                            </td>
                                            <!-- <td><?php echo $row['subject']; ?></td> -->
                                            <!-- <td><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewFaculty<?php echo $row['id'];?>"><i class="fa-solid fa-eye"></i></button> -->
                                             <td>
                                             <button class="btn btn-primary btn-sm" onclick="window.location.href='faculty_timetables.php?faculty=<?php echo $row['name'];?>'">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <!-- <button class="btn btn-success btn-sm" onclick="window.location.href='faculty_timetables.php?faculty=<?php echo $row['name'];?>'"><i class="fa-solid fa-eye"></i></button> -->
                                                <button class="btn btn-danger btn-sm" onclick="window.location.href='?fid=<?php echo $row['id'];?>';"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php
                                    include 'include/viewFaculty.php';
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

        <div class="modal fade" id="addFaculty" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Faculty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="ha" method="POST">
                            <div class="mb-3">
                                <label>Faculty Name:</label>
                                <input type="text" class="form-control" name="name" required>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Department</label>
                                <select class="form-control" name="dept_id" required>
                                <option disabled selected>Select Department</option>
                                <?php
                                $sql = "SELECT * FROM department";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)) {?>
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['dept'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Course</label>
                                <select class="form-control" name="course_id" required>
                                <option disabled selected>Select Course</option>
                                <?php
                                $sql = "SELECT * FROM courses";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)) {?>
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['course_name'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Email:</label>
                                <input type="email" class="form-control" name="email" required>
                                </select>
                            </div>
                            <div class="mb-3">
                            <?php
                            $sql = "SELECT * FROM subjects";
                            $result = mysqli_query($conn, $sql);
                             ?>
                            <label>Assign Subject:</label>
                            <select id="mySelect2" style="width: 100%;" class="form-control" name="sub_id[]" multiple="multiple">
                                <?php foreach($result as $subName): ?>
                                    <option value="<?= $subName['id'];?>"><?= $subName['subject'];?></option>
                                <!-- <?php echo ' <option value="'. $subName['id'] .'">' . $subName['subject']. '</option>';  ?> -->
                                <?php endforeach; ?>
                            
                                <!-- <option value="<?php echo $row['id']; ?>"><?php echo $row['subject']; ?></option> -->
                            
                        </select>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" form="ha" name="save">Save</button>
                    </div>
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
        <script src="js/auto_number.js"></script>
        <script src="vendor/select2/select2.min.js"></script>
    </body>
</html>
<!-- <script>
    function saveSubjects() {
        const select = document.querySelector('select[name="sub_id"]');
        const selectedOptions = [...select.selectedOptions].map(option => option.value);
    }
</script> -->

<script>
            $('#mySelect2').select2({
                dropdownParent: $('#addFaculty')
            });
        </script>

<?php
if(isset($_POST['save'])){
    function generateUniqueID($length = 6) {
        $characters = '0123456789';
        $id = '';
        for ($i = 0; $i < $length; $i++) {
            $id .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $id;
    }

    $name = $_POST['name'];
    $dept_id = $_POST['dept_id'];
    $course_id = $_POST['course_id'];
    $email = $_POST['email'];
    $sub_id = $_POST['sub_id']; 
    // Assuming $sub_id is an array of subject IDs
   


    // Generate a unique emp_id
    do {
        $uniqueID = generateUniqueID();
        $checkQuery = "SELECT * FROM faculty WHERE emp_id = '$uniqueID'";
        $checkResult = mysqli_query($conn, $checkQuery);
    } while (mysqli_num_rows($checkResult) > 0);


    if (is_array($sub_id)) {
        $sub_id_string = implode(',', $sub_id); // Create a comma-separated string
        // ... rest of your code ...
        $query = "INSERT INTO faculty (emp_id, dept_id, name, course_id, email, sub_id) VALUES ('$uniqueID', '$dept_id', '$name', '$course_id', '$email', '$sub_id_string')";
        $query2 = mysqli_query($conn, $query);
        // ... rest of your code ...
    }
    // // Insert data into the database
    // // $query = "INSERT INTO faculty (emp_id, dept_id, name, course_id, email, sub_id) VALUES ('$uniqueID', '$dept_id', '$name', '$course_id', '$email', '$sub_id')";
    // $query = "INSERT INTO faculty (emp_id, dept_id, name, course_id, email, sub_id) VALUES ('$uniqueID', '$dept_id', '$name', '$course_id', '$email', '$sub_id_string')";
    // $query2 = mysqli_query($conn, $query);
    

    // Check if the insertion was successful
    if($query2){
        ?>
        <script>
            Swal.fire({
                title: 'success',
                text: 'Data Inserted',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location.href = "faculty.php";
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire({
                title: 'error',
                text: 'Data Not Inserted',
                icon: 'error',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location.href = "faculty.php";
            });
        </script>
        <?php
    }
}
?>
<?php
if (isset($_GET["fid"])) {
    $fid = $_GET["fid"];

    $sid = $_GET['fid'];

    $check_query = "SELECT * FROM schedules WHERE schedules.faculty_id = $sid";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        ?>
        <script>
            Swal.fire({
              title: 'error',
              text: 'Faculty is already assigned, cannot delete.',
              icon: 'error',
              showConfirmButton: true
            }).then(function() {
            window.location.href = "faculty.php";
            });
        </script>
        <?php
    }

    $delete_query = "DELETE FROM faculty WHERE id = $fid";
    if ($conn->query($delete_query) === TRUE) {
        ?>
        <script>
            Swal.fire({
              title: 'success',
              text: 'Data Deleted',
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
            window.location.href = "faculty.php";
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire({
              title: 'error',
              text: 'Please Try Again',
              icon: 'error',
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
            window.location.href = "faculty.php";
            });
        </script>
        <?php
    }
}
?>