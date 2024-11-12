

<?php
// fetch_student_details.php

// Include database connection script
include("db_connection.php");

// Ensure LRN is provided and sanitize input
if (isset($_GET['lrn'])) {
    $lrn = mysqli_real_escape_string($conn, $_GET['lrn']);

    // Fetch student details based on LRN
    $query = "SELECT * FROM students WHERE student_lrn = '$lrn'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Construct HTML table rows for student details
        while ($row = mysqli_fetch_assoc($result)){
        $lname = $row['lastName'];
        $fname = $row['firstName'];
        $lrn = $row['student_lrn'];
          ?>
              <tr>
            <td><center><img src="admin/students/student_resource/image/<?= $row['image']; ?>" alt="" srcset="" style="width:200px; height:200px;" ></center></td>
          </tr>
          <tr>
            <td style="font-size:15px;"><strong style="margin-left:18px;">Student LRN: </strong><?php echo htmlspecialchars($lrn); ?></td>
          </tr>
          <tr>
            <td style="font-size:15px;"><strong style="margin-left:18px;">Student Name: </strong><?php echo htmlspecialchars($fname); ?> <?php echo htmlspecialchars($lname); ?></td>
          </tr>
          <tr>
            <td><strong><a style="font-size:18px; margin-left:18px; cursor:pointer;" data-id='<?= $row['student_id']; ?>' class="button-view">Check Attendance Record</a></strong></td>
          </tr>

          
  
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



      
           <?php
        }
    } else {
        echo "<tr><td colspan='2'>Student details not found.</td></tr>";
    }

    mysqli_free_result($result);
    mysqli_close($conn);
} else {
    echo "<tr><td colspan='2'>LRN parameter is missing.</td></tr>";
}
?>


    
