<?php
require 'component/connection.php';
require 'component/_navbar.php';
$error="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $email=$_POST['email'];
  
  if (empty($email)) {
    $error = "Please enter your email address.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
} else {
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $query = mysqli_query($conn,$sql);
  $result = mysqli_fetch_assoc($query);
  $numrows = mysqli_num_rows($query);
}
if($numrows==1){
  $token = bin2hex(random_bytes(32));
  $expiry = date("Y-m-d H:i:s", time() + (60 * 30));

  
  $update_sql = "UPDATE users SET reset_token_hash = '$token', token_expire_at = '$expiry' WHERE email = '$email'";
  $update_query = mysqli_query($conn, $update_sql);

  if ($update_query) {
      
      $reset_link ="http://localhost/login project/new_pass.php?token=" . $token;
      $subject = "Password Reset Request";
      $message = "Dear user,\n\nYou have requested to reset your password. Please click on the following link to set a new password:\n\n" . $reset_link . "\n\nThis link will expire in 30 minutes.\n\nIf you did not request this, please ignore this email.\n\nSincerely,\nstudent panel.com";
      $headers = "From: student panel.com\r\n";
      $headers .= "Reply-To: student panel \r\n";
      $headers .= "Content-Type: text/plain\r\n";

      if (mail($email, $subject, $message, $headers)) {

          header("Location: check_email.php");
          exit();
      } else {
          $error = "Failed to send the password reset email. Please try again.";
        
      }
  } else {
      $error = "Error updating reset token in the database.";
      
  }
}
elseif ($_SERVER["REQUEST_METHOD"]=="POST" && empty($error)) {
  $error = "Email address not found.";
}
}
?>
























<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            
            min-height: 100vh;
            background-color: #f4f4f4; /* Optional background color */
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width:30%;
            margin:auto;
            margin-top:120px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
  
    <div class="form-container">
        <h1>Enter your registered email</h1>
        <form method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>