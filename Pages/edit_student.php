<?php
ob_start();
include '../component/connection.php';
include '../common/session_handler.php';
include '../component/sidebar.php';

// Fetch degrees for the dropdown
$sql_degrees = "SELECT D_ID, dname FROM degrees";
$result_degrees = mysqli_query($conn, $sql_degrees);
$degrees = mysqli_fetch_all($result_degrees, MYSQLI_ASSOC);

$student = null;
$errors = [];

// Fetch student data if ID is provided
if (isset($_GET['s_id']) && is_numeric($_GET['s_id'])) {
    $student_id = $_GET['s_id'];
    $sql_student = "SELECT * FROM students WHERE S_ID = ?";
    $stmt_student = mysqli_prepare($conn, $sql_student);
    mysqli_stmt_bind_param($stmt_student, "i", $student_id);
    mysqli_stmt_execute($stmt_student);
    $result_student = mysqli_stmt_get_result($stmt_student);
    $student = mysqli_fetch_assoc($result_student);
    mysqli_stmt_close($stmt_student);

    if (!$student) {
        $_SESSION['message'] = "Student not found.";
        $_SESSION['message_type'] = 'error';
        header("Location: read_student.php"); // Redirect to student list
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid student ID.";
    $_SESSION['message_type'] = 'error';
    header("Location: read_student.php"); // Redirect to student list
    exit();
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['s_id'];
    $sname = $_POST['student_name'];
    $email = $_POST['student_email'];
    $cnic = $_POST['student_cnic'];
    $gender = $_POST['student_gender'];
    $dob = $_POST['student_dob'];
    $d_id = $_POST['student_degree'];
    $existing_pic = $_POST['existing_pic'];
    $pic = $existing_pic; // Default to existing picture

    // --- form Validation ---
    if (empty($sname)) {
        $errors['student_name'] = "Student Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['student_email'] = "Invalid or missing Student Email.";
    }
    if (empty($cnic) || !preg_match('/^[0-9]{13}$/', $cnic)) {
        $errors['student_cnic'] = "CNIC must be 13 digits.";
    }
    if (empty($gender)) {
        $errors['student_gender'] = "Gender is required.";
    }
    if (empty($dob)) {
        $errors['student_dob'] = "Date of Birth is required.";
    }
    if (empty($d_id)) {
        $errors['student_degree'] = "Please select a Degree.";
    }

    // Handle file upload
    if (isset($_FILES['student_picture']) && $_FILES['student_picture']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['student_picture']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png');
        $upload_dir = '../img/';

        if (in_array($file_ext, $allowed_ext)) {
            $unique_name = 'student_' . time() . '_' . uniqid() . '.' . $file_ext;
            $destination = $upload_dir . $unique_name;
            if (move_uploaded_file($_FILES['student_picture']['tmp_name'], $destination)) {
                // Delete the old picture if a new one is uploaded
                if (!empty($existing_pic) && file_exists($existing_pic)) {
                    unlink($existing_pic);
                }
                $pic = '../img/' . $unique_name;
            } else {
                $errors['student_picture'] = "Error uploading picture.";
                error_log("Error moving uploaded file to: " . $destination);
            }
        } else {
            $errors['student_picture'] = "Invalid picture format (jpg, jpeg, png allowed).";
        }
    }

    if (empty($errors)) {
        $sql_update = "UPDATE students SET sname=?, pic=?, email=?, cnic=?, gender=?, DOB=?, D_ID=? WHERE S_ID=?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "sssssssi", $sname, $pic, $email, $cnic, $gender, $dob, $d_id, $student_id);

        if (mysqli_stmt_execute($stmt_update)) {
            $_SESSION['message'] = "Student details updated successfully!";
            $_SESSION['message_type'] = 'success';
            header("Location:Manage students.php");
            exit();
        } else {
            $_SESSION['message'] = "Error updating student details: " . mysqli_error($conn);
            $_SESSION['message_type'] = 'error';
            header("Location: edit_student.php?s_id=" . $student_id);
            exit();
        }
        mysqli_stmt_close($stmt_update);
    } else {
        $_SESSION['message'] = "Please correct the form errors.";
        $_SESSION['message_type'] = 'error';
        header("Location: edit_student.php?s_id=" . $student_id);
        exit();
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .dashboard-space {
            width: 250px; /* Adjust width as needed */
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 50px; /* Adjust for sidebar */
            width:100%;
            margin-top:50px;
            margin-right:200px;
            
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            margin: 20px auto;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="file"],
        select {
            width: calc(100% - 16px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }

        .gender-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }

        .gender-group label {
            font-weight: normal;
            margin-bottom: 0;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }

        .current-picture {
            margin-bottom: 15px;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .current-picture img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    
    <div class="content">
        <div class="form-container">
            <h2>Edit Student</h2>
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="message-container ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="s_id" value="<?php echo htmlspecialchars($student['S_ID']); ?>">
                <input type="hidden" name="existing_pic" value="<?php echo htmlspecialchars($student['pic']); ?>">

                <?php if ($student['pic']): ?>
                    <div class="current-picture">
                        <label>Current Picture:</label><br>
                        <img src="<?php echo htmlspecialchars($student['pic']); ?>" alt="Current Student Picture">
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="student_picture">Update Student Picture</label>
                    <input type="file" id="student_picture" name="student_picture" accept="image/*">
                    <small style="color: #777;">Allowed formats: jpg, jpeg, png. Leave blank to keep current picture.</small>
                    <?php if (isset($errors['student_picture'])) echo '<div class="error">' . $errors['student_picture'] . '</div>'; ?>
                </div>

                <div class="form-group">
                    <label for="student_name">Student Name</label>
                    <input type="text" id="student_name" name="student_name" value="<?php echo htmlspecialchars($student['sname']); ?>" required>
                    <?php if (isset($errors['student_name'])) echo '<div class="error">' . $errors['student_name'] . '</div>'; ?>
                </div>

                <div class="form-group">
                    <label for="student_email">Student Email</label>
                    <input type="email" id="student_email" name="student_email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    <?php if (isset($errors['student_email'])) echo '<div class="error">' . $errors['student_email'] . '</div>'; ?>
                </div>

                <div class="form-group">
                    <label for="student_cnic">Student CNIC (13 digits)</label>
                    <input type="text" id="student_cnic" name="student_cnic" value="<?php echo htmlspecialchars($student['cnic']); ?>" pattern="[0-9]{13}" title="Enter a 13-digit CNIC number" required>
                    <?php if (isset($errors['student_cnic'])) echo '<div class="error">' . $errors['student_cnic'] . '</div>'; ?>
                </div>

                <div class="form-group">
                    <label>Student Gender</label>
                    <div class="gender-group">
                        <input type="radio" id="gender_male" name="student_gender" value="Male" <?php if ($student['gender'] === 'Male') echo 'checked'; ?> required>
                        <label for="gender_male">Male</label>
                        <input type="radio" id="gender_female" name="student_gender" value="Female" <?php if ($student['gender'] === 'Female') echo 'checked'; ?>>
                        <label for="gender_female">Female</label>
                        <input type="radio" id="gender_others" name="student_gender" value="Others" <?php if ($student['gender'] === 'Others') echo 'checked'; ?>>
                        <label for="gender_others">Others</label>
                    </div>
                    <?php if (isset($errors['student_gender'])) echo '<div class="error">' . $errors['student_gender'] . '</div>'; ?>
                </div>

                <div class="form-group">
                    <label for="student_dob">Student DOB</label>
                    <input type="date" id="student_dob" name="student_dob" value="<?php echo htmlspecialchars($student['DOB']); ?>" required>
                    <?php if (isset($errors['student_dob'])) echo '<div class="error">' . $errors['student_dob'] . '</div>'; ?>
                </div>

                <div class="form-group">
                    <label for="student_degree">Student Degree</label>
                    <select id="student_degree" name="student_degree" required>
                        <option value="">Select Below</option>
                        <?php foreach ($degrees as $degree): ?>
                            <option value="<?php echo $degree['D_ID']; ?>" <?php if ($student['D_ID'] == $degree['D_ID']) echo 'selected'; ?>><?php echo htmlspecialchars($degree['dname']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['student_degree'])) echo '<div class="error">' . $errors['student_degree'] . '</div>'; ?>
                </div>

                <button type="submit">Update Student</button>
            </form>
            <p><a href="Manage students.php">Back to Student Records</a></p>
        </div>
    </div>
    <?php 
    ob_end_flush(); 
    ?>
</body>
</html>

