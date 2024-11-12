<?php
  include('db_connection.php');
  session_start();
  $user_check=$_SESSION['username2'];
 
  $ses_sql=mysqli_query($conn,"select username from admin where username='$user_check' ");
  $row=mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
  $login_session=$row['username'];
  if(!isset($login_session))
  {
   header("Location: index.php");
  }
?>