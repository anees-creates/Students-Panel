<?php
require '../component/connection.php';
include '../common/session_handler.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $degree_id = (int)$_GET['id'];

    $query = "DELETE FROM degrees WHERE D_ID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $degree_id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Degree deleted successfully!";
            $_SESSION['message_type'] = 'alert alert-success';
        } else {
            $_SESSION['message'] = "Error deleting degree: " . $stmt->error;
            $_SESSION['message_type'] = 'alert alert-danger';
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Error preparing statement: " . mysqli_error($conn);
        $_SESSION['message_type'] = 'alert alert-danger';
    }
} else {
    $_SESSION['message'] = "Invalid degree ID.";
    $_SESSION['message_type'] = 'alert alert-warning';
}

$conn->close();
header('Location: Manage degree.php');
exit();
?>