<?php
    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: home.php");
        
        die();
    }
?>


<?php

$email = $password ="";
$emailErr = $passwordErr = $error ="";

if(isset($_POST["btnLogin"])){

  if(empty($_POST["username"])){
    $emailErr = "Required!";
  }else{
    $email = $_POST["username"];
  }

  if(empty($_POST["password"])){
    $passwordErr = "Required!";
  }else{
    $password = $_POST["password"];
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/login.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="images/logo.jpg" type="image/x-icon">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Attendance Management System - Login</title>
</head>
<body>
    <div class="login-banner">
    <div class="banner">
      <!-- <img src="images/logo1.png" alt=""> -->
  
    </div>
   
    <div class="login-div">
      
      <h2>Login</h2><br>

      <?php
if ($email && $password) {
    include("db_connection.php"); // Assuming this file connects to your database

    // Query to check if the username (email) exists in the database
    $check_email = mysqli_query($conn, "SELECT * FROM teachers WHERE username = '$email'");
    $check_email_row = mysqli_num_rows($check_email);

    // If the username exists
    if ($check_email_row > 0) {
        // Fetch the user details
        while ($row = mysqli_fetch_assoc($check_email)) {
            $user_id = $row["teacher_id"];
            $db_password = $row["password"];

            // Verify the password
            if ($password == $db_password) {
                // Update status to 'Online'
                $update_status = mysqli_query($conn, "UPDATE teachers SET status = 'Online' WHERE teacher_id = $user_id");

                if ($update_status) {
                    // Start a session and store the user_id
                    session_start();
                    $_SESSION["teacher_id"] = $user_id;

                    // Redirect to home.php after successful login
                    header("Location: login.php");
                    exit; // Make sure to exit after redirection
                } else {
                    $error = "Failed to update status to Online";
                }

            } else {
                // Password does not match
                $error = "Incorrect Password!";
            }
        }
    } else {
        // Username (email) does not exist in the database
        $error = "Username not found!";
    }

    // Display error message if any
    if (isset($error)) {
        ?>
        <div class="alert alert-danger" role="alert">
            <strong><?php echo $error; ?></strong>
        </div>
        <?php
    }
}
?>

     <form method="Post">
      <div class="mb-3 mt-3">
        <label for="username">Username:</label>
        <input type="username"  id="username" placeholder="Enter username" name="username" value="<?php echo $email; ?>">
      </div>
      <div class="mb-3">
        <label for="pwd">Password:</label>
        <input type="password"  id="pwd" placeholder="Enter password" name="password">
      </div>
      
      <div class="form-check mb-3">
        <label class="form-check-label">
          <input class="form-check-input" type="checkbox" name="remember" id="check"> Remember me
        </label>
      </div>
      <button class="login" name="btnLogin">Login</button><hr>
   
   
      
      
      
    </div>
    </form>
  
    </div>
    </div>
    
</body>
</html>