<?php
include '../component/connection.php';
include '../common/session_handler.php';
include '../component/sidebar.php';


// Check if degree ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $degree_id = (int)$_GET['id'];

    // Fetch degree details
    $sql_degree = "SELECT D_ID, dname, fee, C_ID FROM degrees WHERE D_ID = ?";
    $stmt_degree = mysqli_prepare($conn, $sql_degree);
    mysqli_stmt_bind_param($stmt_degree, "i", $degree_id);
    mysqli_stmt_execute($stmt_degree);
    $result_degree = mysqli_stmt_get_result($stmt_degree);

    if (mysqli_num_rows($result_degree) === 1) {
        $degree = mysqli_fetch_assoc($result_degree);
        $current_courses = explode(',', $degree['C_ID']); // Array of currently associated course IDs
    } else {
        // Degree not found, redirect to the list page
        header('Location: degrees_list.php');
        exit();
    }
    mysqli_stmt_close($stmt_degree);

    // Fetch all courses for checkboxes
    $sql_all_courses = "SELECT C_ID, Name FROM courses";
    $result_all_courses = mysqli_query($conn, $sql_all_courses);
    $all_courses = mysqli_fetch_all($result_all_courses, MYSQLI_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dname = $_POST['degree_name'];
        $fee = $_POST['fee'];
        $selected_courses = $_POST['courses'] ?? []; // Get selected courses, default to empty array

        $updated_c_ids = implode(',', $selected_courses);

        // Update the degree in the database
        $sql_update = "UPDATE degrees SET dname = ?, fee = ?, C_ID = ? WHERE D_ID = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "sdsi", $dname, $fee, $updated_c_ids, $degree_id);

        if (mysqli_stmt_execute($stmt_update)) {
            echo "<div class='message-container success' style='margin-left: 270px;'>";
            echo "<h2>Degree updated successfully!</h2>";
            echo "<a href='Manage degree.php' class='back-button'>Back to Degree List</a>";
            echo "</div>";
            exit();
        } else {
            echo "<div class='message-container error' style='margin-left: 100px;'>";
            echo "<h2>Error updating degree: " . mysqli_stmt_error($stmt_update) . "</h2>";
            echo "<a href='degrees_list.php' class='back-button error'>Back to Degree List</a>";
            echo "</div>";
            exit();
        }
        mysqli_stmt_close($stmt_update);
    }

} else {
    // No valid ID provided, redirect to the list page
    header('Location: degrees_list.php');
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Degree</title>
    <style>
        .form-container {
            width: 30%;
            margin: auto;
            margin-top: 100px;
        }
        .message-container {
            width: 80%;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .message-container.error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .message-container.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            margin-left:100p
        }
        .course-checkbox-container {
            margin-bottom: 10px;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }

        .back-button {
            background-color: #28a745; /* Success color */
            color: white;
        }

        .back-button.error {
            background-color: #dc3545; /* Error color */
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="form-container">
            <h2>Edit Degree</h2>
            <form method="post">
                <div class="mb-3">
                    <label for="degree_name" class="form-label">Degree Name</label>
                    <input type="text" class="form-control" id="degree_name" name="degree_name" value="<?php echo htmlspecialchars($degree['dname']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="fee" class="form-label">Fee</label>
                    <input type="number" class="form-control" id="fee" name="fee" value="<?php echo htmlspecialchars($degree['fee']); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Select Courses:</label><br>
                    <?php foreach ($all_courses as $course): ?>
                        <div class="course-checkbox-container">
                            <input type="checkbox" name="courses[]" value="<?php echo $course['C_ID']; ?>" id="course_<?php echo $course['C_ID']; ?>"
                                <?php if (in_array($course['C_ID'], $current_courses)): ?>checked<?php endif; ?>>
                            <label for="course_<?php echo $course['C_ID']; ?>"><?php echo htmlspecialchars($course['Name']); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn-primary">Update Degree</button>
                <a href="degrees_list.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>