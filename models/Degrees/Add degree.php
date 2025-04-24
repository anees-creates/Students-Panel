<?php 
ob_start();
include '../../config/connection.php';// Include your database connection
include '../../component/sidebar.php';
include '../../component/session_handler.php';

// Fetch courses from the database
$sql = "SELECT C_ID, Name FROM courses";
$result = mysqli_query($conn, $sql);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dname = $_POST['degree_name'];
    $fee = $_POST['fee'];
    $selected_courses = $_POST['courses']; // Array of selected course IDs
    
    // Convert course IDs to comma-separated string
    $C_ID = implode(',', $selected_courses);

    // Insert into degrees table (WITHOUT PREPARED STATEMENTS)
    $sql_insert = "INSERT INTO degrees (dname, fee, C_ID) VALUES ('$dname', '$fee', '$C_ID')";
    if (mysqli_query($conn, $sql_insert)) {
        // Set success message in session
        
        $_SESSION['message'] = "Degree added successfully.";

        // Redirect to the degrees list page
        header('Location:Add degree.php');
         // Create this page later
        exit();
    } else {
        // Handle error
        
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
        header('Location:Add degree.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Degree</title>
    <style>
        .form-container {
            width: 30%;
            margin: auto;
            margin-top: 100px;
        }
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
        .course-checkbox-container {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="form-container">
            <h2>Add Degree</h2>
            <form method="post" action="Add degree.php" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="degree_name" class="form-label">Degree Name</label>
                    <input type="text" class="form-control" id="degree_name" name="degree_name" required>
                </div>
                <div class="mb-3">
                    <label for="fee" class="form-label">Fee</label>
                    <input type="number" class="form-control" id="fee" name="fee" required>
                </div>

                <div class="mb-3">
                    <label>Select Courses (At least one required):</label><br>
                    <?php foreach ($courses as $course): ?>
                        <div class="course-checkbox-container">
                            <input type="checkbox" name="courses[]" value="<?php echo $course['C_ID']; ?>" id="course_<?php echo $course['C_ID']; ?>">
                            <label for="course_<?php echo $course['C_ID']; ?>"><?php echo $course['Name']; ?></label>
                        </div>
                    <?php endforeach; ?>
                    <p id="course-error" style="color: red; display: none;">Please select at least one course.</p>
                </div>

                <button type="submit" class="btn btn-primary">Add Degree</button>
            </form>
        </div>
        <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="message-container">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
        ?>
    </div>

    <script>
        function validateForm() {
            const checkboxes = document.querySelectorAll('input[name="courses[]"]:checked');
            if (checkboxes.length === 0) {
                document.getElementById('course-error').style.display = 'block';
                return false;
            } else {
                document.getElementById('course-error').style.display = 'none';
                return true;
            }
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
ob_end_flush(); 
?>