<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel with Sub-Menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-bar {
            background-color: #f8f9fa;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dee2e6;
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            z-index: 100;
            
        }

        .top-bar h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .top-bar .profile-icon {
            font-size: 1.5rem;
        }

        .sidebar {
            background: linear-gradient(135deg, #1A237E, #283593);
            color: white;
            height: 100vh;
            width: 250px;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar .menu-heading {
            padding: 15px 20px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 10px;
        }

        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            transition: background-color 0.3s ease;
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #64B5F6;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .sub-menu {
            display: none;
            background-color: rgba(0, 0, 0, 0.1);
            padding-left: 20px;
        }

        .sidebar .nav-item:hover .sub-menu {
            display: block;
        }

        .content-area {
            margin-left: 250px;
            padding: 20px;
            margin-top: 60px;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <h1>Student Panel</h1>
        <i class="bi bi-person-circle profile-icon"></i>
    </div>

    <div class="container-fluid">
        <div class="row">
            <nav class="sidebar">
                <div class="position-sticky">
                    <div class="menu-heading">Dashboard</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/Login Project/welcome.php">
                                <i class="bi bi-house"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="welcome.php">
                                <i class="bi bi-book"></i> Courses
                            </a>
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link " href="/Login Project/Pages/Add Courses.php">Add Courses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"  href="/Login Project/Pages/Manage courses.php">Manage Courses</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-mortarboard"></i> Degree
                            </a>
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="/Login Project/Pages/Add degree.php">Add Degree</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="/Login Project/Pages/Manage degree.php">Manage Degrees</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-person"></i> Student
                            </a>
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link " href="/Login Project/Pages/addstudent.php">Add Student</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/Login Project/Pages/Manage students.php">Manage Student</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Login Project/Pages/record.php">
                                <i class="bi bi-file-earmark-text"></i> Record
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Login Project/logout.php">
                                <i class="bi bi-box-arrow-left"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col content-area">
                </main>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>