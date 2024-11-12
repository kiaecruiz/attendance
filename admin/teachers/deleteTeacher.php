
<?php
   




   include("../db_connection.php");
   
     
   
   $id = $_POST['id'];
   $sql = "SELECT * from teachers WHERE teacher_id=".$id;
   
   
   
   
   
   
   $result = mysqli_query($conn,$sql);
   while( $row = mysqli_fetch_array($result) ){
   ?>
   
   
   
                  
   <div class="container mt-3">
   <form class="" method="post" action="updateDeleteTeacher.php?id=<?php echo $id; ?>">
         
         <div class="mb-3 mt-3">
           <h3>Are you sure you want to delete?&nbsp;<span style="color:red;"><?php echo $row['name'];?></span></h3>
          
         </div>
         
         <button name="delete" type="submit" class="btn btn-danger"><i class="fa fa-trash icons"></i> &nbsp;&nbsp;Confirm</button>
        
       
   
         
         
      
         
   
       </form>
     </div>
    
   <?php }
   
   
   ?>
   
   