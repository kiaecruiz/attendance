<?php
include('../../db_connection.php');

if (isset($_GET['grade_id'])) {
    $gradeId = $_GET['grade_id'];

    // SQL query to fetch subjects based on grade ID
    $subjectSql = "SELECT subject_id, subject FROM subject WHERE grade_id = $gradeId";
    $subjectResult = $conn->query($subjectSql);

    if ($subjectResult->num_rows > 0) {
        // Build options for subjects dropdown
        $output = '<select class="form-select" id="subject_id" name="subject_id">';
        $output .= '<option selected disabled>Select subject</option>';

        while ($row = $subjectResult->fetch_assoc()) {
            $output .= '<option value="' . $row['subject_id'] . '">' . $row['subject'] . '</option>';
        }

        $output .= '</select>';

        echo $output;
    } else {
        echo '<select class="form-select" id="subject_id" name="subject_id">';
        echo '<option selected disabled>No subjects found</option>';
        echo '</select>';
    }
} else {
    echo '<select class="form-select" id="subject_id" name="subject_id">';
    echo '<option selected disabled>Error: Grade ID not specified</option>';
    echo '</select>';
}

$conn->close();
?>
