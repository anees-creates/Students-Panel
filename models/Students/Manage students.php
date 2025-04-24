<?php

include '../../config/connection.php';
include '../../component/session_handler.php';
include '../../component/sidebar.php';

// Fetch all student records with their degree names
$sql_students = "SELECT s.*, d.dname AS degree_name
                 FROM students s
                 JOIN degrees d ON s.D_ID = d.D_ID";
$result_students = mysqli_query($conn, $sql_students);
$students = mysqli_fetch_all($result_students, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .dashboard-space {
            width: 250px; /* Adjust width as needed for your dashboard */
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            margin-left: auto;
            margin-right: 400px;
            width: 60%;
            max-width: 1200px;
            
        }

        .student-table {
            background-color: #fff;
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .student-table th, .student-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .student-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .student-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .student-table img {
            max-width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .actions {
            text-align: center;
        }

        .actions a {
            margin: 0 5px;
            text-decoration: none;
            color: #007bff; /* Default icon color */
        }

        .actions a:hover {
            color: #0056b3; /* Hover icon color */
        }

        .actions a.delete-btn {
            color: #dc3545; /* Style for delete icon */
        }

        .actions a.delete-btn:hover {
            color: #c82333;
        }

        .actions i {
            font-size: 1.1em; /* Adjust icon size */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content {
                margin-left: 20px;
                width: 95%;
            }
            .student-table th, .student-table td {
                padding: 6px;
                font-size: 0.9em;
            }
            .student-table img {
                max-width: 60px;
            }
            .actions a {
                margin: 0 3px;
            }
            .actions i {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    
    <div class="content">
        <h2>All Student Records</h2>
        <?php if (!empty($students)): ?>
            <table class="student-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Picture</th>
                        <th>Email</th>
                        <th>CNIC</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Degree</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['S_ID']); ?></td>
                            <td><?php echo htmlspecialchars($student['sname']); ?></td>
                            <td>
                                <?php if ($student['pic']): ?>
                                    <img src="<?php echo htmlspecialchars($student['pic']); ?>" alt="Student Picture">
                                <?php else: ?>
                                    No Picture
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['cnic']); ?></td>
                            <td><?php echo htmlspecialchars($student['gender']); ?></td>
                            <td><?php echo htmlspecialchars($student['DOB']); ?></td>
                            <td><?php echo htmlspecialchars($student['degree_name']); ?></td>
                            <td class="actions">
                                <a href="edit_student.php?s_id=<?php echo $student['S_ID']; ?>" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="#" class="delete-btn" onclick="confirmDelete('Delete_students.php?s_id=<?php echo $student['S_ID']; ?>')">                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No student records found.</p>
        <?php endif; ?>
    </div>
    <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
  <div style="background:pink; padding:20px; border-radius:10px; text-align:center; min-width:300px;">
    <p style="margin-bottom: 20px;">Are you sure you want to delete this record?</p>
    <button onclick="proceedDelete()" style="padding:8px 16px; margin-right:10px;">Yes</button>
    <button onclick="closeConfirm()" style="padding:8px 16px;">No</button>
  </div>
</div>
<script>
  let deleteUrl = '';

  function confirmDelete(url) {
    deleteUrl = url;
    document.getElementById('overlay').style.display = 'flex';
  }

  function proceedDelete() {
    window.location.href = deleteUrl;
  }

  function closeConfirm() {
    deleteUrl = '';
    document.getElementById('overlay').style.display = 'none';
  }
</script>
</body>
</html>