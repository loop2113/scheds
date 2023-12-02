<?php
session_start();
include 'db.php';
if(isset($_SESSION['username'])) {
  header("Location: index.php"); // redirects them to homepage
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
            border-radius: 10px;
            padding: 20px;
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
                                        <h4 class="text-center font-weight-light my-4">Admin Login</h4>
                                    </div>
                                    <div class="card-body">
                                        <form role="form" method="POST">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="username" type="text" />
                                                <label for="inputUsername">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="password" type="password" />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="mt-4 mb-0">
                                                <button type="submit" class="btn btn-success" name="submit">Login</button>
                                                <a class="btn btn-primary" href="loginf.php" style="float:right;">Login as Faculty</a>
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
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);

    $login = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password' ");

    if(mysqli_num_rows($login) == 0) {
        ?>
            <script>
                Swal.fire({
                title: 'Error',
                text: 'Incorrect Credentials',
                icon: 'error',
                showConfirmButton: false,
                timer: 1500
                }).then(function() {
                window.location.href = "login.php";
                });
            </script>
        <?php
    } else {
        $row = mysqli_fetch_assoc($login);

        if (!empty($row['session_id']) && $row['session_id'] !== session_id()) {
            ?>
            <script>
                Swal.fire({
                    title: 'Error',
                    text: 'This account is already logged in on another device.',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "login.php";
                });
            </script>
            <?php
            exit;
        }

        $newSessionId = session_id();
        mysqli_query($conn, "UPDATE users SET session_id='$newSessionId' WHERE id=" . $row['id']);

        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['user_type'] = $row['user_type'];

        ?>
        <script>
          Swal.fire({
            title: 'Success',
            text: 'Login Successfuly',
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
          }).then(function() {
          window.location.href = "index.php";
          });
        </script>
        <?php
    }
}
?>

