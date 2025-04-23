<?php
require 'component/connection.php';
include 'component/_navbar.php';
$error="";
$success="";
if (isset($_GET['token'])) {
  $token = $_GET['token'];

  if (empty($token)) {
      $error = "Invalid reset link.";
  } else {
      $sql = "SELECT snum, token_expiry_at FROM users WHERE reset_token_hash = '$token'";
      $query = mysqli_query($conn, $sql);
      $user = mysqli_fetch_assoc($query);

      if ($user) {
          $expiry = strtotime($user['token_expiry_at']);
          $now = time();

          if ($expiry < $now) {
              $error = "Reset link has expired. Please request a new one.";
            
              $clear_sql = "UPDATE users SET reset_token_hash = NULL, reset_token_expiry = NULL WHERE snum = " . $user['snum'];
              mysqli_query($conn, $clear_sql);
          }
      } else {
          $error = "Invalid reset link.";
      }
  }
} else {
  $error = "Invalid reset link.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  if (isset($_POST['token']) && $_POST['token'] === $token && empty($error)) {
      $new_password = $_POST['new_password'];
      $confirm_password = $_POST['confirm_password'];

      
      if (empty($new_password) || empty($confirm_password)) {
          $error = "Please enter and confirm your new password.";
      } elseif ($new_password !== $confirm_password) {
          $error = "New passwords do not match.";
      } elseif (strlen($new_password) < 6) {
          $error = "New password must be at least 6 characters long.";
      } else {
          
          $plain_password = $new_password;

          // Update the user's password in the database and clear the reset token
          $update_password_sql = "UPDATE users SET password = '$plain_password', reset_token = NULL, token_expiry_at = NULL WHERE reset_token_hash = '$token'";
          $update_password_query = mysqli_query($conn, $update_password_sql);

          if ($update_password_query) {
              $success = "Your password has been successfully reset. You can now <a href='login.php'>log in</a>.";
          } else {
              $error = "Error updating password. Please try again.";
              // Consider logging the database error
          }
      }
  } else {
      $error = "Invalid request."; // Token mismatch or error during initial validation
  }
}

?>













<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .password-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .password-container h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
            color: #495057;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #dc3545;
            margin-top: 10px;
        }
        .success-message {
            color: #28a745;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <h2>Set New Password</h2>

        <?php
        // PHP logic for token validation and form display will go here
        ?>

        <form  method="post" >
            <div class="form-group">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>">
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>

        <p class="mt-3 text-muted text-center"><a href="index.php">Back to Login</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>