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
        <title>Section</title>
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
                        <h1 class="mt-4">Section <button type="button" class="btn btn-primary btns" data-bs-toggle="modal" data-bs-target="#addSection">+ Add Section</button></h1>
                        <ol class="breadcrumb mb-4">
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Section List
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Section</th>
                                            <th>Course</th>
                                            <th>Department</th>
                                            <th>Adviser</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = mysqli_query($conn,"SELECT sections.id, sections.section, courses.course_name, faculty.name, department.dept FROM sections INNER JOIN courses ON sections.course_id = courses.id INNER JOIN faculty ON sections.emp_id = faculty.id INNER JOIN department ON sections.dep_id = department.id order by sections.id asc");
                                    while($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><?php echo $row['course_name']; ?></td>
                                            <td><?php echo $row['dept']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td>
                                                <a href='student_timetables.php?section=<?php echo $row['section']?>' class="btn btn-success btn-sm"><i class="fa-solid fa-plus"></i></a>
                                                <a href='?deletesid=<?php echo $row['id']?>' class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php
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

        <div class="modal fade" id="addSection" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="ha" method="POST">
                            <div class="mb-3">
                                <label">Section Name:</label>
                                <input type="text" class="form-control" name="section" required>
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
                                <label>Department</label>
                                <select class="form-control" name="emp_id" required>
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
                                <label>Adviser</label>
                                <select class="form-control" name="emp_id" required>
                                <option disabled selected>Select Adviser</option>
                                <?php
                                $sql = "SELECT * FROM faculty";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($result)) {?>
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['name'];?></option>
                                <?php } ?>
                                </select>
                            </div>
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
    </body>
</html>
<?php
if(isset($_POST['save'])){
      $section = $_POST['section'];
      $course_id = $_POST['course_id'];
      $emp_id = $_POST['emp_id'];

      $query = "INSERT INTO sections (section, course_id, emp_id) VALUES ('$section', '$course_id', '$emp_id')";
      $query2 = mysqli_query($conn, $query);
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
            window.location.href = "section.php";
            });
          </script>
        <?php  
      }
}
?>
<?php
if (isset($_GET["deletesid"])) {
    $deleteSid = $_GET["deletesid"];

    $deleteSql = "DELETE FROM sections WHERE sections.id = $deleteSid";
    if ($conn->query($deleteSql) === TRUE) {
        ?>
        <script>
            Swal.fire({
            title: 'Section Deleted',
            text: 'Deleted Successfully',
            icon: 'success'
            }).then(function() {
                window.location.href = "section.php";
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
                window.location.href = "section.php";
            });
        </script>
        <?php
    }
}
?>