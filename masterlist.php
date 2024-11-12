<?php
// Start session (if not already started)
session_start();

// Check if teacher_id is set in session
if (!isset($_SESSION["teacher_id"])) {
    header("Location: index.php");
}

// Assign teacher_id from session to $teacher_id
$teacher_id = $_SESSION["teacher_id"];

// Include database connection script
include("db_connection.php");

// Fetch teacher's name, subject, grade, and section from database using teacher_id
$get_teacher = mysqli_query($conn, "SELECT * FROM teachers WHERE teacher_id='$teacher_id'");

if (!$get_teacher || mysqli_num_rows($get_teacher) === 0) {
    die("Teacher not found.");
}



$row_teacher = mysqli_fetch_assoc($get_teacher);
$teacher_name = $row_teacher['name'];
$teacher_subject = $row_teacher['subject_id'];



        
?>

<?php

// Retrieve grade and section parameters from URL
if (isset($_GET['grade']) && isset($_GET['sections'])) {
    $grade_name = $_GET['grade'];
    $section_name = $_GET['sections'];

    // Perform additional queries or display details based on grade_name and section_name
    // Example: Fetch details from database based on these parameters
    // Example: Display detailed information about the selected grade and section
} else {
    // Handle case where parameters are missing
    die("Grade and section parameters are required.");
}


// Fetch students for the specified grade and section
$view_query = "SELECT * FROM `students` WHERE section_id = '$section_name' AND grade_id = '$grade_name'";
$result = mysqli_query($conn, $view_query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Masterlist - Grade <?php echo htmlspecialchars($grade_name); ?> Section <?php echo htmlspecialchars($section_name); ?></title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/navbars/">
    
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="assets/css/style.css">



<!-- jQuery UI CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">


    

    <!-- Bootstrap core CSS -->
<link href="admin/assets/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>




    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="navbar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/class.css">
    <link rel="stylesheet" href="css/print.css">
    
  </head>
  <body>
    
<main>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary" aria-label="Eighth navbar example">
    <div class="container">
      <a class="navbar-brand" href="#">QR Code Attendance System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExample07">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
           
      
        </ul>
        <form>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0"><a class="nav-link" href="home.php">Back</a></ul>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mt-4">

  
    <div class="row">
      <div class="col-8">
      <?php
// Update action result function
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $new_action = $_POST['new_action'];
    $result_data = $_POST['result_data']; // Get the result data

    // Update query
    $update_query = "UPDATE students SET actionTake = ?, result = ? WHERE student_id = ?";
    $stmt = $conn->prepare($update_query);
    if ($stmt->execute([$new_action, $result_data, $student_id])) {
        $successMessage = "Action updated successfully!";
    }
    $stmt->close();
}

// Check for success message
$successMessage = isset($successMessage) ? $successMessage : '';
?>

<div class="data_table">
    <table id="example" class="table table-striped table-bordered">
        <thead class="table-primary" style="font-size:15px">
            <tr>
                <th>Student LRN</th>
                <th>Student Name</th>
                <th>Qr code</th>
                <th>Status</th>
                <th>Action</th>
                <th>Action Result</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Iterate through the results and display them
            while ($row = mysqli_fetch_assoc($result)) {
                $lrn = $row['student_lrn'];
                $lname = $row['lastName'];
                $fname = $row['firstName'];
                $qr = $row['qr'];
                $status = $row['status'];
                $result2 = $row['result'];
                $action = $row['actionTake'];
            ?>
                <tr style="font-size:14px; padding-top:100px;">
                    <td class='lrn-cell'><?php echo htmlspecialchars($lrn); ?></td>
                    <td class='lrn-cell'><?php echo htmlspecialchars($fname); ?> <?php echo htmlspecialchars($lname); ?></td>
                    <td class='lrn-cell'>
                        <a data-id='<?= $row['student_id']; ?>' class="button-view2">
                            <i class="bi bi-qr-code-scan" style="cursor:pointer; color:blue; font-size:25px;"></i>
                        </a>
                    </td>
                    <?php if ($status == 'Low Risk') : ?>
                            <td>
                                
                                    <span style="color:green;  font-weight:600"><?php echo htmlspecialchars($status); ?></span>
                               
                            </td>
                            <?php elseif ($status == 'Medium Risk') : ?>
                            <td>
                                
                                    <span style="color:orange;  font-weight:600"><?php echo htmlspecialchars($status); ?></span>
                               
                            </td>
                            
                        
                        <?php else : ?>
                            <td class='lrn-cell'>
                            <p style="color:red;  font-weight:600"><?php echo htmlspecialchars($status); ?></p>
                            </td>
                        <?php endif; ?>

                    <?php if ($status == 'Low Risk') : ?>
                        <td>
                            <center>
                                <p style="color:black; font-weight:600"><?php echo htmlspecialchars($action); ?></p>
                            </center>
                        </td>
                    <?php elseif ($action !== '-') : ?>
                        <td>
                            <center>
                                <p style="color:black; font-weight:600"><?php echo htmlspecialchars($action); ?></p>
                            </center>
                        </td>
                    <?php else : ?>
                        <td class='lrn-cell'>
                            <a style="cursor:pointer; text-decoration:none; font-size:10px;" onclick="showActionForm(<?= $row['student_id']; ?>, '<?= htmlspecialchars($action); ?>', '<?= htmlspecialchars($status); ?>')">
                                <button>Take an Action</button>
                            </a>
                        </td>
                    <?php endif; ?>

                    <td style="font-weight:600; text-align:center;"><?php echo htmlspecialchars($result2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Action form modal -->
<div id="actionFormModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:1000;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.3); width:300px;">
        <h2 style="margin-bottom:15px; font-size:18px;">Update Action</h2>
        <form method="POST">
            <input type="hidden" name="student_id" id="student_id">
            <label for="new_action" style="margin-bottom:5px;">New Action:</label>
            <select name="new_action" id="new_action" required style="width:100%; padding:8px; margin-bottom:10px; border-radius:4px; border:1px solid #ccc;">
                <option value="">Select Action</option>
                <!-- Options will be populated by JavaScript -->
            </select>
            <label for="result_data" style="margin-bottom:5px;">Result:</label>
            <input type="text" name="result_data" id="result_data" required style="width:100%; padding:8px; margin-bottom:10px; border-radius:4px; border:1px solid #ccc;">
            <div style="text-align:right;">
                <button type="submit" style="background-color:green; color:white; padding:10px 15px; border:none; border-radius:4px; cursor:pointer;">Update</button>
                <button type="button" onclick="closeActionForm()" style="margin-left:5px; background-color:red; color:white; padding:10px 15px; border:none; border-radius:4px; cursor:pointer;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showActionForm(studentId, currentAction, status) {
    document.getElementById('student_id').value = studentId;
    document.getElementById('new_action').value = currentAction;

    // Clear previous options
    const actionSelect = document.getElementById('new_action');
    actionSelect.innerHTML = '';

    // Set options based on status
    if (status === 'Medium Risk') {
        actionSelect.innerHTML += '<option value="Call Parent">Call Parent</option>';
        actionSelect.innerHTML += '<option value="Home Visit">Home Visit</option>';
    } else if (status === 'High Risk') {
        actionSelect.innerHTML += '<option value="LARS">LARS</option>';
    } else {
        actionSelect.innerHTML += '<option value="No Action">No Action</option>';
    }

    document.getElementById('actionFormModal').style.display = 'block';
}

function closeActionForm() {
    document.getElementById('actionFormModal').style.display = 'none';
}

// Show success alert if exists
<?php if ($successMessage): ?>
    alert("<?php echo htmlspecialchars($successMessage); ?>");
<?php endif; ?>
</script>


        
           
       
     
      </div>

      <div class="col-4">

      <table class="table">
          <thead class="table-primary">
            <tr>
              <th colspan="2" style="text-align: center;">Class Information</th>
              
            </tr>
          </thead>
          <tbody>
          <?php
                       // Assuming $user_id is defined somewhere before this code snippet

                     include("db_connection.php");

                      // Perform query to fetch schedules along with related sections and subjects
                      $view_query = "SELECT sections.*, subject.*, schedules.*,
                      TIME_FORMAT(schedules.start_class, '%h:%i %p') AS start_class,
                      TIME_FORMAT(schedules.end_class, '%h:%i %p') AS end_class
                      FROM schedules
                      JOIN sections ON schedules.section_id = sections.section_id
                      JOIN subject ON schedules.subject_id = subject.subject_id
                      WHERE schedules.teacher_id = '$teacher_id' and schedules.grade_id='$grade_name' and schedules.section_id='$section_name'";

                      $result = mysqli_query($conn, $view_query);

                      if (!$result) {
                          die("Query failed: " . mysqli_error($conn));
                      }

                      // Iterate through the results and display them
                      while ($row = mysqli_fetch_assoc($result)) {
                          $sub = $row['subject'];
                          $sec = $row['section'];
                          $gr = $row['grade_id'];
                          $start = $row['start_class'];
                          $end = $row['end_class'];
                      ?>
                       <?php
                      }
                      ?>



                            <tr>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Subject</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($sub); ?></span></td>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Grade & Section</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($gr); ?>-<?php echo htmlspecialchars($sec); ?></span></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Class Start</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($start); ?></span></td>
                                <td style="text-align: center;"><span style="font-weight: 700; font-size: 13px;">Class End</span><br><span style="font-weight: 700; text-decoration: underline;"><?php echo htmlspecialchars($end); ?></span></td>
                            </tr>
                       
          </tbody>
      </table>
      
      <table class="table mt-4"  id="studentDetails">
        <thead class="table-primary">
          <tr>
            <th colspan="2" style="text-align: center;">Student Profile</th>
            
          </tr>
        </thead>
        <tbody>
        
        
          
        </tbody>
    </table>
     
        

     
      </div>
    </div>
  </div>

  
<script type='text/javascript'>
            $(document).ready(function(){
                $('.button-view').click(function(){
                    var userid = $(this).data('id');
                    $.ajax({
                        url: 'attendanceRecord.php',
                        type: 'post',
                        data: {userid: userid},
                        success: function(response){ 
                            $('.modal-body').html(response); 
                            $('#myModal').modal('show'); 
                        }
                    });
                });
            });
            </script>



 <!---------------------------------------------- View Modal -->
 <div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary" style="color:white">
        <h4 class="modal-title" style="font-size:30px;">Attendance Record</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="background-color:white; color:white; font-size:30px;"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
 
      </div>

      <div class="modal-footer">
                <button type="button" class="btn btn-primary text-white" onclick="printModalContent()">Print Record</button>
              
            </div>
    

    </div>
  </div>
  
</div>

<script type='text/javascript'>
            $(document).ready(function(){
                $('.button-view2').click(function(){
                    var userid = $(this).data('id');
                    $.ajax({
                        url: 'viewQr.php',
                        type: 'post',
                        data: {userid: userid},
                        success: function(response){ 
                            $('.modal-body').html(response); 
                            $('#myModal2').modal('show'); 
                        }
                    });
                });
            });
            </script>



 <!---------------------------------------------- View Modal -->
 <div class="modal" id="myModal2">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background-color:#006400; color:white">
        <h4 class="modal-title" style="font-size:30px;">Student Qr Code</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="background-color:white; color:white; font-size:30px;"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
 
      </div>

      <!-- Modal footer -->
    

    </div>
  </div>
  
</div>


<script type='text/javascript'>
            $(document).ready(function(){
                $('.button-edit').click(function(){
                    var userid = $(this).data('id');
                    $.ajax({
                        url: 'teacher_action/teacherAction.php',
                        type: 'post',
                        data: {userid: userid},
                        success: function(response){ 
                            $('.modal-body').html(response); 
                            $('#myModal3').modal('show'); 
                        }
                    });
                });
            });
            </script>

 <!---------------------------------------------- Edit Modal -->
 <div class="modal" id="myModal3">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background-color:#006400; color:white">
        <h4 class="modal-title"  style="font-size:30px;">Action</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="background-color:white; color:white; font-size:30px;"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

      

  
 
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
  
</div>



<script>
    function printTable() {
        var printContents = document.getElementById('attendanceTable').outerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>


<script>
    // Function to filter by status
    function filterAttendance() {
        var filter = document.getElementById('attendanceFilter').value.toLowerCase();
        var rows = document.querySelectorAll('#attendanceTable tbody tr');

        rows.forEach(function(row) {
            var status = row.children[1].textContent.toLowerCase();
            var display = 'table-row';

            if (filter === 'all') {
                display = 'table-row';
            } else if (status.includes(filter)) {
                display = 'table-row';
            } else {
                display = 'none';
            }

            row.style.display = display;
        });
    }
    function printModalContent() {
        var modalTitle = document.getElementById('attendanceModalLabel').innerText;
        var tableContent = document.getElementById('attendanceTable').outerHTML;
        
        var printContents = `
            <h5>${modalTitle}</h5>
            ${tableContent}
        `;
        
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
 
</script>





</main>



<!-- Bootstrap core JavaScript -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="assets/js/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="assets/js/datatables.min.js"></script>
<!-- Additional custom scripts -->
<script src="assets/js/pdfmake.min.js"></script>
<script src="assets/js/vfs_fonts.js"></script>
<script src="assets/js/custom.js"></script>
<!-- Initialize DataTables -->
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>

<script>
    // Event listener for the table rows with LRN
    $('#example tbody').on('click', 'tr', function () {
        var lrn = $(this).find('td:first').text(); // Get LRN from the clicked row
        fetchStudentDetails(lrn); // Call function to fetch and display student details
    });

    // Function to fetch and display student details
    function fetchStudentDetails(lrn) {
        $.ajax({
            type: 'GET',
            url: 'fetch_student.php', // PHP script to fetch student details
            data: {lrn: lrn},
            success: function (response) {
                $('#studentDetails tbody').html(response); // Update student details table
            },
            error: function () {
                alert('Error fetching student details.');
            }
        });
    }
</script>
      
  </body>
</html>
