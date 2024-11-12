

<!Doctype HTML>
<html>
<head>
<link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>Teacher</title>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
 
 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    

  <?php
include("../db_connection.php");

// Assuming you're receiving teacher_id via POST method
$teacher_id = $_POST['userid'];

// Ensure $teacher_id is properly sanitized (for example, using mysqli_real_escape_string)
$teacher_id = mysqli_real_escape_string($conn, $teacher_id);

$sql = "SELECT t.name, t.contact, t.username, t.courtesy, t.password, t.image, g.grade, s.subject
        FROM teachers t
        JOIN grade g ON t.grade_id = g.grade_id
        JOIN subject s ON g.grade_id = s.grade_id
        WHERE t.teacher_id = $teacher_id";

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
      <img style="width: 300px; height:250px; margin: 10% 2% 3% 5%;" src="image/<?= $row['image']; ?>" class="img-fluid rounded" alt="...">
    </div>
    <div class="col-md-8 mt-5">
      <div class="card-body" style="margin-left:20px;">
        <h5 class="card-title"><span style="color: #0048ad; font-weight: 500;"></span><?php echo $row['courtesy']; ?> <?php echo $row['name']; ?></h5>
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Contact Number: </span><?php echo $row['contact']; ?></p>


     
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Grade : </span><?php echo $row['grade']; ?></p>
        <p class="card-text"><span style="color: #0048ad; font-size:15px; font-weight: 500;">Subject : </span><?php echo $row['subject']; ?> </p>
    
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