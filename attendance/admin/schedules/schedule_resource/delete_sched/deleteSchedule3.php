
<?php
   




   include("../../../db_connection.php");
   
     
   
   $id = $_POST['id'];
   $sql = "SELECT 
                        sc.schedule_id,
                        sc.start_class,
                        sc.end_class,
                        su.subject,
                        se.*,
                        tch.name,
                        g.*
                        FROM 
                            schedules sc
                        INNER JOIN 
                            subject su ON sc.subject_id = su.subject_id
                        INNER JOIN 
                            sections se ON sc.section_id = se.section_id
                        INNER JOIN 
                            teachers tch ON sc.teacher_id = tch.teacher_id
                        INNER JOIN 
                            grade g ON tch.grade_id = g.grade_id WHERE sc.schedule_id=".$id;
   
   
   
   
   
   
   $result = mysqli_query($conn,$sql);
   while( $row = mysqli_fetch_array($result) ){
   ?>
   
   
   
                  
   <div class="container mt-3">
   <form class="" method="post" action="schedule_resource/delete_sched/updateDeleteSchedule3.php?id=<?php echo $id; ?>">
         
         <div class="mb-3 mt-3">
           <h3>Are you sure you want to delete?&nbsp;<span style="color:red;">Grade&Section:&nbsp;<?php echo $row['grade_id'];?>&nbsp;-<?php echo $row['section_id'];?>&nbsp;<?php echo $row['subject'];?></span></h3>
          
         </div>
         
         <button name="delete" type="submit" class="btn btn-danger"><i class="fa fa-trash icons"></i> &nbsp;&nbsp;Confirm</button>
        
       
   
         
         
      
         
   
       </form>
     </div>
    
   <?php }
   
   
   ?>
   
   