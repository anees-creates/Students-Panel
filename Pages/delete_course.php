<?php
require '../component/connection.php';
include '../common/session_handler.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
   
    $course_id = (int)$_GET['id'];
     $sql="DELETE FROM courses WHERE C_ID=$course_id";
     $query=mysqli_query($conn,$sql);
}
if($query && mysqli_affected_rows($conn)>0){
          $_SESSION['message']="<div class='alert alert-success'>Course is successfully deleted</div>";
          
}
else{
    $_SESSION['message']="<div class='alert alert-danger'>Error deleting the course</div>";
   
}
$conn->close();
    header('Location:Manage courses.php');
    exit();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-YCJUS2j0HgxXla6QDeyRH6Rm6i7edGiyg7vhnzPdCmYyR1gOGuwQKTolRBSflYXeu" crossorigin="anonymous">
</head>

<body>
    
</body>
</html>