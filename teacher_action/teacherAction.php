
  
  <!DOCTYPE html>
<html>
<head>
    <title></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 
</head>
<body>

<?php
include("../db_connection.php");

$id = $_POST['userid']; 
$sql = "SELECT * FROM subject where subject_id = " . $id;

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
?>
<div class="container mt-3">
    <form method="post" action="teacher_action/update.php?id=<?php echo $id; ?>" autocomplete="off" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
      <label for="grade" class="form-label">Select list (select one):</label>
      <select class="form-select"  name="actionTake">
    <option selected>Open this select action</option>
   
      <option value = "Home Visit">Home Visit</option>
      <option value = "Call Parent">Call Parent</option>
      <option value = "LARS">LARS</option>

    </select>
      </div>
      <div class="mb-3 mt-3">
        <label for="text">Result:</label>
        <input type="text" class="form-control"  placeholder="Enter result" id="result" name="result" required value="" style="width:100%;">
      </div>
      
     

        

        <button name="edit" type="submit" class="btn btn-primary">Confirm</button>
    </form>
</div>
<?php
}
$conn->close();
?>


</body>
</html>
