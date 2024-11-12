

<!Doctype HTML>
<html>
<head>
<link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>Admin - Packages</title>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
  
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    

  <?php
include("../../db_connection.php");

// Assuming you're receiving teacher_id via POST method
$student_id = $_POST['userid'];

// Ensure $teacher_id is properly sanitized (for example, using mysqli_real_escape_string)
$student_id = mysqli_real_escape_string($conn, $student_id);

$sql = "SELECT * FROM students
        WHERE student_id = $student_id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    // Query failed
    die("Query failed: " . mysqli_error($conn));
}

// Assuming only one row will be fetched since it's based on a specific teacher_id
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);





?>

 



<div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
      <img style="width: 300px; height:250px; margin: 10% 2% 3% 5%;" src="student_resource/image/<?= $row['image']; ?>" class="img-fluid rounded" alt="...">
    </div>
    <div class="col-md-8">
      <div class="card-body" style="margin-left:20px;">
        <h5 class="card-title"><span style="color: #0048ad; font-weight: 500;">Name : </span><?php echo $row['firstName']; ?>&nbsp;<?php echo $row['lastName']; ?></h5>
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Email: </span><?php echo $row['email']; ?></p>
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Guardian : </span><?php echo $row['guardian']; ?></p>
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Guardian Contact Number: </span><?php echo $row['guardianContact']; ?></p>
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Grade : </span><?php echo $row['grade_id']; ?></p>
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Section : </span><?php echo $row['section_id']; ?></p>
     
    
      </div>
    </div>
  </div>
</div>

<?php } else {
    echo "No records found";
}

// Close the connection
mysqli_close($conn);
?> 

</body>
</html>