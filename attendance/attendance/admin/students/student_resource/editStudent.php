<!DOCTYPE html>
<html>
<head>
    <title>Edit Student Details</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
</head>
<body>

<?php
include("../../db_connection.php");

$id = $_POST['userid']; // Assuming you're passing student ID via POST from another page

// SQL query to fetch student details along with grade and section names
$sql = "SELECT s.*, g.grade, sec.section
        FROM students s
        INNER JOIN grade g ON s.grade_id = g.grade_id
        INNER JOIN sections sec ON s.section_id = sec.section_id
        WHERE s.student_id = " .$id;

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
?>
<div class="container mt-3">
    <form method="post" action="student_resource/updateStudent.php?id=<?php echo $id; ?>" autocomplete="off" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
            <label for="firstName">LRN:</label>
            <input type="text" class="form-control" id="student_lrn" name="student_lrn" value="<?php echo htmlspecialchars($row['student_lrn']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="firstName">First Name:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($row['firstName']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="lastName">Last Name:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($row['lastName']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="guardian">Guardian Name:</label>
            <input type="text" class="form-control" id="guardian" name="guardian" value="<?php echo htmlspecialchars($row['guardian']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="guardianContact">Guardian Contact Number:</label>
            <input type="text" class="form-control" id="guardianContact" name="guardianContact" value="<?php echo htmlspecialchars($row['guardianContact']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="grade_id">Select Grade:</label>
            <select class="form-select" id="grade_id" name="grade_id">
                <option selected disabled>Select grade</option>
                <?php
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
            <label for="section_id">Select Section:</label>
            <select class="form-select" id="section_id" name="section_id">
                <option selected disabled>Select section</option>
                <?php
                $sectionSql = "SELECT section_id, section FROM sections";
                $sectionResult = $conn->query($sectionSql);

                while ($sectionRow = $sectionResult->fetch_assoc()) {
                    $selected = ($sectionRow['section_id'] == $row['section_id']) ? 'selected' : '';
                    echo '<option value="' . $sectionRow['section_id'] . '" ' . $selected . '>' . $sectionRow['section'] . '</option>';
                }
                ?>
            </select>
        </div>
     
        <button name="edit" type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
    </form>
</div>
<?php
}
mysqli_close($conn);
?>

</body>
</html>
