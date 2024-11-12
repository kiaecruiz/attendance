<!DOCTYPE html>
<html>
<head>
    <title>Edit Schedule Details</title>

</head>
<body>
<div class="container mt-3">
    <form class="needs-validation" action="schedule_resource/updateAddSchedule.php" method="post" autocomplete="off" enctype="multipart/form-data" novalidate>
        <div class="mb-3 mt-3">
            <label for="grade_id">Select Grade:</label>
            <?php
            include('../../db_connection.php');

            // Fetch grades from database
            $gradeSql = "SELECT grade_id, grade FROM grade";
            $gradeResult = $conn->query($gradeSql);

            echo '<select class="form-select" id="grade_id" name="grade_id">';
            echo '<option selected disabled>Select grade</option>';

            while ($row = $gradeResult->fetch_assoc()) {
                echo '<option value="' . $row['grade_id'] . '">' . $row['grade'] . '</option>';
            }

            echo '</select>';

            $conn->close();
            ?>
        </div>

        <div class="mb-3 mt-3" id="subjectList">
            <!-- Subjects will be dynamically loaded here based on grade selection -->
        </div>

        <div class="mb-3 mt-3" id="teacherList">
            <!-- Teachers will be dynamically loaded here based on subject selection -->
        </div>

        <div class="mb-3 mt-3">
            <label for="section_id">Select Section:</label>
            <?php
            include('../../db_connection.php');

            // Fetch grades from database
            $gradeSql = "SELECT section_id, section FROM sections";
            $gradeResult = $conn->query($gradeSql);

            echo '<select class="form-select" id="section_id" name="section_id">';
            echo '<option selected disabled>Select section</option>';

            while ($row = $gradeResult->fetch_assoc()) {
                echo '<option value="' . $row['section_id'] . '">' . $row['section'] . '</option>';
            }

            echo '</select>';

            $conn->close();
            ?>
        </div>

        <div class="mb-3 mt-3">
                <label for="start_time">Start Time:</label>
                <input type="time" class="form-control" id="start_time" name="start_class" required>
            </div>

            <div class="mb-3 mt-3">
                <label for="end_time">End Time:</label>
                <input type="time" class="form-control" id="end_time" name="end_class" required>
            </div>

        <button type="submit" name="submit" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add
        </button>
    </form>
</div>

<script>
    // Function to fetch subjects based on selected grade
    function fetchSubjects(gradeId) {
        $.ajax({
            url: 'schedule_resource/getSubjects.php', // Replace with your PHP file to fetch subjects
            type: 'GET',
            data: { grade_id: gradeId },
            success: function(response) {
                $('#subjectList').html(response); // Update subjects in the dropdown
                // Trigger change event on subject dropdown if already selected
                var selectedSubject = $('#subject_id').val();
                if (selectedSubject) {
                    fetchTeachers(selectedSubject); // Fetch teachers based on selected subject
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching subjects:', error);
            }
        });
    }

    // Function to fetch teachers based on selected subject
    function fetchTeachers(subjectId) {
        $.ajax({
            url: 'schedule_resource/getTeachers.php', // Replace with your PHP file to fetch teachers
            type: 'GET',
            data: { subject_id: subjectId },
            success: function(response) {
                $('#teacherList').html(response); // Update teachers in the dropdown
            },
            error: function(xhr, status, error) {
                console.error('Error fetching teachers:', error);
            }
        });
    }

    // Event listener for grade selection change
    $(document).on('change', '#grade_id', function() {
        var gradeId = $(this).val();
        if (gradeId) {
            fetchSubjects(gradeId); // Fetch subjects when grade is selected
        }
    });

    // Event listener for subject selection change
    $(document).on('change', '#subject_id', function() {
        var subjectId = $(this).val();
        if (subjectId) {
            fetchTeachers(subjectId); // Fetch teachers when subject is selected
        }
    });
</script>

</body>
</html>
