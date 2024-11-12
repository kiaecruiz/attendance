<?php
session_start();

// Check if teacher_id is set in session
if(isset($_SESSION["teacher_id"])) {
    $teacher_id = $_SESSION["teacher_id"];
    include("../db_connection.php");
    
    // Fetch teacher's name from database
    $get_record = mysqli_query($conn, "SELECT * FROM teachers WHERE teacher_id='$teacher_id'");
    if ($row_edit = mysqli_fetch_assoc($get_record)) {
        $teacher_name = $row_edit['name'];
    } else {
        die("Teacher not found."); // Handle case where teacher ID doesn't exist
    }
} else {
    die("Session expired or not logged in."); // Handle case where teacher session is not set
}

// Include database connection
include("../conn.php");

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $qrCode = isset($_POST['qr']) ? $_POST['qr'] : '';
    $grade_id = isset($_POST['grade_id']) ? $_POST['grade_id'] : '';

    // Validate inputs (ensure they are not empty)
    if (empty($qrCode) || empty($grade_id)) {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/Attendance/attendance.php';
            </script>
        ";
        exit();
    }

    try {
        // Prepare statement to select student_id based on qr code
        $selectStmt = $conn->prepare("SELECT student_id FROM students WHERE qr = :qr");
        $selectStmt->bindParam(":qr", $qrCode, PDO::PARAM_STR);

        // Execute the statement
        if ($selectStmt->execute()) {
            $result = $selectStmt->fetch(PDO::FETCH_ASSOC);

            if ($result !== false) {
                $studentID = $result["student_id"];
                $timeIn = date("Y-m-d H:i:s");

                // Insert attendance record with teacher_id and grade_id
                $insertStmt = $conn->prepare("INSERT INTO attendance (student_id, time_in, teacher_id, grade_id) 
                                              VALUES (:student_id, :time_in, :teacher_id, :grade_id)");
                $insertStmt->bindParam(":student_id", $studentID, PDO::PARAM_INT); // Assuming student_id is an integer
                $insertStmt->bindParam(":time_in", $timeIn, PDO::PARAM_STR);
                $insertStmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT); // Assuming teacher_id is an integer
                $insertStmt->bindParam(":grade_id", $grade_id, PDO::PARAM_INT); // Assuming grade_id is an integer

                if ($insertStmt->execute()) {
                    // Redirect after successful insertion
                    header("Location: http://localhost/Attendance/attendance.php");
                    exit();
                } else {
                    echo "Failed to insert attendance record.";
                }
            } else {
                echo "No student found for QR Code: " . htmlspecialchars($qrCode);
            }
        } else {
            echo "Failed to execute select statement.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
