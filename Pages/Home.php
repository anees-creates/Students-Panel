<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f6f8;
            color: #344050;
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            padding: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .data-card {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .data-card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2.5em;
            color: #007bff;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #344050;
        }

        .card-value {
            font-size: 2em;
            color: #28a745;
            font-weight: bold;
        }

        .footer {
            background-color: #e9ecef;
            color: #6c757d;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            border-top: 1px solid #dee2e6;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
                gap: 20px;
            }
            .data-card {
                padding: 20px;
            }
            .card-icon {
                font-size: 2em;
                margin-bottom: 10px;
            }
            .card-title {
                font-size: 1.2em;
                margin-bottom: 8px;
            }
            .card-value {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <h1>Welcome to Your Learning Management System</h1>
        </header>

        <main class="main-content">
            <div class="data-card">
                <i class="fas fa-users card-icon"></i>
                <h2 class="card-title">Total Students</h2>
                <p class="card-value">500+</p>
            </div>

            <div class="data-card">
                <i class="fas fa-graduation-cap card-icon"></i>
                <h2 class="card-title">Available Degrees</h2>
                <p class="card-value">50+</p>
            </div>

            <div class="data-card">
                <i class="fas fa-user-plus card-icon"></i>
                <h2 class="card-title">New Registrations</h2>
                <p class="card-value">200+</p>
            </div>

            <div class="data-card">
                <i class="fas fa-book-open card-icon"></i>
                <h2 class="card-title">Total Courses</h2>
                <p class="card-value">150+</p>
            </div>

            <div class="data-card">
                <i class="fas fa-chalkboard-teacher card-icon"></i>
                <h2 class="card-title">Active Instructors</h2>
                <p class="card-value">30+</p>
            </div>

            <div class="data-card">
                <i class="fas fa-file-alt card-icon"></i>
                <h2 class="card-title">Uploaded Resources</h2>
                <p class="card-value">1000+</p>
            </div>
        </main>

        <footer class="footer">
            &copy; <?php echo date("Y"); ?> Developed by Anees ur Rehaman 
        </footer>
    </div>
</body>
</html>