<?php
// Start session (if not already started)
session_start();

// Check if teacher_id is set in session
if (!isset($_SESSION["teacher_id"])) {
    header("Location: index.php");
}

// Assign teacher_id from session to $teacher_id
$teacher_id = $_SESSION["teacher_id"];

// Include database connection script
include("db_connection.php");

// Fetch teacher's name, subject, grade, and section from database using teacher_id
$get_teacher = mysqli_query($conn, "SELECT * FROM teachers WHERE teacher_id='$teacher_id'");

if (!$get_teacher || mysqli_num_rows($get_teacher) === 0) {
    die("Teacher not found.");
}

$row_teacher = mysqli_fetch_assoc($get_teacher);
$teacher_name = $row_teacher['name'];
$teacher_subject = $row_teacher['subject_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>QR Code Attendance System</title>

    <!-- Bootstrap core CSS -->
    <link href="admin/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        .filter-form {
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<main>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">QR Code Attendance System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Back</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h1 class="mb-4">Search Attendance</h1>
        <form method="POST" action="" class="filter-form row g-3">
            <div class="col-md-6">
                <label for="section" class="form-label">Section:</label>
                <input type="text" id="section" name="section_id" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="date" class="form-label">Date:</label>
                <input type="date" id="date" name="attendance_date" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Search</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>

        <?php
        include("db_connection.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize input
            $section = htmlspecialchars($_POST['section_id']);
            $attendanceDate = htmlspecialchars($_POST['attendance_date']);

            // Prepare the SQL query with a JOIN
            $stmt = $conn->prepare("
                SELECT s.lastName, s.section_id, a.time_in, a.remarks
                FROM attendance a
                JOIN students s ON a.student_id = s.student_id
                WHERE s.section_id = ? AND DATE(a.time_in) = ? AND teacher_id = ?
            ");
            $stmt->bind_param("ssi", $section, $attendanceDate, $teacher_id);
            
            // Execute the query
            if ($stmt->execute()) {
                // Get the result
                $result = $stmt->get_result();

                // Check if there are results
                if ($result->num_rows > 0) {
                    echo "<h2>Attendance Records:</h2>";
                    echo "<button id='printButton' class='btn btn-success mb-3' onclick='printAttendance()'>Print Attendance</button>";
                    echo "<table class='table table-striped' id='attendanceTable'>";
                    echo "<thead><tr><th>Student Name</th><th>Section</th><th>Time in</th><th>Remarks</th></tr></thead>";
                    echo "<tbody>";

                    // Output data for each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['section_id']) . "</td>";
                        echo "<td>" . date('Y-m-d H:i:s', strtotime($row['time_in'])) . "</td>"; // Format timestamp for display
                        echo "<td>" . htmlspecialchars($row['remarks']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No records found.</p>";
                }
            } else {
                echo "<p class='text-danger'>Error executing query: " . htmlspecialchars($stmt->error) . "</p>";
            }

            // Close the statement
            $stmt->close();
        }

        // Close the database connection
        $conn->close();
        ?>

        <footer class="pt-3 mt-4 text-muted border-top">
            &copy; <?php echo date("Y"); ?>
        </footer>
    </div>

    <script src="admin/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function printAttendance() {
        var printContents = document.getElementById('attendanceTable').outerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload to restore original content
    }
    </script>
</main>

</body>
</html>
