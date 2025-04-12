<?php
include '../common/session_handler.php';
include '../component/connection.php'; // Include connection here, outside the if block

if (isset($_POST['submit'])) {
    $course_name = $_POST["Name"];
    $credit_hours = (int)$_POST["hours"];

    $query = "INSERT INTO courses(Name,`Credit hours`)VALUES('$course_name','$credit_hours')";
    $execute = mysqli_query($conn, $query);

    if ($execute) {
        $message = "<div style='background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 10px; text-align: center; width: 40%; margin: 20px auto; background-color: #c2f0c2;'>Course added successfully!</div>"; // Green background
        
    } else {
        $message = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 10px; text-align: center; width: 40%; margin: 20px auto; background-color: #f5c6cb;'>Error: " . mysqli_error($conn) . "</div>"; // Red background
    }
}
?>
<html>
<head>
    <title>Add Course</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content-area {
            margin-left: 250px; /* Adjust this to your sidebar width */
            padding: 20px;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: flex-start; /* Align to the top */
        }

        .form-container { /* Renamed to form-container */
            max-width: 400px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-out;
            width: 100%; /* Ensure it takes full width of parent */
            margin-top: 0px; /* Add some top margin for spacing */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px 15px;
            transition: border-color 0.3s ease;
            width: 100%; /* Ensure it takes full width of parent */
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            width: 100%; /* Ensure it takes full width of parent */
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            animation: slideInDown 0.8s ease-out;
        }

        @keyframes slideInDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <?php include '../component/sidebar.php'; ?>
    <?php
    // Force the message to display (for testing)
    if (isset($message)) {
        echo $message;
    } else {
        echo "<p>Message is not set.</p>";
    }
    ?>

    <div class="content-area">
        <div class="form-container">
            <h2>Add New Course</h2>
            <form method="post">
                <div class="form-group">
                    <label for="course_name">Course Name:</label>
                    <input type="text" class="form-control" name="Name" id="course_name" required>
                </div>
                <div class="form-group">
                    <label for="credit_hours">Credit Hours:</label>
                    <input type="number" class="form-control" name="hours" id="credit_hours" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="submit">Add Course</button>
            </form>
        </div>
    </div>
</body>
</html>