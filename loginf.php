<?php 
session_start();
include 'db.php';
if(isset($_SESSION['emp_id'])) {
  header("Location: indexf.php"); // redirects them to homepage
  exit; // for good measure
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
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="vendor/fontawesome/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
        body {
            background: url('assets/img/evsu.jpg') center/cover no-repeat;
        }
        .card {
            background: rgba(255, 255, 255, 0.8); /* Adjust the alpha value (fourth parameter) for transparency */
            backdrop-filter: blur(0px); /* Apply blur effect to create glass effect */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4">Subject Scheduling System</h3>
                                        <h4 class="text-center font-weight-light my-4">Faculty Login</h4>
                                    </div>
                                    <div class="card-body">
                                        <form role="form" method="POST">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="emp_id" type="text" />
                                                <label for="inputUsername">Employee ID</label>
                                            </div>
                                            <div class="mt-4 mb-0">
                                                <button type="submit" class="btn btn-success" name="submit">Login</button>
                                                <a class="btn btn-primary" href="login.php" style="float:right;">Login as Admin</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="vendor/sweetalert2/sweetalert2.js"></script>
    </body>
</html>
<?php
if(isset($_POST['submit'])) {
  $emp_id = $_POST['emp_id'];

  $login = mysqli_query($conn, "SELECT * FROM faculty WHERE emp_id='$emp_id'");
  if(mysqli_num_rows($login) == 0)
  {
    ?>
    <script>
        Swal.fire({
          title: 'Error',
          text: 'Unknown Employee No.',
          icon: 'error',
          showConfirmButton: false,
          timer: 1500
        }).then(function() {
        window.location.href = "loginf.php";
        });
    </script>
    <?php
  }
  else
  {
    $row = mysqli_fetch_assoc($login);
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['emp_id'] = $row['emp_id'];
        ?>
        <script>
          Swal.fire({
            title: 'Success',
            text: 'Login Successfuly',
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
          }).then(function() {
          window.location.href = "indexf.php";
          });
        </script>
        <?php
    
  }
}
?>
