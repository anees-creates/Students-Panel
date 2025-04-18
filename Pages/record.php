<?php
include '../component/connection.php';
include '../common/session_handler.php';

// Fetch degrees for the dropdown
$sql_degrees = "SELECT DISTINCT dname, D_ID FROM degrees";
$result_degrees = mysqli_query($conn, $sql_degrees);
$degrees = mysqli_fetch_all($result_degrees, MYSQLI_ASSOC);

// Fetch courses for the dropdown
$sql_courses = "SELECT DISTINCT Name, C_ID FROM courses";
$result_courses = mysqli_query($conn, $sql_courses);
$courses = mysqli_fetch_all($result_courses, MYSQLI_ASSOC);

// Initialize report data array
$report_data = [];

// Handle form submission
if (isset($_POST['submit_report'])) {
    $selected_degree_id = mysqli_real_escape_string($conn, $_POST['degree_id']);
    $selected_course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // Construct the SQL query to fetch matching students
    // **You'll need to adjust this query based on your actual database schema
    // and how students, degrees, and courses are related.**
    $sql_report = "SELECT
    s.S_ID,
    s.sname,
    d.dname AS degree_name,
    c.Name AS course_name,
    d.fee
FROM
    students s
JOIN
    degrees d ON s.D_ID = d.D_ID
JOIN
    courses c ON FIND_IN_SET(c.C_ID, d.C_ID)  -- Link courses based on the degree's C_ID list
WHERE
    s.D_ID = ? AND
    c.C_ID = ?;";

    $stmt_report = mysqli_prepare($conn, $sql_report);
    mysqli_stmt_bind_param($stmt_report, "ii", $selected_degree_id, $selected_course_id);
    mysqli_stmt_execute($stmt_report);
    $result_report = mysqli_stmt_get_result($stmt_report);
    $report_data = mysqli_fetch_all($result_report, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt_report);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report</title>
    <style>
        /* Basic CSS for the form and table */
        body { font-family: sans-serif; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        form { margin-bottom: 20px; text-align: center; }
        select { padding: 8px; margin: 0 10px; }
        button { padding: 10px 20px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Student Report</h2>

    <form method="post">
        <label for="degree_id">Select Degree:</label>
        <select name="degree_id" id="degree_id">
            <option value="">-- Select Degree --</option>
            <?php foreach ($degrees as $degree): ?>
                <option value="<?php echo htmlspecialchars($degree['D_ID']); ?>">
                    <?php echo htmlspecialchars($degree['dname']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="course_id">Select Course:</label>
        <select name="course_id" id="course_id">
            <option value="">-- Select Course --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo htmlspecialchars($course['C_ID']); ?>">
                    <?php echo htmlspecialchars($course['Name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="submit_report">Generate Report</button>
    </form>

    <?php if (!empty($report_data)): ?>
        <h3>Matching Students:</h3>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Degree</th>
                    <th>Course</th>
                    <th>Fee</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report_data as $student_report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student_report['S_ID']); ?></td>
                        <td><?php echo htmlspecialchars($student_report['sname']); ?></td>
                        <td><?php echo htmlspecialchars($student_report['degree_name']); ?></td>
                        <td><?php echo htmlspecialchars($student_report['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($student_report['fee']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($_POST['submit_report'])): ?>
        <p>No students found matching the selected degree and course.</p>
    <?php endif; ?>

</body>
</html>