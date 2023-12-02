<?php
session_start();

include '../db.php';

if(isset($_SESSION['username'])) {
    // Clear the session_id in the database
    $userId = $_SESSION['id'];
    mysqli_query($conn, "UPDATE users SET session_id = NULL WHERE id = $userId");

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: ../login.php");
    exit;
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: ../login.php");
    exit;
}
?>