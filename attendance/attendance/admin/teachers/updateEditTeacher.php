
<!Doctype HTML>
<html>
<head>
<link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title></title>
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
	include('../db_connection.php');
    $id = $_GET['id'];
    if ($id==null)
    {
        header("Location:manageTeachers.php");
    }
  
	
	if(isset($_POST["edit"])){
   

	
	$a=$_POST['name'];
    $b=$_POST['contact'];
    $c=$_POST['username'];
    $d=$_POST['password'];
    $e=$_POST['grade_id'];
    $f=$_POST['subject_id'];
    $g=$_POST['courtesy'];
   
   
  
	mysqli_query($conn,"update `teachers` set name='$a',   contact='$b', username='$c', password='$d', grade_id='$e', subject_id='$f', courtesy='$g'  where teacher_id='$id'");
  
?><script>
swal(
   { title: "Update Successfully",
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
  window.location.href='manageTeachers.php'; 
 
  
 
break;


}
});

</script><?php



 }

      
?>
 </body>


</html>