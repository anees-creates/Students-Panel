<?php
require '../component/connection.php';
include '../common/session_handler.php';


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $degree_id = (int)$_GET['id'];

    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // User confirmed deletion
        $query = "DELETE FROM degrees WHERE D_ID = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $degree_id);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Degree deleted successfully!";
                $_SESSION['message_type'] = 'success';
                header('Location: Manage degree.php');
                exit();
            } else {
                $_SESSION['message'] = "Error deleting degree: " . $stmt->error;
                $_SESSION['message_type'] = 'error';
                header('Location: Manage degree.php');
                exit();
            }

            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Error preparing statement: " . mysqli_error($conn);
            $_SESSION['message_type'] = 'error';
            header('Location: degrees_list.php');
            exit();
        }
    } else if (isset($_GET['confirm']) && $_GET['confirm'] === 'no') {
        // User canceled deletion
        header('Location: Manage degree.php');
        exit();
    } else {
        // Show confirmation prompt (HTML Modal)
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Confirm Delete Degree</title>
            <style>
                .modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                }

                .modal-content {
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    text-align: center;
                }

                .modal-content h2 {
                    margin-bottom: 20px;
                }

                .modal-content a {
                    display: inline-block;
                    padding: 10px 20px;
                    margin: 0 10px;
                    text-decoration: none;
                    border-radius: 5px;
                }

                .modal-content a.yes {
                    background-color: #dc3545;
                    color: white;
                }

                .modal-content a.no {
                    background-color: #28a745;
                    color: white;
                }
            </style>
        </head>
        <body>
            <div class="modal-overlay">
                <div class="modal-content">
                    <h2>Are you sure you want to delete this degree?</h2>
                    <p>Deleting this degree will also remove its association with any courses.</p>
                    <a href="delete_degree.php?id=<?php echo $degree_id; ?>&confirm=yes" class="yes">Yes, Delete</a>
                    <a href="delete_degree.php?id=<?php echo $degree_id; ?>&confirm=no" class="no">No, Cancel</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    // Invalid ID
    header('Location: Manage degree.php');
    exit();
}
$conn->close();
?>