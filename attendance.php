

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <!-- <title>Attendance - Grade <?php echo htmlspecialchars($grade_name); ?> Section <?php echo htmlspecialchars($section_name); ?></title> -->

    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/class.css">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
  

    <!-- Bootstrap JS (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Instascan library -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<?php
date_default_timezone_set('Asia/Manila');

// Get the current time
$current_time2 = date('F j, Y h:i:s a');
use PHPMailer\PHPMailer\PHPMailer;
// Start session (if not already started)
session_start();

// Check if teacher_id is set in session
if (!isset($_SESSION["teacher_id"])) {
    header("Location: index.php");
    exit(); // Ensure no further code execution after redirection
}

// Assign teacher_id from session to $teacher_id
$teacher_id = $_SESSION["teacher_id"];

// Include database connection script
include("db_connection.php");

// Fetch teacher's name, subject, grade, and section from database using teacher_id
$get_teacher = mysqli_query($conn, "SELECT 
    t.*,
  
    s.*
FROM 
    teachers t
JOIN 
    subject s
ON 
    t.subject_id = s.subject_id
 WHERE t.teacher_id='$teacher_id'");

if (!$get_teacher || mysqli_num_rows($get_teacher) === 0) {
    die("Teacher not found.");
}

$row_teacher = mysqli_fetch_assoc($get_teacher);
$teacher_subject = $row_teacher['subject_id'];
$teacher_subject2 = $row_teacher['subject']; // Assuming you need teacher's subject_id
$teacher_courtesy=$row_teacher['courtesy'];
$teacher_name=$row_teacher['name'];
// Set timezone to match your server's timezone
date_default_timezone_set('Asia/Manila'); // Example: Set to your server's timezone

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    // Retrieve and sanitize form data
    $qr_code = mysqli_real_escape_string($conn, $_POST['qr']); // Sanitize QR code

    // Query to fetch student details including grade_id and section_id
    $query = "SELECT * FROM students WHERE qr='$qr_code'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch student details
        $row = mysqli_fetch_assoc($result);
        $student_id = $row['student_id'];
        $student_email = $row['email'];
        $grade_id = $row['grade_id']; // Assuming grade_id is a field in your students table
        $section_id = $row['section_id']; // Assuming section_id is a field in your students table

        // Check if the student has already attended for the current day and subject
        $current_date = date('Y-m-d'); // Current date

        $check_attendance_query = "SELECT * FROM attendance WHERE student_id='$student_id' AND subject_id='$teacher_subject' AND DATE(time_in)='$current_date'";
        $check_attendance_result = mysqli_query($conn, $check_attendance_query);

        if ($check_attendance_result && mysqli_num_rows($check_attendance_result) > 0) {
           

            echo '<script>
            Swal.fire({
                icon: "warning",
                title: "",
                text: "Student has already attended for this subject today.",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed || result.isDismissed) {
                    window.location.href = "attendance.php?grade=' . $grade_id . '&sections=' . $section_id . '";
                }
            });
          </script>';
        } else {
            // Fetch current class schedule information for today
            $current_time = new DateTime(); // Current time as DateTime object

            // Query to fetch schedule for the teacher's subject for today
            $get_schedule = mysqli_query($conn, "SELECT * FROM schedules WHERE subject_id='$teacher_subject'");

            if ($get_schedule && mysqli_num_rows($get_schedule) > 0) {
                $row_schedule = mysqli_fetch_assoc($get_schedule);

                // Get the start and end times from the schedule
                $start_time = new DateTime($row_schedule['start_class']);
                $end_time = new DateTime($row_schedule['end_class']);

                // Check if the current time is beyond the end time of the class
                if ($current_time < $end_time) {
                    // Student is absent if current time is beyond end time
                    $remarks = "Present";
                } else {
                    // Compare attendance time with scheduled class time
                    if ($current_time >= $start_time && $current_time < date_modify(clone $start_time, '+1 minute')) {
                        // Early attendance
                        $remarks = "Present";
                    } elseif ($current_time >= date_modify(clone $start_time, '+1 minute') && $current_time <= $end_time) {
                        // Late attendance
                        $remarks = "Late";
                    } else {
                        // Absent if more than end time
                        $remarks = "Absent";
                    }
                }
               
                require_once "PHPMailer/PHPMailer.php";
        
        
                require_once "PHPMailer/SMTP.php";
            
                require_once "PHPMailer/Exception.php";
                $mail =new PHPMailer();
            
                $mail->IsSMTP();
    
                $mail->SMTPDebug  = 0;  
                $mail->SMTPAuth   = TRUE;
                $mail->SMTPSecure = "tls";
                $mail->Port       = 587;
                $mail->Host       = "smtp.gmail.com";
                //$mail->Host       = "smtp.mail.yahoo.com";
                $mail->Username   = "anjaynicfred.scott@gmail.com";
                $mail->Password   = "plunhhgmvqwvllwd";
                $mail ->isHTML(true);
                $mail ->From = 'anjaynicfred.scott@gmail.com';
                $mail ->FromName ='QR Code Attendance System';
                $mail ->addAddress($student_email);
                
                $message = "Mahal na Magulang/Guardian! <br> <br> Ipinapaalam namin na ang inyong anak ay <b>$remarks</b> sa $teacher_subject2 na klase ngayong <br>$current_time2!🌟<br><br>Kung may mga katanungan ka, huwag mag-atubiling makipag-ugnayan. Salamat at magandang araw!😊<br><br>Best Regards, <br><br>$teacher_courtesy $teacher_name ";
                $mail-> Subject ="Your Child's Class Attendance";
                $mail-> Body =$message;
    
                if(!$mail->send()){
                  echo 'Message could not be sent';
                  echo 'Mailer Error: ' . $mail->ErrorInfo;
                }else{







                // Insert attendance record into database
                $sql = "INSERT INTO attendance (student_id, teacher_id, subject_id, remarks) 
                        VALUES ('$student_id', '$teacher_id', '$teacher_subject', '$remarks')";

                if (mysqli_query($conn, $sql)) {
                    function sendTelegramMessage($chatId, $message) {
                        $botToken = "7931991285:AAEq-_0-X3Xr4_gHeou5AZBs3tpSH-AvLFs"; // Replace with your bot token
                        $url = "https://api.telegram.org/bot$botToken/sendMessage";
                    
                        $data = [
                            'chat_id' => $chatId,
                            'text' => $message,
                        ];
                    
                        $options = [
                            'http' => [
                                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                'method'  => 'POST',
                                'content' => http_build_query($data),
                            ],
                        ];
                    
                        $context  = stream_context_create($options);
                        $result = file_get_contents($url, false, $context);
                        
                        // Check if the request was successful
                        if ($result === FALSE) {
                            // Handle error
                            echo "Failed to send message.";
                        } else {
                            // Decode the response
                            $response = json_decode($result, true);
                            if ($response['ok']) {
                                echo "";
                            } else {
                                echo "Error: " . $response['description'];
                            }
                        }
                    }
                    
                    // Usage
                    $chatId = "6062189773"; // Replace with the actual user's chat ID
                    $message = "Mahal na Magulang/Guardian, ipinapaalam namin na ang inyong anak ay present sa klase ngayong araw 🌟 Kung may mga katanungan ka, huwag mag-atubiling makipag-ugnayan. Salamat at magandang araw!😊";
                    sendTelegramMessage($chatId, $message);
                        
                    echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "",
                        text: "Attendance recorded successfully.",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            window.location.href = "attendance.php?grade=' . $grade_id . '&sections=' . $section_id . '";
                        }
                    });
                  </script>';
                    
                } else {
                    echo "Error: " . mysqli_error($conn);
                }}
            } else {
                echo "No schedule found for subject today.";
            }
        }
    } else {
        echo "No student found with QR code: $qr_code";
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!-- Your HTML form and JavaScript for QR code scanning -->




<?php

if (isset($_GET['grade']) && isset($_GET['sections'])) {
    $grade_name = $_GET['grade'];
    $section_name = $_GET['sections'];

    // Perform additional queries or display details based on grade_name and section_name
    // Example: Fetch details from database based on these parameters
    // Example: Display detailed information about the selected grade and section
} else {
    // Handle case where parameters are missing
    die();
}

?>

<main>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" aria-label="Eighth navbar example">
        <div class="container">
            <a class="navbar-brand" href="#">QR Code Attendance System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample07">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                </ul>
                <form>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <a class="nav-link" href="home.php">Back</a>
                    </ul>
                </form>
            </div>
        </div>
    </nav>
    <script>
        // Check if the session variable is set and display alert
        <?php
        if (isset($_SESSION['attendance_success_message'])) {
            echo 'alert("' . $_SESSION['attendance_success_message'] . '");';
            // Unset the session variable after displaying the alert
            unset($_SESSION['attendance_success_message']);
        }
        ?>
    </script>
    <div class="container mt-5">
        <div class="row mt-5">
            <div class="col-8">
                
              <div class="data_table">
             

                  
             <table id="example" class="table table-striped table-bordered">
                 <thead class="table-primary" style="font-size:15px">
                 <tr>
                 <th>Student LRN</th>
                 <th>Student Name</th>
                 <th>Time In</th>
                 <th>Remarks</th>
               </tr>
                 </thead>
                 <tbody>
                 <?php
include("db_connection.php");

// Assuming $teacher_id, $section_name, and $grade_name are already defined or received

// Get today's date in MySQL format (YYYY-MM-DD)
$current_date = date('Y-m-d');

$view_query = "SELECT s.*, a.*, TIME_FORMAT(a.time_in, '%h:%i %p') AS time_in
               FROM students s
               JOIN attendance a ON s.student_id = a.student_id
               WHERE a.teacher_id = '$teacher_id'
               AND s.section_id = '$section_name'
               AND s.grade_id = '$grade_name'
               AND DATE(a.time_in) = '$current_date'";

$result = mysqli_query($conn, $view_query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Iterate through the results and display them
while ($row = mysqli_fetch_assoc($result)) {
    $lrn = $row['student_lrn'];
    $lname = $row['lastName'];
    $fname = $row['firstName'];
    $timein = $row['time_in'];
    $remarks = $row['remarks'];

    // Determine CSS class based on remarks
    $class = '';
    switch ($remarks) {
        case 'Present':
            $class = 'background-color:#34DC40; color:#0A490F'; // Darker green color
            break;
        case 'Late':
            $class = 'background-color:#FFDB4B; color:D7AC00'; // Darker yellow color
            break;
        case 'Absent':
            $class = 'background-color:#F75F5F; color:#B00D0D'; // Darker red color
            break;
        default:
            $class = ''; // Default class
            break;
    }
?>
    <tr style="font-size:15px">
        <td><?php echo htmlspecialchars($lrn); ?></td>
        <td><?php echo htmlspecialchars($fname); ?> <?php echo htmlspecialchars($lname); ?></td>
        <td><?php echo htmlspecialchars($timein); ?></td>
        <td style="<?php echo $class; ?>; text-align:center; font-weight:500"><?php echo htmlspecialchars($remarks); ?></td>
    </tr>
<?php
}

// Close MySQL connection

?>

                    
 
                 

       

                 </tbody>
             </table>
   </div>   
                
            </div>
            <div class="col-4">
                <!-- Class Information Table -->
                <div class="container">
                    <table class="table">
                        <thead class="table-primary">
                            <tr>
                                <th colspan="2" style="text-align: center;">Class Information</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                       // Assuming $user_id is defined somewhere before this code snippet

                     include("db_connection.php");

                      // Perform query to fetch schedules along with related sections and subjects
                      $view_query = "SELECT sections.*, subject.*, schedules.*,
                      TIME_FORMAT(schedules.start_class, '%h:%i %p') AS start_class,
                      TIME_FORMAT(schedules.end_class, '%h:%i %p') AS end_class
                      FROM schedules
                      JOIN sections ON schedules.section_id = sections.section_id
                      JOIN subject ON schedules.subject_id = subject.subject_id
                      WHERE schedules.teacher_id = '$teacher_id' and schedules.grade_id='$grade_name' and schedules.section_id='$section_name'";

                      $result = mysqli_query($conn, $view_query);

                      if (!$result) {
                          die("Query failed: " . mysqli_error($conn));
                      }

                      // Iterate through the results and display them
                      while ($row = mysqli_fetch_assoc($result)) {
                          $sub = $row['subject'];
                          $sec = $row['section'];
                          $gr = $row['grade_id'];
                          $start = $row['start_class'];
                          $end = $row['end_class'];
                      ?>
                       <?php
                      }
                      ?>



                            <tr>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Subject</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($sub); ?></span></td>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Grade & Section</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($gr); ?>-<?php echo htmlspecialchars($sec); ?></span></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Class Start</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($start); ?></span></td>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Class End</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($end); ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
               

                
                <!-- QR Code Scanner Modal -->
                <div class="container">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Open QR Code Scanner</button>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">QR Code Scanner</h5>
                                    <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="scanner-container">
                                        <video id="scanner" class="viewport" style="width:470px;"></video>
                                    </div>
                                    <div class="qr-detected-container" style="display: none;">
                                <h4 class="text-center">Student QR Code Detected!</h4>
                                <center><img id="student-image" class="img-fluid rounded" alt="Student Image" style="width:200px; height:200px"></center>
                                <p style="margin-left:50px; margin-top:20px;"><strong>LRN:</strong> <span id="student-lrn"></span></p>
                                <p style="margin-left:50px;"><strong>Student Name:</strong> <span id="student-fname"></span>&nbsp;<span id="student-lname"></span></p>
                            

                                <form id="attendanceForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <input type="hidden" id="detected-qr-code" name="qr">
                                    <input type="hidden" id="teacher-name" name="teacher_name" value="<?php echo htmlspecialchars($teacher_id); ?>">
                                    <input type="hidden" id="teacher-subject" name="teacher_subject" value="<?php echo htmlspecialchars($teacher_subject); ?>">
                                    <input type="hidden" id="student-fname-input" name="student-fname">
                                    <!-- Other form inputs and submit button -->
                                    <!-- Button to reset scan -->
                                    <button id="resetScanBtn" class="btn btn-danger" style="display: none;">Scan Another Student</button>

                                    <button type="submit" class="btn btn-dark form-control mt-3" name="submit" id="submitBtn">Submit Attendance</button>
                                    
                                </form>
                            </div>
                                </div>

                                <div class="modal-footer">
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <table class="table">
                        <thead class="table-primary">
                            <tr>
                            
                                <th>Student Name</th>
                                <th>Attendance Status</th>
                                <th>Action</th>
                                <th>Result</th>
                           
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                       // Assuming $user_id is defined somewhere before this code snippet

                       include("db_connection.php");

                       // Perform query to fetch schedules along with related sections and subjects
                       $view_query2 = "SELECT s.*, a.*, COUNT(*) as attendance_count
                        FROM students s
                        JOIN attendance a ON s.student_id = a.student_id
                        WHERE a.teacher_id = '$teacher_id'
                        AND s.section_id = '$section_name'
                        AND s.grade_id = '$grade_name'
                      
                        AND (s.status = 'Medium Risk' OR s.status = 'High Risk')
                        GROUP BY s.student_id
                        HAVING attendance_count > 1;

                        ";
 
                       $result = mysqli_query($conn, $view_query2);
 
                       if (!$result) {
                           die("Query failed: " . mysqli_error($conn));
                       }
 
                       // Iterate through the results and display them
                       while ($row = mysqli_fetch_assoc($result)) {
                           $lname = $row['lastName'];
                           $fname = $row['firstName'];
                           $status = $row['status'];
                           $action = $row['actionTake'];
                           $results = $row['result'];
                       ?> <tr>
                                   
            
                       <td><span style="font-weight: 500;"><?php echo htmlspecialchars($fname); ?> <?php echo htmlspecialchars($lname); ?></span></td>
                      

                       <?php if ($status == 'Medium Risk') : ?>
                            <td>
                                
                                    <span style="color:orange;  font-weight:500"><?php echo htmlspecialchars($status); ?></span>
                               
                            </td>
                            
                        
                        <?php else : ?>
                            <td class='lrn-cell'>
                            <p style="color:red;  font-weight:500"><?php echo htmlspecialchars($status); ?></p>
                            </td>
                        <?php endif; ?>

                     
                       <td><span style="font-weight: 500;"><?php echo htmlspecialchars($action); ?></span></td>
                       <td><span style="font-weight: 500;"><?php echo htmlspecialchars($results); ?></span></td>
                       
               </tr>
                        <?php
                       }
                       ?>



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</main>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/datatables.min.js"></script>
<script src="assets/js/pdfmake.min.js"></script>
<script src="assets/js/vfs_fonts.js"></script>
<script src="assets/js/custom.js"></script>




<!-- Ensure you have a button element for resetting scan -->


<script>
    var scanner = null; 
    let grade_id_param = <?php echo htmlspecialchars($grade_name); ?>; // Grade parameter from URL
    let section_id_param = <?php echo htmlspecialchars($section_name); ?>; // Section parameter from URL

    function startScanner() {
        scanner = new Instascan.Scanner({ video: document.getElementById('scanner') });

        scanner.addListener('scan', function (content) {
            $("#detected-qr-code").val(content);
            fetchStudentDetails(content); // Fetch details based on QR content
            scanner.stop();
            document.querySelector(".qr-detected-container").style.display = '';
            document.querySelector(".scanner-container").style.display = 'none';
        });

        Instascan.Camera.getCameras()
            .then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                    alert('No cameras found.');
                }
            })
            .catch(function (err) {
                console.error('Camera access error:', err);
                alert('Camera access error: ' + err);
            });
    }

    function fetchStudentDetails(qrContent) {
        // Example: Fetch details using AJAX
        $.ajax({
            url: 'fetchStudentDetails.php',
            method: 'GET',
            data: { qr: qrContent },
            dataType: 'json',
            success: function (data) {
                // Log section_id_param and data.section_id
                console.log('Section ID Parameter:', section_id_param);
                console.log('Fetched Section ID:', data.section_id);

                // Proceed with other logic
                $('#student-lrn').text(data.student_lrn);
                $('#student-fname').text(data.firstName);
                $('#student-lname').text(data.lastName);
                $('#grade_id').text(data.grade_id);
                $('#section_id').text(data.section_id);
                $('#student-image').attr('src', 'admin/students/student_resource/image/' + data.image);

                // Enable or disable submit button based on section ID match
                if (data.section_id !== section_id_param) {
                    $('#submitBtn').prop('disabled', true); // Disable submit button
                    $('#submitBtn').css('background-color', 'red'); // Change background color to red
                    $('#submitBtn').css('color', 'white'); // Change text color to white
                    $('#submitBtn').text('This student does not belong to this class'); // Change button text
                } else {
                    $('#submitBtn').prop('disabled', false); // Enable submit button
                    $('#submitBtn').css('background-color', ''); // Reset background color
                    $('#submitBtn').css('color', ''); // Reset text color
                    $('#submitBtn').text('Submit Attendance'); // Reset button text
                }

                // Show the container with student details
                $('.qr-detected-container').css('display', 'block');
                $('.scanner-container').css('display', 'none'); // Hide the scanner container
            },
            error: function (xhr, status, error) {
                console.error('Error fetching student details:', error);
                alert('Error fetching student details.');
            }
        });
    }

    $('#exampleModal').on('shown.bs.modal', function () {
        startScanner(); // Start the scanner when the modal is shown
    });

    // Event listener for when the modal is hidden
    $('#exampleModal').on('hidden.bs.modal', function () {
        if (scanner) {
            scanner.stop(); // Stop the scanner if it's running
            scanner = null; // Reset the scanner instance

            $('.qr-detected-container').css('display', 'none'); // Hide detected container
            $('.scanner-container').css('display', 'block'); // Show scanner container
            resetModal(); // Reset modal content and state
        }
    });
</script>




</body>
</html>


