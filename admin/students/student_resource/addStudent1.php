<div class="container mt-3">
    <form action="student_resource/updateAddstudent1.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="image-preview" id="imagePreview">
            <img src="" alt="Image Preview" style="width: 110px; height: 110px; border-radius: 50%;">
        </div>
        <div class="mb-3 mt-3">
            <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
        </div>

        <div class="mb-3 mt-3">
            <label for="student_lrn">Student LRN:</label>
            <input type="text" class="form-control" placeholder="Enter student lrn" id="student_lrn" name="student_lrn" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="firstName">First Name:</label>
            <input type="text" class="form-control" placeholder="Enter first name" id="firstName" name="firstName" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="lastName">Last Name:</label>
            <input type="text" class="form-control" placeholder="Enter last name" id="lastName" name="lastName" required style="width:100%;">
        </div>
        <div class="mb-3 mt-3">
            <label for="gender">Select Gender:</label>
            <select class="form-select" id="gender" name="gender" >
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

           
        </div>

        <div class="mb-3 mt-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" placeholder="Enter email" id="email" name="email" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="guardian">Guardian Name:</label>
            <input type="text" class="form-control" placeholder="Enter guardian name" id="guardian" name="guardian" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="guardianContact">Guardian Contact Number:</label>
            <input type="text" class="form-control" placeholder="Enter guardian contact number" id="guardianContact" name="guardianContact" required style="width:100%;">
        </div>

        <div class="mb-3 mt-3">
            <label for="grade_id">Select Grade:</label>
            <?php
            include('../../db_connection.php');

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

        <div class="mb-3 mt-3">
            <label for="section_id">Select Section:</label>
            <?php
            include('../../db_connection.php');

            // Fetch grades from database
            $gradeSql = "SELECT section_id, section FROM sections";
            $gradeResult = $conn->query($gradeSql);

            echo '<select class="form-select" id="section_id" name="section_id" onchange="fetchSubjects(this.value)">';
            echo '<option selected disabled>Select section</option>';

            while ($row = $gradeResult->fetch_assoc()) {
                echo '<option value="' . $row['section_id'] . '">' . $row['section'] . '</option>';
            }

            echo '</select>';

            $conn->close();
            ?>
        </div>

        <button type="button" class="btn btn-secondary form-control qr-generator" style="background-color:#90C1D7;" onclick="generateQrCode()">✨Generate QR Code!✨</button>
        
        <div class="qr-con text-center" style="display: none;">
            <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
            <p>Take a picture with your QR code.</p>
            <img class="mb-4" src="" id="qrImg" alt="">
        </div>

        <div class="modal-footer modal-close" style="display: none;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add
            </button>
        </div>
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

    function generateRandomCode(length) {
        const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        let randomString = '';

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            randomString += characters.charAt(randomIndex);
        }

        return randomString;
    }

    function generateQrCode() {
        const qrImg = document.getElementById('qrImg');
        const generatedCodeInput = document.getElementById('generatedCode');

        let text = generateRandomCode(10);
        generatedCodeInput.value = text;

        if (text === "") {
            alert("Please enter text to generate a QR code.");
            return;
        } else {
            const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(text)}`;

            qrImg.src = apiUrl;
            document.querySelector('.modal-close').style.display = '';
            document.querySelector('.qr-con').style.display = '';
            document.querySelector('.qr-generator').style.display = 'none';
        }
    }
</script>
