

    
<div class="container mt-3">
<form class="" action="updateAdd1.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="image-preview" id="imagePreview">
            <img src="" alt="Image Preview" style="width: 110px; height: 110px; border-radius: 50%;">
        </div>
        <div class="mb-3 mt-3">
            <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
        </div>
        <div class="mb-3 mt-3">
            <label for="grade_id">Courtesy Titles:</label>
            <select class="form-select" name="courtesy">
            <option selected disabled>Select courtesy title</option>';
            <option name="courtesy" value="MS.">MS.</option>
            <option name="courtesy" value="MRS.">MRS.</option>
            <option name="courtesy" value="MR.">MR.</option>
         </select>

            
        </div>

        <div class="mb-3 mt-3">
            <label for="name">Name:</label>
            <input type="text" class="form-control" placeholder="Enter name" id="name" name="name" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="contact">Contact Number:</label>
            <input type="text" class="form-control" placeholder="Enter Contact No." id="contact" name="contact" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="username">Username:</label>
            <input type="text" class="form-control" placeholder="Enter username" id="username" name="username" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="password">Password:</label>
            <input type="password" class="form-control" placeholder="Enter password" id="password" name="password" required style="width:100%;">
        </div>
    
        <div class="mb-3 mt-3">
            <label for="grade_id">Select Grade:</label>
            <?php
            include('../db_connection.php');

            // Fetch grades from database
            $gradeSql = "SELECT grade_id, grade FROM grade";
            $gradeResult = $conn->query($gradeSql);

            echo '<select class="form-select" id="grade_id" name="grade_id" onchange="fetchSubjects(this.value)">';
            echo '<option selected disabled>Select grade</option>';

            while ($row = $gradeResult->fetch_assoc()) {
                echo '<option value="' . $row['grade_id'] . '">' . $row['grade'] . '</option>';
            }

            echo '</select>';

            $conn->close();
            ?>
        </div>

        <div class="mb-3 mt-3" id="subjectList">
            <!-- Subjects will be dynamically loaded here -->
        </div>

        <button type="submit" name="submit" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add
        </button>
    </form>
  </div>
  
  <script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('imagePreview');
            output.style.display = 'block';
            output.children[0].src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function fetchSubjects(gradeId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'getSubject.php?grade_id=' + gradeId, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('subjectList').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>