<?php
// getSubjects.php

include('../db_connection.php');

if (isset($_GET['grade_id'])) {
    $gradeId = intval($_GET['grade_id']); // Sanitize input

    // Query to fetch subjects based on grade ID
    $subjectSql = "SELECT subject_id, subject FROM subject WHERE grade_id = $gradeId";
    $subjectResult = $conn->query($subjectSql);

    if ($subjectResult->num_rows > 0) {
        echo '<label for="subject" class="form-label">Select Subject:</label>';
        echo '<select class="form-select" id="subject_id" name="subject_id">';
        echo '<option selected>Select subject</option>';

        while ($row = $subjectResult->fetch_assoc()) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['subject'] . '</option>';
        }

        echo '</select>';
    } else {
        echo '<select class="form-select" id="subject_id" name="subject_id">';
        echo '<option selected disabled>No subjects found</option>';
        echo '</select>';
    }
} else {
    echo '<select class="form-select" id="subject_id" name="subject_id">';
    echo '<option selected disabled>Select a grade first</option>';
    echo '</select>';
}

$conn->close();
?>
