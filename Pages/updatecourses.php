<?php
include '../component/connection.php';
 // Include your database connection
 include '../common/session_handler.php';
 include '../component/sidebar.php';

 if (isset($_GET['id'])) {
  $course_id = $_GET['id'];

  // Fetch the course data
  $sql = "SELECT C_ID, Name, `Credit hours` FROM courses WHERE C_ID = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $course_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $course = mysqli_fetch_assoc($result);

  if (!$course) {
      echo "Course not found.";
      exit();
  }
} else {
  echo "Course ID not provided.";
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $credit_hours = $_POST['credit_hours'];

  $sql = "UPDATE courses SET Name = ?, `Credit hours` = ? WHERE C_ID = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sii", $name, $credit_hours, $course_id);
  mysqli_stmt_execute($stmt);

  // Set success message in session
  
  $_SESSION['message'] = "Course updated successfully.";

  // Redirect back to the courses list
  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Course</title>
  <style>
    .form-container {
        width: 30%;
        margin: auto;
        margin-top: 100px;
    }
    .message-container {
        width: 30%;
        position: fixed;
        top: 80px; /* Further adjusted top value */
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
</style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="form-container">
          <h2>Update Course</h2>
          <form method="post">
              <div class="mb-3">
                  <label for="name" class="form-label">Course Name</label>
                  <input type="text" class="form-control" id="name" name="name" value="<?php echo $course['Name']; ?>" required>
              </div>
              <div class="mb-3">
                  <label for="credit_hours" class="form-label">Credit Hours</label>
                  <input type="number" class="form-control" id="credit_hours" name="credit_hours" value="<?php echo $course['Credit hours']; ?>" required>
              </div>
              <button type="submit" class="btn btn-primary">Update Course</button>
          </form>
      </div>
      <?php
          if (isset($_SESSION['message'])) {
              echo '<div class="message-container">' . $_SESSION['message'] . '</div>';
              unset($_SESSION['message']);
          }
      ?>
  </div>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>