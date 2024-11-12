

<!Doctype HTML>
<html>
<head>
<link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>User</title>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
    <link rel="stylesheet" href="style/subpackages.css" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
</head>


<body>
	


<?php
require '../db_connection.php';
if(isset($_POST["submit"])){
  $adminName = $_POST["adminName"];
  $username = $_POST["username"];
  $password = $_POST["password"];


 
      $query = "INSERT INTO admin (adminName, username, password)
      VALUES('$adminName', '$username', '$password')";
      mysqli_query($conn, $query);
       
      ?>
       <script>
swal(
   { title: "New User Added",
text: "",
icon: "success",

buttons: {

catch: {
text: "OK",
value: "Ok",
},

},
})
.then((value) => {
switch (value) {


case "Ok":
  window.location.href='../userSettings.php'; 
 
  
 
break;


}
});

</script><?php
   

  
    }


?>
</body>


</html>