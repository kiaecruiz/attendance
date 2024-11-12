
    
<div class="container mt-3">
<form class="" action="subject_resource/updateadd.php" method="post" >
      <div class="mb-3 mt-3">
      <label for="grade" class="form-label">Select list (select one):</label>
      <select class="form-select" id="grade_id" name="grade_id">
    <option selected>Open this select grade</option>
      <?php
     
         include('../db_connection.php');
        $gradeSql = "SELECT grade, grade_id FROM grade";
        $gradeResult = $conn->query($gradeSql);

   
     while($row = $gradeResult->fetch_assoc())
     {
       echo "<option value = ".$row['grade_id'].">".$row['grade'],"</option>";
     }
     $conn->close();
?>
    </select>
      </div>
      <div class="mb-3 mt-3">
        <label for="text">Subject Name:</label>
        <input type="text" class="form-control"  placeholder="Enter subject name" id="subject" name="subject" required value="" style="width:100%;">
      </div>

    
  
    
      <button name="submit" class="button-add1" style="padding: 8px 25px; color:white; background:#00A9FF; border:none;"><i class="fa fa-plus icons"></i> &nbsp;&nbsp;Add</button>
    </form>
  </div>
  
