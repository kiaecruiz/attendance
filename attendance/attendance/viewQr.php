<!DOCTYPE HTML>
<html>
<head>
    <link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>View Qr</title>
    <link rel="stylesheet" href="style/style.css" type="text/css"/>
    <link rel="stylesheet" href="style/subpackages.css" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

<?php
// Include database connection
include("db_connection.php");

// Assuming you're receiving student_id via POST method
if(isset($_POST['userid'])) {
    $student_id = $_POST['userid'];
    
    // Sanitize input (assuming $conn is your MySQLi connection object)
    $student_id = mysqli_real_escape_string($conn, $student_id);
    
    // Query to fetch student details
    $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
    
    $result = mysqli_query($conn, $sql);
    
    
    if (!$result) {
        // Query failed
        die("Query failed: " . mysqli_error($conn));
    }
    
    // Check if a record was found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $qr = $row["qr"];
        
        // Display student information
?>
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body" style="margin-left:70px;">
                        <!-- Display student details here -->
                        <p class="card-text"><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $qr ?>" alt="" width="300"></p>
                        <!-- Add more fields as needed -->
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        echo "No records found";
    }
} else {
    echo "Student ID not provided";
}

// Close the connection
mysqli_close($conn);
?>

</body>
</html>
