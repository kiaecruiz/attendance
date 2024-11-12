<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher Details</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<?php
include("../db_connection.php");

$id = $_POST['userid']; // Assuming you're passing teacher ID via POST from another page

// SQL query to fetch teacher details along with grade and subject names
$sql = "SELECT t.*, g.grade, s.* FROM teachers t INNER JOIN grade g ON t.grade_id = g.grade_id INNER JOIN subject s ON t.subject_id = s.subject_id WHERE t.teacher_id = " . $id;

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
?>
<div class="container mt-3">
    <form method="post" action="updateEditTeacher.php?id=<?php echo $id; ?>" autocomplete="off" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
            <label for="courtesy">Courtesy Titles:</label>
            <select class="form-select" name="courtesy">
            <option selected value="<?php echo htmlspecialchars($row['courtesy']); ?>" name="courtesy"><?php echo htmlspecialchars($row['courtesy']); ?></option>';
            <option name="courtesy" value="MS.">MS.</option>
            <option name="courtesy" value="MRS.">MRS.</option>
            <option name="courtesy" value="MR.">MR.</option>
         </select>

            
        </div>
        <div class="mb-3 mt-3">
            <label for="name">Name:</label>
            <input style="width:100%;" type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="contact">Contact Number:</label>
            <input style="width:100%;" type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($row['contact']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="username">Username:</label>
            <input style="width:100%;" type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
        </div>
        <div class="mb-3 mt-3">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($row['password']); ?>">
        </div>
      
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

        <div class="mb-3 mt-3" id="subjectList">
            <!-- Subjects will be dynamically loaded here -->
            <?php
            // Fetch subjects based on the teacher's grade_id
            $grade_id = $row['grade_id'];
            $subjectSql = "SELECT subject_id, subject FROM subject WHERE grade_id = $grade_id";
            $subjectResult = $conn->query($subjectSql);

            echo '<label for="subject_id">Select Subject:</label>';
            echo '<select class="form-select" id="subject_id" name="subject_id">';
            while ($subjectRow = $subjectResult->fetch_assoc()) {
                $selected = ($subjectRow['subject_id'] == $row['subject_id']) ? 'selected' : '';
                echo '<option value="' . $subjectRow['subject_id'] . '" ' . $selected . '>' . $subjectRow['subject'] . '</option>';
            }
            echo '</select>';
            ?>
        </div>

        <button name="edit" type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
    </form>
</div>
<?php
}
$conn->close();
?>

<script>
    function fetchSubjects(gradeId) {
        $.ajax({
            url: 'getSubject.php',
            type: 'GET',
            data: {
                grade_id: gradeId
            },
            success: function(response) {
                $('#subjectList').html(response);
            }
        });
    }
</script>

</body>
</html>
