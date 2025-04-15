<?php

session_start(); // Start the session at the beginning of the file

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php"); // Redirect to login if not logged in
    exit();
}

// Now, the user is logged in, so you can display the welcome content

// ... rest of your welcome page content ...

?>
