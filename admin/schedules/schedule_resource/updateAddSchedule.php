<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>Admin</title>
    <link rel="stylesheet" href="style/style.css" type="text/css"/>
    <link rel="stylesheet" href="style/subpackages.css" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
<?php
require '../../db_connection.php';

if(isset($_POST["submit"])){
    // Sanitize and escape inputs to prevent SQL injection
    $start = mysqli_real_escape_string($conn, $_POST["start_class"]);
    $end = mysqli_real_escape_string($conn, $_POST["end_class"]);
    $grade = mysqli_real_escape_string($conn, $_POST["grade_id"]);
    $subject = mysqli_real_escape_string($conn, $_POST["subject_id"]);
    $teacher = mysqli_real_escape_string($conn, $_POST["teacher_id"]);
    $section = mysqli_real_escape_string($conn, $_POST["section_id"]);

    // Check if the schedule already exists
    $check_query = "SELECT * FROM schedules 
                    WHERE grade_id = '$grade' 
                    AND subject_id = '$subject' 
                    AND section_id = '$section'";

    $result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($result) > 0) {
        switch($grade) {
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
                $redirectPage = '../index.php'; // Default redirect page
                break;
        }

        // Display success message using SweetAlert
        echo "
        <script>
            swal({
                title: 'Schedule Already Exists',
                text: '.',
                icon: 'warning',
                buttons: {
                    catch: {
                        text: 'OK',
                        value: 'Ok',
                    },
                },
            }).then((value) => {
                if (value === 'Ok') {
                    window.location.href = '$redirectPage'; // Redirect to appropriate page
                }
            });
        </script>";
    } else {
        // Insert the new schedule
        $query = "INSERT INTO schedules (start_class, end_class, grade_id, subject_id, teacher_id, section_id)
                  VALUES ('$start', '$end', '$grade', '$subject', '$teacher', '$section')";

        if(mysqli_query($conn, $query)) {
            // Determine where to redirect based on grade
            switch($grade) {
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
                    $redirectPage = '../index.php'; // Default redirect page
                    break;
            }

            // Display success message using SweetAlert
            echo "
            <script>
                swal({
                    title: 'New Schedule Added',
                    text: 'Schedule successfully added.',
                    icon: 'success',
                    buttons: {
                        catch: {
                            text: 'OK',
                            value: 'Ok',
                        },
                    },
                }).then((value) => {
                    if (value === 'Ok') {
                        window.location.href = '$redirectPage'; // Redirect to appropriate page
                    }
                });
            </script>";
        } else {
            // Failed to insert schedule, show error message
            echo "<script>alert('Failed to add schedule. Please try again.');</script>";
        }
    }
}
?>
</body>
</html>
