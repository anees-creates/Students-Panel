<?php
require '../component/connection.php';
include '../common/session_handler.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $course_id = (int)$_GET['id'];

    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // User confirmed deletion
        $query = "DELETE FROM courses WHERE C_ID = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $course_id);

            if ($stmt->execute()) {
                echo "<div class='message-container'>";
                echo "<h2>Record deleted successfully!</h2>";
                echo "<a href='Manage courses.php' class='back-button'>Back to List</a>";
                echo "</div>";
                exit();
            } else {
                echo "<div class='message-container error'>";
                echo "<h2>Error deleting record: " . $stmt->error . "</h2>";
                echo "<a href='Manage courses.php' class='back-button error'>Back to List</a>";
                echo "</div>";
                exit();
            }

            $stmt->close();
        } else {
            echo "<div class='message-container error'>";
            echo "<h2>Error preparing statement: " . $conn->error . "</h2>";
            echo "<a href='Manage courses.php' class='back-button error'>Back to List</a>";
            echo "</div>";
        }
    } else if (isset($_GET['confirm']) && $_GET['confirm'] === 'no') {
        // User canceled deletion
        header('Location: Manage courses.php');
        exit();
    } else {
        // Show confirmation prompt (HTML Modal)
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Confirm Deletion</title>
            <style>
                .message-container {
                    width: 30%;
                    position: fixed;
                    top: 80px;
                    left: 50%;
                    transform: translateX(-50%);
                    padding: 15px;
                    background-color: #d4edda;
                    border: 1px solid #c3e6cb;
                    color: #155724;
                    border-radius: 5px;
                    text-align: center;
                    z-index: 1000;
                }
                .message-container.error {
                    background-color: #f8d7da;
                    border-color: #f5c6cb;
                    color: #721c24;
                }
                .back-button {
                  background-color: #4CAF50;
                  color: white;
                  padding: 10px 20px;
                  text-decoration: none;
                  border-radius: 5px;
                  margin-top: 10px;
                }
                .back-button.error {
                    background-color: #f44336;
                }
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
                }

                .modal-content {
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    text-align: center;
                }

                .modal-content a {
                    margin: 10px;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                }

                .modal-content a.yes {
                    background-color: #f44336;
                    color: white;
                }

                .modal-content a.no {
                    background-color: #4CAF50;
                    color: white;
                }
            </style>
        </head>
        <body>
            <div class="modal-overlay">
                <div class="modal-content">
                    <h2>Are you sure you want to delete this record?</h2>
                    <a href="delete_course.php?id=<?php echo $course_id; ?>&confirm=yes" class="yes">Yes, Delete</a>
                    <a href="delete_course.php?id=<?php echo $course_id; ?>&confirm=no" class="no">No, Cancel</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    // Invalid ID
    header('Location: Manage courses.php');
    exit();
}
$conn->close();
?>