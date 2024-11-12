

<!Doctype HTML>
<html>
<head>
<link rel="shortcut icon" href="../images/logo.jpg" type="image/x-icon">
    <title>Manage Teachers</title>
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
  $name = $_POST["name"];
  $courtesy = $_POST["courtesy"];
  $contact = $_POST["contact"];
  $username = $_POST["username"];
  $password = $_POST["password"];
  $subject = $_POST["subject_id"];
  $grade = $_POST["grade_id"];
  if($_FILES["image"]["error"] == 4){
    echo
    "<script> alert('Image Does Not Exist'); </script>"
    ;
  }
  else{
    $fileName = $_FILES["image"]["name"];
    $fileSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if ( !in_array($imageExtension, $validImageExtension) ){
      echo
      "
      <script>
        alert('Invalid Image Extension');
      </script>
      ";
    }
    else if($fileSize > 1000000){
      echo
      "
      <script>
        alert('Image Size Is Too Large');
      </script>
      ";
    }
    else{
      $newImageName = uniqid();
      $newImageName .= '.' . $imageExtension;

      move_uploaded_file($tmpName, 'image/' . $newImageName);
      $query = "INSERT INTO teachers (name, image, courtesy, contact, username, password, subject_id, grade_id)
      VALUES('$name', '$newImageName', '$courtesy', '$contact','$username','$password','$subject','$grade')";
      mysqli_query($conn, $query);
     
      ?>
       <script>
swal(
   { title: "New Teacher Added",
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

  }
}
?>
</body>


</html>