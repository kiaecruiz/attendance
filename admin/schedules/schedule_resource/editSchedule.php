<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher Details</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 
</head>
<body>

<?php
include("../../db_connection.php");

$id = $_POST['userid']; 
$sql = "SELECT * FROM schedules where schedule_id = " . $id;

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
?>
<div class="container mt-3">
    <form method="post" action="schedule_resource/updateEditSchedule.php?id=<?php echo $id; ?>" autocomplete="off" enctype="multipart/form-data">
    
    <div class="mb-3 mt-3">
                <label for="start_time">Start Time:</label>
                <input type="time" class="form-control" id="start_time" name="start_class" value="<?php echo htmlspecialchars($row['start_class']); ?>">
            </div>

            <div class="mb-3 mt-3">
                <label for="end_time">End Time:</label>
                <input type="time" class="form-control" id="end_time" name="end_class" value="<?php echo htmlspecialchars($row['end_class']); ?>">
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
