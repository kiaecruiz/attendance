<?php
include('../../db_connection.php');

if (isset($_GET['subject_id'])) {
    $subjectId = $_GET['subject_id'];

    // SQL query to fetch teachers based on subject ID
    $teacherSql = "SELECT teacher_id, name FROM teachers WHERE subject_id = $subjectId";
    $teacherResult = $conn->query($teacherSql);

    if ($teacherResult->num_rows > 0) {
        // Build options for teachers dropdown
        $output = '<select class="form-select" id="teacher_id" name="teacher_id">';
        $output .= '<option selected disabled>Select teacher</option>';

        while ($row = $teacherResult->fetch_assoc()) {
            $output .= '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
        }

        $output .= '</select>';

        echo $output;
    } else {
        echo '<select class="form-select" id="teacher_id" name="teacher_id">';
        echo '<option selected disabled>No teachers found</option>';
        echo '</select>';
    }
} else {
    echo '<select class="form-select" id="teacher_id" name="teacher_id">';
    echo '<option selected disabled>Error: Subject ID not specified</option>';
    echo '</select>';
}

$conn->close();
?>
