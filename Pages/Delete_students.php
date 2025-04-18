<?php

include '../component/connection.php';
include '../common/session_handler.php';

if (isset($_GET['s_id']) && is_numeric($_GET['s_id'])) {
    $student_id = $_GET['s_id'];

    // Prepare and execute the DELETE query
    $sql="DELETE FROM students WHERE S_ID=$student_id";
    $query=mysqli_query($conn,$sql);

    if ($query){
        
            $_SESSION['message'] = "Student record deleted successfully!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Student record not found or could not be deleted.";
            $_SESSION['message_type'] = 'info'; // Or 'warning' depending on your preference
        }
    }

mysqli_close($conn);

// Redirect back to the student list page

exit();
?>