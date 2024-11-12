<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    
</body>
</html>




<?php
session_start();

// Check if teacher_id is set in session
if (isset($_SESSION["teacher_id"])) {
    // Get the teacher_id from session
    $teacher_id = $_SESSION["teacher_id"];

    // Include your database connection file
    include("db_connection.php");

    // Update the status to 'Offline' in the database
    $update_status = mysqli_query($conn, "UPDATE teachers SET status = 'Offline' WHERE teacher_id = $teacher_id");

    if ($update_status) {
        // Successfully updated status to 'Offline', now destroy the session
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session

        ?><script>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      
      Toast.fire({
        icon: 'success',
        title: 'Signing Out'
      })
      </script>
        
        <?php

header ('Refresh: 3;url=index.php');

    } else {
        // Error updating status
        echo "Error updating status to Offline";
    }
} else {
    // Session teacher_id not set, handle error or redirect to login page
    header("Location: index.php");
    exit;
}
?>




