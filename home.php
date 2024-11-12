
<?php
// Assuming you have a connection to the database in $connection
// You can modify this according to your actual table names and structure
include("db_connection.php");
// Fetch all students to check their attendance status
$query = "SELECT s.student_id, s.status FROM students s";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $student_id = $row['student_id'];
        $current_status = $row['status']; // Existing status (this is optional, you may choose to ignore this)
        
        // Fetch the count of absences from the attendance table for this student
        $attendance_query = "SELECT COUNT(*) AS total_absences 
                             FROM attendance 
                             WHERE student_id = ? AND remarks = 'Absent'"; // Assuming 'absent' status
        $stmt = mysqli_prepare($conn, $attendance_query);
        mysqli_stmt_bind_param($stmt, 'i', $student_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $total_absences);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Determine the student's risk level based on the absences
        if ($total_absences >= 40) {
            $status = 'High Risk';   // More than 10 absences
        } elseif ($total_absences >= 10) {
            $status = 'Medium Risk'; // Between 5 and 9 absences
        } else {
            $status = 'Low Risk';    // Less than 5 absences
        }

        // Update the student's status in the students table
        $update_query = "UPDATE students 
                         SET status = ? 
                         WHERE student_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, 'si', $status, $student_id);
        mysqli_stmt_execute($update_stmt);
        
       
        
        // Close the update statement
        mysqli_stmt_close($update_stmt);
    }
} else {
    echo "Error fetching students: " . mysqli_error($conn);
}


?>


<?php
session_start();
if(isset($_SESSION["teacher_id"])) {
    $user_id = $_SESSION["teacher_id"];
    include("db_connection.php");

    // Fetch teacher's name from database
    $get_record = mysqli_query($conn, "SELECT * FROM teachers WHERE teacher_id='$user_id'");
    if ($row_edit = mysqli_fetch_assoc($get_record)) {
        $name = $row_edit['name'];
        $title = $row_edit['courtesy'];
    } else {
        die("Teacher not found."); // Handle case where teacher ID doesn't exist
    }date_default_timezone_set('Asia/Manila');

    // Determine greeting based on time of day
    $time = date("H");
    if ($time >= 11) {
        $greeting = "Good morning";
    } elseif ($time <= 18) {
        $greeting = "Good afternoon";
    } else {
        $greeting = "Good evening";
    }

    // Set the timezone to Asia/Manila
    date_default_timezone_set('Asia/Manila');

    // Perform query to fetch schedules along with related sections and subjects
    $view_query = "SELECT sections.*, subject.*, schedules.*,
                  TIME_FORMAT(schedules.start_class, '%h:%i %p') AS start_class,
                  TIME_FORMAT(schedules.end_class, '%h:%i %p') AS end_class
                  FROM schedules
                  JOIN sections ON schedules.section_id = sections.section_id
                  JOIN subject ON schedules.subject_id = subject.subject_id
                  WHERE schedules.teacher_id = '$user_id'";

    $result = mysqli_query($conn, $view_query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Check if class has ended and insert attendance records for absent students
    $current_date = date('Y-m-d');
    $current_time = time();
    while ($row = mysqli_fetch_assoc($result)) {
        $class_end = strtotime($current_date . ' ' . $row['end_class']);
        if ($current_time >= $class_end) {
            // Class has ended, insert attendance records for absent students
            $grade_id = $row['grade_id'];
            $section_id = $row['section_id'];
            $subject_id = $row['subject_id'];

            // Query to insert attendance records for absent students
            $insert_query = "INSERT INTO attendance (student_id, subject_id, teacher_id, remarks, time_in)
                             SELECT students.student_id, $subject_id, $user_id, 'Absent', '$current_date'
                             FROM students
                             WHERE NOT EXISTS (
                                 SELECT 1 FROM attendance
                                 WHERE students.student_id = attendance.student_id
                                 AND attendance.subject_id = $subject_id
                                
                                
                             )
                             AND students.section_id = $section_id
                             AND students.grade_id = $grade_id";

            // Execute the query
            $insert_result = mysqli_query($conn, $insert_query);

            if ($insert_result) {
               
            } else {
                echo "Error inserting attendance: " . mysqli_error($conn) . "<br>";
            }
        }
    }

    mysqli_close($conn); // Close database connection
} else {
    header("Location: index.php"); // Redirect if session variable not set
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Home</title>

    <!-- Bootstrap core CSS -->
    <link href="admin/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }
        .clock-container {
            color: #000;
            font-size: 1.5rem;
            
        }
        #clock{
            margin-left:48%;
        }
      
        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<body>

<main>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" aria-label="Eighth navbar example">
        <div class="container">
            <a class="navbar-brand" href="#">QR Code Attendance System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExample07">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">List of Students</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown01">
                            <?php mysqli_data_seek($result, 0); // Reset result pointer ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <li><a class="dropdown-item" href="masterlist.php?grade=<?php echo urlencode($row['grade_id']); ?>&sections=<?php echo urlencode($row['section']); ?>"><?php echo "Grade " . urlencode($row['grade_id']) . " Section " . urlencode($row['section']); ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </li>
                    <li><a class="nav-link" href="attendanceHistory.php">Attendance History</a></li>
                </ul>
                <form>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </ul>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <header class="pb-3 mb-4 border-bottom">
            <span class="fs-4">Hello, <?php echo htmlspecialchars($title) . " " . htmlspecialchars($name); ?></span>
            <span id="clock" class="clock-container"></span>&nbsp;&nbsp;
            <span id="date" class="clock-container"></span>
            
           
        </header>

        <div class="row align-items-md-stretch">
            <?php mysqli_data_seek($result, 0); // Reset result pointer ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                $section_id = $row['section_id'];
                $grade_name = $row['grade_id'];
                $section_name = $row['section'];
                $subject_name = $row['subject'];
                $class_start = $row['start_class'];
                $class_end = $row['end_class'];

                // Convert class start and end times to timestamps
                $current_time = time(); // Current Unix timestamp
                $class_start_datetime = strtotime(date('Y-m-d') . ' ' . $class_start); // Start class time as timestamp
                $class_end_datetime = strtotime(date('Y-m-d') . ' ' . $class_end); // End class time as timestamp

                // Calculate 5 minutes before attendance availability
                $attendance_start_time = $class_start_datetime - (5 * 60); // 5 minutes in seconds

                // Calculate countdown timer for attendance availability (only minutes and seconds)
                $countdown = '';
                if ($current_time < $attendance_start_time) {
                    $countdown_seconds = $attendance_start_time - $current_time;
                    $minutes = floor($countdown_seconds / 60);
                    $seconds = $countdown_seconds % 60;
                    $countdown = sprintf("%02d:%02d", $minutes, $seconds); // Format minutes and seconds
                }

                // Check if current time is within the allowed attendance window
                if ($current_time >= $attendance_start_time && $current_time < $class_end_datetime) {
                    $disable_link = false;
                    $button_text = 'Check Attendance';
                    $link_url = "attendance.php?grade=" . urlencode($grade_name) . "&sections=" . urlencode($section_name);
                } elseif ($current_time >= $class_end_datetime) {
                    $disable_link = true;
                    $button_text = 'Class Ended';
                    $link_url = ''; // Empty URL to prevent clicking
                } else {
                    $disable_link = true;
                    $button_text = 'Attendance Unavailable';
                    $link_url = ''; // Empty URL to prevent clicking
                }
                ?>
                <div class="col-md-6 mb-3">
                    <div class="h-60 p-5 text-white bg-success rounded-3">
                        <h2>Grade <?php echo htmlspecialchars($grade_name); ?> Section <?php echo htmlspecialchars($section_name); ?></h2>
                        <p><?php echo htmlspecialchars($subject_name); ?></p>
                        <p><?php echo htmlspecialchars($class_start); ?> - <?php echo htmlspecialchars($class_end); ?></p>
                        <?php if ($disable_link): ?>
                            <button class="btn btn-outline-light disabled" type="button" disabled><?php echo htmlspecialchars($button_text); ?></button>
                            <?php if ($countdown !== ''): ?>
                                <div id="countdown-parent<?php echo $section_id; ?>" class="countdown-container"></div>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($link_url); ?>" class="btn btn-outline-light" type="button"><?php echo htmlspecialchars($button_text); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <footer class="pt-3 mt-4 text-muted border-top">
            &copy; <?php echo date("Y"); ?>
        </footer>
    </div>

   





    <script src="admin/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to update clock with AM/PM
        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';

            // Convert hours from 24-hour format to 12-hour format
            hours = hours % 12;
            hours = hours ? hours : 12; // The hour '0' should be '12' in 12-hour format

            var timeString = hours.toString().padStart(2, '0') + ':' +
                             minutes.toString().padStart(2, '0') + ':' +
                             seconds.toString().padStart(2, '0') + ' ' + ampm;

            document.getElementById('clock').textContent = timeString;

            // Update date
            var date = now.toDateString();
            document.getElementById('date').textContent = date;
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        
        // PHP variables to JavaScript for each schedule
        <?php mysqli_data_seek($result, 0); // Reset result pointer ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            var sectionId = <?php echo $row['section_id']; ?>;
            var attendanceStartTime = <?php echo $class_start_datetime - (5 * 60 * 1000); ?>; // 5 minutes before in milliseconds
            updateCountdown(attendanceStartTime, sectionId);
        <?php endwhile; ?>
    </script>
</body

