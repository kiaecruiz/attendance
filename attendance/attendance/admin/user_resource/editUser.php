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
$sql = "SELECT * FROM admin where admin_id = " . $id;

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
?>
<div class="container mt-3">
    <form method="post" action="user_resource/updateEditUser.php?id=<?php echo $id; ?>" autocomplete="off" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
        <label for="text">Admin Name:</label>
        <input type="text" class="form-control"  placeholder="Enter admin name" id="adminName" name="adminName" value="<?php echo htmlspecialchars($row['adminName']); ?>" style="width:100%;">
      </div>
      <div class="mb-3 mt-3">
        <label for="text">Username:</label>
        <input type="text" class="form-control"  placeholder="Enter username" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" style="width:100%;">
      </div>
      <div class="mb-3 mt-3">
        <label for="text">Password:</label>
        <input type="password" class="form-control"  placeholder="Enter password" id="password" name="password" value="<?php echo htmlspecialchars($row['password']); ?>" style="width:100%;">
      </div>
      
     

        

        <button name="edit" type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
    </form>
</div>
<?php
}
$conn->close();
?>


</body>
</html>
