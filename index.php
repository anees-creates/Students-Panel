<?php
$login=false;

$showerror =false;
if ($_SERVER ["REQUEST_METHOD"]=="POST"){
include 'component/connection.php';
$username=$_POST["username"];
$password=$_POST["password"];
 $sql = "select * from users where username='$username' AND password='$password'";

  $result = mysqli_query($conn,$sql);
  $num = mysqli_num_rows($result);
  if($num == 1){
    $login=true;
    session_start();
    $_SESSION['loggedin']= true;
    $_SESSION['username']= $username;
    header ("location: welcome.php");
    
  }
 else {
  $showerror="invalid credantials";}
}
?>


<!doctype html>
<html lang="en">
  <head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN PAGE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script type="text/javascript">
    function preventBack() {
        window.history.forward();
    }

    setTimeout("preventBack()", 0);

    window.onunload = function() {
        null;
    };
</script>
  <style>
  body{
   
    
    background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('img/pexels-joshsorenson-1714208.jpg') 
    
  }
  .container{
    color:white;
    margin-top:200px;
    
    
  }
  
  </style>
  </head>
  <body>
    <?php require 'component/_navbar.php' ?>
    <?php 
    if($login){
    echo '<div class="alert alert-primary" role="alert">
  <strong>Success</strong> Your are logged in to your account
</div>';}
if($showerror){
  echo "<div class='alert alert-danger' role='alert'>
<strong>Error</strong>..$showerror Passwords doesn't match 
</div>";}
?>

   <div class="container" style="width:50%"> <h1 class="text-center">LOGIN in to your Account here</h1>
   <form action="/login project/index.php" method="post">
  <div class="mb-3">
    <label for="username" class="form-label">username</label>
    <input type= "text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
   
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control"  id="password" name="password">
  </div>
  
  
  <button type="submit" class="btn btn-primary" style="width:100px">LOGIN</button>
  <div id="link" style=" font-size: 18px"><a href="signup.php" >Don't have an account! Click here to create one</a></div>
</form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>