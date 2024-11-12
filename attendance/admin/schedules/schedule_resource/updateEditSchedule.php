<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>Admin</title>
    <link rel="stylesheet" href="style/style.css" type="text/css"/>
    <link rel="stylesheet" href="style/subpackages.css" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

<?php
    include('../../db_connection.php');
    
    // Check if 'id' is provided via GET parameter
    if (!isset($_GET['id'])) {
        header("Location: ../grade1Students.php"); // Redirect if ID is not provided
        exit(); // Stop further execution
    }
    
    $id = $_GET['id'];
    
    // Fetch the grade of the student based on $id (example assumption)
    $query = "SELECT grade_id FROM schedules WHERE schedule_id = $id"; // Adjust this query as per your database structure
    $result = mysqli_query($conn, $query);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        // Handle error if student ID is not found
        echo "<script>alert('Student ID not found');</script>";
        exit();
    }
    
    $row = mysqli_fetch_assoc($result);
    $grade_id = $row['grade_id'];
    
    // Handle form submission to update schedule
    if (isset($_POST["edit"])) {
        $start_class = $_POST['start_class'];
        $end_class = $_POST['end_class'];
        
        $update_query = "UPDATE `schedules` SET start_class='$start_class', end_class='$end_class' WHERE schedule_id='$id'";
        
        if(mysqli_query($conn, $update_query)) {
            // Determine where to redirect based on grade_id
            switch ($grade_id) {
                case 1:
                    $redirectPage = '../grade1Students.php';
                    break;
                case 2:
                    $redirectPage = '../grade2Students.php';
                    break;
                case 3:
                    $redirectPage = '../grade3Students.php';
                    break;
                case 4:
                    $redirectPage = '../grade4Students.php';
                    break;
                case 5:
                    $redirectPage = '../grade5Students.php';
                    break;
                case 6:
                    $redirectPage = '../grade6Students.php';
                    break;
                default:
                    $redirectPage = '../index.php'; // Default page if grade_id doesn't match
                    break;
            }
            
            // Display success alert using sweetalert
            echo "
            <script>
                swal({
                    title: 'Edit Schedule',
                    text: 'Schedule updated successfully',
                    icon: 'success',
                    buttons: {
                        confirm: {
                            text: 'OK',
                            value: 'Ok',
                        },
                    },
                }).then((value) => {
                    if (value === 'Ok') {
                        window.location.href = '$redirectPage';
                    }
                });
            </script>";
        } else {
            echo "<script>alert('Failed to update schedule. Please try again.');</script>";
        }
    }
?>
</body>
</html>
