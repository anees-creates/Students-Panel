<?php
ob_start();
include '../component/connection.php';
include '../common/session_handler.php';
include '../component/sidebar.php';

// Fetch degrees from the database for the dropdown
$sql_degrees = "SELECT D_ID, dname FROM degrees";
$result_degrees = mysqli_query($conn, $sql_degrees);
$degrees = mysqli_fetch_all($result_degrees, MYSQLI_ASSOC);

$errors = []; // Array to store validation errors

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $student_cnic = $_POST['student_cnic'];
    $student_gender = $_POST['student_gender'];
    $student_dob = $_POST['student_dob']; // Assuming <input type="date">
    $degree_id = $_POST['student_degree'];

    // --- Server-Side Validation ---
    if (empty($student_name)) {
        $errors['student_name'] = "Student Name is required.";
    }
    if (empty($student_email) || !filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
        $errors['student_email'] = "Invalid or missing Student Email.";
    }
    if (empty($student_cnic) || !preg_match('/^[0-9]{13}$/', $student_cnic)) {
        $errors['student_cnic'] = "CNIC must be 13 digits.";
    }
    if (empty($student_gender)) {
        $errors['student_gender'] = "Gender is required.";
    }
    if (empty($student_dob)) {
        $errors['student_dob'] = "Date of Birth is required.";
    }
    if (empty($degree_id)) {
        $errors['student_degree'] = "Please select a Degree.";
    }

    // Handle file upload (validation can be added here if needed)
    $student_picture_path = '';
    if (isset($_FILES['student_picture']) && $_FILES['student_picture']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['student_picture']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png');
        $upload_dir = '../img/';

        if (in_array($file_ext, $allowed_ext)) {
            $unique_name = 'student_' . time() . '_' . uniqid() . '.' . $file_ext;
            $destination = $upload_dir . $unique_name;
            if (move_uploaded_file($_FILES['student_picture']['tmp_name'], $destination)) {
                $student_picture_path = '../img/' . $unique_name;
            } else {
                $errors['student_picture'] = "Error uploading picture.";
                error_log("Error moving uploaded file to: " . $destination);
            }
        } else {
            $errors['student_picture'] = "Invalid picture format (jpg, jpeg, png allowed).";
        }
    }

    // If there are no validation errors, proceed with database insertion
    if (empty($errors)) {
        $sql_insert = "INSERT INTO students (sname, pic, email, cnic, gender, DOB, D_ID) VALUES ('$student_name', '$student_picture_path', '$student_email', '$student_cnic', '$student_gender', '$student_dob', '$degree_id')";

        if (mysqli_query($conn, $sql_insert)) {
            $_SESSION['message'] = "Student registered successfully!";
            $_SESSION['message_type'] = 'success';
            header("Location: students_list.php");
            exit();
        } else {
            $_SESSION['message'] = "Error registering student: " . mysqli_error($conn);
            $_SESSION['message_type'] = 'error';
            header("Location: add_student.php");
            exit();
        }
    } else {
        // Display validation errors
        $_SESSION['message'] = "Please correct the form errors.";
        $_SESSION['message_type'] = 'error';
        header("Location: add_student.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 700px;
            margin-top:100px;
            margin-right:320px;
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
        input[type="number"],
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

        .message-container {
            width: 80%;
            margin: 25px auto;
            padding: 18px;
            border-radius: 5px;
            text-align: center;
            font-size: 1em;
        }

        .message-container.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-container.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Student</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="message-container ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="student_name">Student Name</label>
                <input type="text" id="student_name" name="student_name" required>
                <?php if (isset($errors['student_name'])) echo '<div class="error">' . $errors['student_name'] . '</div>'; ?>
            </div>
            <div class="form-group">
                <label for="student_picture">Student Picture</label>
                <input type="file" id="student_picture" name="student_picture" accept="image/*">
                <small style="color: #777;">Allowed formats: jpg, jpeg, png</small>
                <?php if (isset($errors['student_picture'])) echo '<div class="error">' . $errors['student_picture'] . '</div>'; ?>
            </div>
            <div class="form-group">
                <label for="student_email">Student Email</label>
                <input type="email" id="student_email" name="student_email" required>
                <?php if (isset($errors['student_email'])) echo '<div class="error">' . $errors['student_email'] . '</div>'; ?>
            </div>
            <div class="form-group">
                <label for="student_cnic">Student CNIC (13 digits)</label>
                <input type="text" id="student_cnic" name="student_cnic" pattern="[0-9]{13}" title="Enter a 13-digit CNIC number" required>
                <?php if (isset($errors['student_cnic'])) echo '<div class="error">' . $errors['student_cnic'] . '</div>'; ?>
            </div>
            <div class="form-group">
                <label>Student Gender</label>
                <div class="gender-group">
                    <input type="radio" id="gender_male" name="student_gender" value="Male" required>
                    <label for="gender_male">Male</label>
                    <input type="radio" id="gender_female" name="student_gender" value="Female">
                    <label for="gender_female">Female</label>
                    <input type="radio" id="gender_others" name="student_gender" value="Others">
                    <label for="gender_others">Others</label>
                </div>
                <?php if (isset($errors['student_gender'])) echo '<div class="error">' . $errors['student_gender'] . '</div>'; ?>
            </div>
            <div class="form-group">
                <label for="student_dob">Student DOB</label>
                <input type="date" id="student_dob" name="student_dob" required>
                <?php if (isset($errors['student_dob'])) echo '<div class="error">' . $errors['student_dob'] . '</div>'; ?>
            </div>
            <div class="form-group">
                <label for="student_degree">Student Degree</label>
                <select id="student_degree" name="student_degree" required>
                    <option value="">Select Below</option>
                    <?php foreach ($degrees as $degree): ?>
                        <option value="<?php echo $degree['D_ID']; ?>"><?php echo htmlspecialchars($degree['dname']); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['student_degree'])) echo '<div class="error">' . $errors['student_degree'] . '</div>'; ?>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
ob_end_flush();
?>