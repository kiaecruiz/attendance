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
    $id = $_GET['id'];
    if ($id == null) {
        header("Location: ../grade1Student.php");
        exit(); // Ensure that script stops here
    }
  
    if (isset($_POST["edit"])) {
        $a = $_POST['firstName'];
        $b = $_POST['lastName'];
        $c = $_POST['email'];
        $d = $_POST['student_lrn'];
        $e = $_POST['guardian'];
        $f = $_POST['guardianContact'];
        $g = $_POST['grade_id'];
        $h = $_POST['section_id'];
   
  
        $query = ("UPDATE `students` SET firstName='$a', lastName='$b', email='$c', student_lrn='$d', guardian='$e', guardianContact='$f', grade_id='$g', section_id='$h' WHERE student_id='$id'");
        // Check grade_id and redirect accordingly
        if(mysqli_query($conn, $query)) {
          // Determine where to redirect based on grade
          $redirectPage = '';
          switch($g) {
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
              // Add more cases as needed for other grades
              default:
                  // Redirect to a default page if grade is not handled
                  $redirectPage = '../index.php'; // Adjust as per your requirement
                  break;
          }

          // Display success alert using sweetalert
          echo "
          <script>
              swal({
                  title: 'Edit Student',
                  text: '',
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
          echo "<script>alert('Failed to add student. Please try again.');</script>";
      }
  }


?>
</body>
</html>