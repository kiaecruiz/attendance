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
    <form method="post" action="subject_resource/updateEditSubject.php?id=<?php echo $id; ?>" autocomplete="off" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
            <label for="grade_id">Select Grade:</label>
            <select class="form-select" id="grade_id" name="grade_id" onchange="fetchSubjects(this.value)">
                <option selected disabled>Select grade</option>
                <?php
                // Fetch grades from database
                $gradeSql = "SELECT grade_id, grade FROM grade";
                $gradeResult = $conn->query($gradeSql);

                while ($gradeRow = $gradeResult->fetch_assoc()) {
                    $selected = ($gradeRow['grade_id'] == $row['grade_id']) ? 'selected' : '';
                    echo '<option value="' . $gradeRow['grade_id'] . '" ' . $selected . '>' . $gradeRow['grade'] . '</option>';
                }
                ?>
            </select>
        </div>
      <div class="mb-3 mt-3">
        <label for="text">Subject Name:</label>
        <input type="text" class="form-control"  placeholder="Enter subject name" id="subject" name="subject" value="<?php echo htmlspecialchars($row['subject']); ?>" style="width:100%;">
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
