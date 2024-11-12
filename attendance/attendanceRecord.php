<!DOCTYPE HTML>
<html>
<head>
    <link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>Admin - Packages</title>
    <link rel="stylesheet" href="style/style.css" type="text/css"/>
    <link rel="stylesheet" href="style/subpackages.css" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

<?php
include("db_connection.php");

// Check if student_id is received via POST
if (isset($_POST['userid'])) {
    $student_id = $_POST['userid'];

    // Sanitize user input (better to use prepared statements)
    $student_id = mysqli_real_escape_string($conn, $student_id);

    $sql = "SELECT 
                s.*, 
                a.*, 
                DATE(a.time_in) AS attendance_date,
                TIME_FORMAT(a.time_in, '%h:%i %p') AS attendance_time,
                a.remarks
            FROM 
                students s 
            JOIN 
                attendance a ON s.student_id = a.student_id
            WHERE 
                a.student_id = $student_id";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        // Query failed
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        // Display attendance details
?>
<?php
$query2 = "SELECT * FROM students where student_id=$student_id";

// Execute the query
$result2 = mysqli_query($conn, $query2);

if (!$result2) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if there is exactly one row returned
if (mysqli_num_rows($result2) == 1) {
    // Fetch the row as an associative array
    $row = mysqli_fetch_assoc($result2);

  
    $lname = $row['lastName'];
    $fname = $row['firstName'];

    // Now you can use $id, $name, $email variables in your application logic
    // For example, assign them to another variable, use them in calculations, etc.
} else {
    echo "No records found or multiple records returned";
}

?>
        <h5 id="attendanceModalLabel"><?php echo htmlspecialchars($fname); ?> <?php echo htmlspecialchars($lname); ?></h5>

        <label for="attendanceFilter">Filter by:</label>
        <select id="attendanceFilter" onchange="filterAttendance()" class="form-control mb-3">
            <option value="all">All</option>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Late">Late</option>
        </select>

        <div class="data_table">
            <table id="attendanceTable" class="table table-striped table-bordered">
                <thead class="table-primary" style="font-size:15px">
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
<?php
        while ($row = mysqli_fetch_assoc($result)) {
            $time = $row['attendance_time'];
            $date = $row['attendance_date'];
            $remarks = $row['remarks'];
?>
                    <tr style="font-size:15px">
                        <td><?php echo htmlspecialchars($date); ?></td>

                        <?php

if ( $time == '12:00 AM' )
    echo '<td>- - -</td>';

    else
    echo '<td>'.$time.'</td>';

?>
                        
                        <td style="text-align:center; <?php echo getRemarksClass($remarks); ?> "><?php echo htmlspecialchars($remarks); ?></td>
                    </tr>
<?php
        }
?>
                </tbody>
            </table>
        </div>
<?php
    } else {
        echo "No attendance records found";
    }

    // Free result set
    mysqli_free_result($result);
   
} else {
    echo "Student ID parameter not received";
}

// Close MySQL connection
mysqli_close($conn);

// Function to determine CSS class based on remarks
function getRemarksClass($remarks) {
    switch ($remarks) {
        case 'Present':
            return 'background-color:#34DC40; color:#0A490F'; // Darker green color
        case 'Late':
            return 'background-color:#FFDB4B; color:D7AC00'; // Darker yellow color
        case 'Absent':
            return 'background-color:#F75F5F; color:#B00D0D'; // Darker red color
        default:
            return ''; // Default class
    }
}
?>

<script>
    // Function to filter by status
    function filterAttendance() {
        var filter = document.getElementById('attendanceFilter').value.toLowerCase();
        var rows = document.querySelectorAll('#attendanceTable tbody tr');

        rows.forEach(function(row) {
            var status = row.children[2].textContent.toLowerCase(); // Remarks column
            var display = 'table-row';

            if (filter === 'all') {
                display = 'table-row';
            } else if (status.includes(filter.toLowerCase())) {
                display = 'table-row';
            } else {
                display = 'none';
            }

            row.style.display = display;
        });
    }
</script>

</body>
</html>
