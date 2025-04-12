<?php include '../common/session_handler.php'; 
  include '../component/connection.php';
  include '../component/sidebar.php';

  $sql = "SELECT C_ID, Name, `Credit hours` FROM courses"; // Corrected column names
  $result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .courses-container {
            margin-left: 25%;
            margin-top: 25px;
            margin-right: 20px;
        }

        .courses-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .courses-table th, .courses-table td {
            border: 1px solid #e0e0e0;
            padding: 12px 15px;
            text-align: left;
            white-space: nowrap;
        }

        .courses-table th {
            background-color: #1A237E; /* Dark blue header */
            color: white; /* White text on header */
            font-weight: 600;
        }

        .courses-table tr:nth-child(even) {
            background-color: #e6f7ff; /* Light powder blue */
        }

        .courses-table tr:nth-child(odd) {
            background-color: #FFFDD0; /* Cream color */
        }

        .action-icons {
            display: flex;
            justify-content: center;
        }

        .action-icons a {
            margin: 0 8px;
            color: #3498db;
        }

        .action-icons a:hover {
            color: #2980b9;
        }

        .courses-table th:nth-child(1),
        .courses-table td:nth-child(1) {
            width: 80px; /* SR Number column width */
        }

        .courses-table th:nth-child(3),
        .courses-table td:nth-child(3) {
            width: 120px; /* Credit Hours column width */
        }
    </style>
</head>
<body>

    <div class="courses-container">
        <h2>Courses List</h2>
        <div class="table-responsive">
            <table class="courses-table">
                <thead>
                    <tr>
                        <th>SR Number</th>
                        <th>Course Title (Name)</th>
                        <th>Credit Hours</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['C_ID'] . "</td>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row['Credit hours'] . "</td>";
                            echo "<td class='action-icons'>";
                            echo "<a href='updatecourses.php?id=" . $row['C_ID'] . "'><i class='bi bi-pencil-square'></i></a>";
                            echo "<a href='delete_course.php?id=" . $row['C_ID'] . "'><i class='bi bi-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No courses found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>