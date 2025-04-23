<?php include '../common/session_handler.php';
   include '../component/sidebar.php';
   include '../component/connection.php'; 
   $sql = "SELECT D_ID, dname, fee, C_ID FROM degrees";
   $result = mysqli_query($conn, $sql);
   $degrees = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
   // Function to fetch course name by ID
   function getCourseNames($conn, $course_ids_string) {
       $course_names = [];
       $course_ids = explode(',', $course_ids_string);
       if (!empty($course_ids) && $course_ids[0] !== '') {
           $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
           $sql = "SELECT Name FROM courses WHERE C_ID IN ($placeholders)";
           $stmt = mysqli_prepare($conn, $sql);
           mysqli_stmt_bind_param($stmt, str_repeat('i', count($course_ids)), ...$course_ids);
           mysqli_stmt_execute($stmt);
           $result = mysqli_stmt_get_result($stmt);
           while ($row = mysqli_fetch_assoc($result)) {
               $course_names[] = $row['Name'];
           }
           mysqli_stmt_close($stmt);
       }
       return implode(', ', $course_names);
   }
   ?>
   
   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Degree List</title>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
       <style>
           .container {
               display: flex;
               justify-content: flex-start;
               align-items: center;
               min-height: 100vh;
               background-color: #f4f4f4;
               padding-left: 300px;
               box-sizing: border-box;
               margin-top:-70px;
               margin-left:200px;
           }
           .table-container {
               background-color: white;
               padding: 10px;
               border-radius: 8px;
               box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
               width: 80%;
               overflow-x: auto;
               margin-top:0px;
           }
           table {
               width: 100%;
               border-collapse: collapse;
               margin-top: 0px;
           }
           th, td {
               border: 1px solid #ddd;
               padding: 10px;
               text-align: left;
           }
           th {
               background-color: #f2f2f2;
               font-weight: bold;
           }
           tr:nth-child(even) {
               background-color: #f9f9f9;
           }
           .action-icons a {
               display: inline-block;
               margin-right: 10px;
               text-decoration: none;
               font-size: 1.2em; /* Adjust icon size */
           }
           .action-icons a.edit {
               color: #007bff;
           }
           .action-icons a.delete {
               color: #dc3545;
           }
           .add-button {
               display: inline-block;
               padding: 10px 20px;
               text-decoration: none;
               background-color: #28a745;
               color: white;
               border-radius: 5px;
               margin-bottom: 20px;
           }
           .message-container {
               width: 80%;
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
       </style>
   </head>
   <body>
       <div class="container">
           <div class="table-container">
               
               <a href="Add degree.php" class="add-button">Add New Degree</a>
   
               <?php
               if (isset($_SESSION['message'])) {
                   echo '<div class="message-container ' . ($_SESSION['message_type'] ?? '') . '">' . $_SESSION['message'] . '</div>';
                   unset($_SESSION['message']);
                   unset($_SESSION['message_type']);
               }
               ?>
   
               <table>
                   <thead>
                       <tr>
                           <th>Sr. No.</th>
                           <th>Degree</th>
                           <th>Fee</th>
                           <th>Courses</th>
                           <th>Actions</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php if (empty($degrees)): ?>
                           <tr><td colspan="5">No degrees found.</td></tr>
                       <?php else: ?>
                           <?php $serialNumber = 1; ?>
                           <?php foreach ($degrees as $degree): ?>
                               <tr>
                                   <td><?php echo $serialNumber++; ?></td>
                                   <td><?php echo htmlspecialchars($degree['dname']); ?></td>
                                   <td><?php echo htmlspecialchars($degree['fee']); ?></td>
                                   <td><?php echo getCourseNames($conn, $degree['C_ID']); ?></td>
                                   <td class="action-icons">
                                       <a href="edit_degree.php?id=<?php echo $degree['D_ID']; ?>" class="edit"><i class="fas fa-edit"></i></a>
                                       <a href="#" class="delete" onclick="confirmDelete('delete_degree.php?id=<?php echo $degree['D_ID']; ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                           </a>
                                  </td>
                               </tr>
                           <?php endforeach; ?>
                       <?php endif; ?>
                   </tbody>
               </table>
           </div>
       </div>




       <!-- Custom Confirm Modal -->
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
   
   <?php
   mysqli_close($conn);
   ?>