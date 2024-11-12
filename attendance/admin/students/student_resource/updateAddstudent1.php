<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>Student Registration</title>
    <link rel="stylesheet" href="style/style.css" type="text/css"/>
    <link rel="stylesheet" href="style/subpackages.css" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<?php
require '../../db_connection.php';

if(isset($_POST["submit"])){
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $student_lrn = $_POST["student_lrn"];
    $email = $_POST["email"];
    $guardian = $_POST["guardian"];
    $guardianContact = $_POST["guardianContact"];
    $grade = $_POST["grade_id"];
    $section = $_POST["section_id"];
    $generated_code = $_POST["generated_code"];

    if($_FILES["image"]["error"] == 4){
        echo "<script> swal('Error', 'Image Does Not Exist', 'error'); </script>";
    } else {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ( !in_array($imageExtension, $validImageExtension) ){
            echo "<script> swal('Error', 'Invalid Image Extension', 'error'); </script>";
        } elseif($fileSize > 1000000){
            echo "<script> swal('Error', 'Image Size Is Too Large', 'error'); </script>";
        } else {
            // Check if student already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE student_lrn = ?");
            $stmt->bind_param("s", $student_lrn);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                echo "
                <script>
                    swal({
                        title: 'Error',
                        text: 'Student already exists with this LRN',
                        icon: 'error',
                        buttons: {
                            catch: {
                                text: 'Go to Grade 1 Students',
                                value: 'redirect',
                            },
                        },
                    }).then((value) => {
                        if (value === 'redirect') {
                            window.location.href = '../grade1Students.php';
                        }
                    });
                </script>";
            } else {
                $newImageName = uniqid() . '.' . $imageExtension;
                move_uploaded_file($tmpName, 'image/' . $newImageName);

                $stmt = $conn->prepare("INSERT INTO students (firstName, lastName, student_lrn, image, email, guardian, guardianContact, grade_id, section_id, qr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssis", $firstName, $lastName, $student_lrn, $newImageName, $email, $guardian, $guardianContact, $grade, $section, $generated_code);
                
                if($stmt->execute()) {
                    $redirectPage = '';
                    switch($grade) {
                        case 1: $redirectPage = '../grade1Students.php'; break;
                        case 2: $redirectPage = '../grade2Students.php'; break;
                        case 3: $redirectPage = '../grade3Students.php'; break;
                        case 4: $redirectPage = '../grade4Students.php'; break;
                        case 5: $redirectPage = '../grade5Students.php'; break;
                        case 6: $redirectPage = '../grade6Students.php'; break;
                        default: $redirectPage = '../index.php'; break;
                    }

                    echo "
                    <script>
                        swal({
                            title: 'Success',
                            text: 'New Student Added',
                            icon: 'success',
                            buttons: {
                                catch: {
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
                    echo "<script> swal('Error', 'Failed to add student. Please try again.', 'error'); </script>";
                }
            }
        }
    }
}
?>
</body>
</html>
