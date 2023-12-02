<?php
include 'db.php';

mysqli_query($conn, "UPDATE users SET session_id = NULL");
header("Location: login.php");
exit;

?>