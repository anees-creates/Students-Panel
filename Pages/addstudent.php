<?php
include '../component/connection.php';
include '../common/session_handler.php';
include '../component/sidebar.php';

// Fetch degrees from the database for the dropdown
$sql_degrees = "SELECT D_ID, dname FROM degrees";
$result_degrees = mysqli_query($conn, $sql_degrees);
$degrees = mysqli_fetch_all($result_degrees, MYSQLI_ASSOC);

// Handle form submission (only if JavaScript validation passes)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_validated']) && $_POST['form_validated'] == 'true') {
    $sname = $_POST['student_name'];
    $email = $_POST['student_email'];
    $cnic = $_POST['student_cnic'];
    $gender = $_POST['student_gender'];
    $dob_raw = $_POST['student_dob'];
    $degree_id = $_POST['student_degree'];

    // Process date format (convert mm/dd/yyyy to yyyy-mm-dd for database)
    $dob_parts = explode('/', $dob_raw);
    if (count($dob_parts) === 3 && checkdate($dob_parts[0], $dob_parts[1], $dob_parts[2])) {
        $dob = $dob_parts[2] . '-' . $dob_parts[0] . '-' . $dob_parts[1];
    } else {
        $dob = null; // Or handle invalid date error
    }

    // Handle file upload
    // Handle file upload
    $picture_path = '';
    if (isset($_FILES['student_picture']) && $_FILES['student_picture']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['student_picture']['name'];
        $file_tmp = $_FILES['student_picture']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png');
        $upload_dir = '../img/';

        if (in_array($file_ext, $allowed_ext)) {
            $unique_name = 'student_' . time() . '_' . uniqid() . '.' . $file_ext;
            $destination = $upload_dir . $unique_name;

            if (move_uploaded_file($file_tmp, $destination)) {
                $picture_path = '../img/' . $unique_name;
            } else {
                // Error moving file
                $picture_path = ''; // Or handle the error as needed
                error_log("Error moving uploaded file to: " . $destination);
            }
        } else {
            // Invalid file type
            $picture_path = ''; // Or handle the error
        }
    }

    // Insert student data into the database
    $sql_insert = "INSERT INTO students (sname, pic, email, cnic, gender, dob, D_ID) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssssssi", $sname, $picture_path, $email, $cnic, $gender, $dob, $degree_id);

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['message'] = "Student registered successfully!";
        $_SESSION['message_type'] = 'success';
        header("Location: students_list.php"); // You'll create this page later
        exit();
    } else {
        $_SESSION['message'] = "Error registering student: " . mysqli_error($conn);
        $_SESSION['message_type'] = 'error';
        header("Location: add_student.php");
        exit();
    }
    mysqli_stmt_close($stmt_insert);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <style>
        .form-container {
            width: 50%; /* Adjust width as needed */
            margin: 20px auto; /* Adjusted top margin */
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top:0px;
            margin-left:440px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        input[type="file"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .gender-group label {
            display: inline-block;
            margin-right: 10px;
            font-weight: normal;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message-container {
            width: 50%;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
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
        .error-message {
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
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="form_validated" id="form_validated" value="false">
            <div class="form-group">
                <label for="student_name">Student Name</label>
                <input type="text" id="student_name" name="student_name" required>
                <div class="error-message" id="name_error"></div>
            </div>
            <div class="form-group">
                <label for="student_picture">Student Picture</label>
                <input type="file" id="student_picture" name="student_picture" accept="image/*">
                <small>Allowed formats: jpg, jpeg, png</small>
                <div class="error-message" id="picture_error"></div>
            </div>
            <div class="form-group">
                <label for="student_email">Student Email</label>
                <input type="email" id="student_email" name="student_email" required>
                <div class="error-message" id="email_error"></div>
            </div>
            <div class="form-group">
                <label for="student_cnic">Student CNIC (13 digits)</label>
                <input type="text" id="student_cnic" name="student_cnic" pattern="[0-9]{13}" title="Enter a 13-digit CNIC number" required>
                <div class="error-message" id="cnic_error"></div>
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
                    <div class="error-message" id="gender_error"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="student_dob">Student DOB (mm/dd/yyyy)</label>
                <input type="text" id="student_dob" name="student_dob" placeholder="mm/dd/yyyy" pattern="(0[1-9]|1[0-2])/(0[1-9]|[12][0-9]|3[01])/\d{4}" title="Enter date in mm/dd/yyyy format" required>
                <div class="error-message" id="dob_error"></div>
            </div>
            <div class="form-group">
                <label for="student_degree">Student Degree</label>
                <select id="student_degree" name="student_degree" required>
                    <option value="">Select Below</option>
                    <?php foreach ($degrees as $degree): ?>
                        <option value="<?php echo $degree['D_ID']; ?>"><?php echo htmlspecialchars($degree['dname']); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="error-message" id="degree_error"></div>
            </div>
            <button type="submit" onclick="document.getElementById('form_validated').value = 'true';">Register</button>
        </form>
    </div>

    <script>
        function validateForm() {
            let isValid = true;

            // Reset error messages
            document.getElementById("name_error").innerText = "";
            document.getElementById("email_error").innerText = "";
            document.getElementById("cnic_error").innerText = "";
            document.getElementById("gender_error").innerText = "";
            document.getElementById("dob_error").innerText = "";
            document.getElementById("degree_error").innerText = "";

            // Check Student Name
            const nameInput = document.getElementById("student_name");
            if (nameInput.value.trim() === "") {
                document.getElementById("name_error").innerText = "Student Name is required.";
                isValid = false;
            }

            // Check Student Email
            const emailInput = document.getElementById("student_email");
            if (emailInput.value.trim() === "") {
                document.getElementById("email_error").innerText = "Student Email is required.";
                isValid = false;
            } else {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailInput.value)) {
                    document.getElementById("email_error").innerText = "Invalid email format.";
                    isValid = false;
                }
            }

            // Check Student CNIC
            const cnicInput = document.getElementById("student_cnic");
            if (cnicInput.value.trim() === "") {
                document.getElementById("cnic_error").innerText = "Student CNIC is required.";
                isValid = false;
            } else if (!/^[0-9]{13}$/.test(cnicInput.value)) {
                document.getElementById("cnic_error").innerText = "CNIC must be 13 digits.";
                isValid = false;
            }

            // Check Student Gender
            const genderRadios = document.querySelectorAll('input[name="student_gender"]:checked');
            if (genderRadios.length === 0) {
                document.getElementById("gender_error").innerText = "Please select a gender.";
                isValid = false;
            }

            // Check Student DOB
            const dobInput = document.getElementById("student_dob");
            if (dobInput.value.trim() === "") {
                document.getElementById("dob_error").innerText = "Date of Birth is required.";
                isValid = false;
            } else if (!/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/.test(dobInput.value)) {
                document.getElementById("dob_error").innerText = "Invalid date format (mm/dd/yyyy).";
                isValid = false;
            }

            // Check Student Degree
            const degreeSelect = document.getElementById("student_degree");
            if (degreeSelect.value === "") {
                document.getElementById("degree_error").innerText = "Please select a degree.";
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>