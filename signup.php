<?php
$showalert = false;
$showerror = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'component/connection.php'; // Assuming $conn is defined here

    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    // --- ADDED THIS LINE ---
    $email = $_POST["email"];
    // -----------------------

    // Check if username already exists
    $sql_check_username = "SELECT username FROM users WHERE username = ?";
    $stmt_check_username = mysqli_prepare($conn, $sql_check_username);
    mysqli_stmt_bind_param($stmt_check_username, "s", $username);
    mysqli_stmt_execute($stmt_check_username);
    mysqli_stmt_store_result($stmt_check_username);

    // --- ADDED THIS BLOCK ---
    // Check if email is empty
    if (empty($email)) {
        $showerror = "Please enter your email address.";
    } else {
        // Check if email already exists
        $sql_check_email = "SELECT email FROM users WHERE email = ?";
        $stmt_check_email = mysqli_prepare($conn, $sql_check_email);
        mysqli_stmt_bind_param($stmt_check_email, "s", $email);
        mysqli_stmt_execute($stmt_check_email);
        mysqli_stmt_store_result($stmt_check_email);

        if (mysqli_stmt_num_rows($stmt_check_email) > 0) {
            $showerror = "Email address already exists.";
        } else {
            if (mysqli_stmt_num_rows($stmt_check_username) > 0) {
                $showerror = "Username already exists.";
            } else {
                if ($password == $cpassword) {

                
                    $plain_password = $password;
                    
                
                $sql_insert = "INSERT INTO users (username, password, email ) VALUES (?, ?, ?)";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                mysqli_stmt_bind_param($stmt_insert, "sss", $username, $plain_password, $email);
                
                    
                if (mysqli_stmt_execute($stmt_insert)) {
                $showalert = true;
                } else {
                $showerror = "Database error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt_insert);
                    
                } else {
             $showerror = "Passwords do not match.";
                }
            }
        }
        mysqli_stmt_close($stmt_check_email);
    }
    // -------------------------
    mysqli_stmt_close($stmt_check_username);
    mysqli_close($conn); // Close the connection
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('img/pexels-joshsorenson-1714208.jpg')
        }

        .container {
            color: white;
            margin-top: 150px;
            width: 50%;
        }
    </style>
</head>
<body>
<?php require 'component/_navbar.php' ?>
<?php
if ($showalert) {
    echo '<div class="alert alert-primary" role="alert"><strong>Submitted</strong> Your data has been submitted. You can now login.</div>';
}
if ($showerror) {
    echo "<div class='alert alert-danger' role='alert'><strong>Error</strong> " . $showerror . "</div>";
}
?>

<div class="container">
    <h1 class="text-center">Signup to our Website</h1>
    <form action="/login project/signup.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text" style="color: grey">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="cpassword" class="form-label">Confirm your Password</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword" required>
            <div id="passwordHelp" class="form-text" style="color: grey">Make sure to type the same password.</div>
            <div id="link" style="color:while; font-size: 18px"><a href="login.php" >Already have an account! Click here to login</a></div>
        </div>
        <button type="submit" class="btn btn-primary">Signup</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>