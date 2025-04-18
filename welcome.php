<?php
ob_start();
include 'component/sidebar.php';
session_start(); // Start the session at the beginning of the file

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php"); // Redirect to login if not logged in
    exit();
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LLMS Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
            color: #333;
        }
        .container {
            width: 80%; /* Reduce width to leave space */
            margin-left: 20%; /* Leave 20% blank space on the left */
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Ensure all sections align properly */
            height: 100vh; /* Use the full height of the viewport */
            box-sizing: border-box;
        }
        .marquee {
            background: #4caf50;
            color: white;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            overflow: hidden;
            white-space: nowrap;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .marquee span {
            display: inline-block;
            animation: scroll 10s linear infinite;
        }
        @keyframes scroll {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Two cards per row */
            gap: 20px;
            margin-top: 20px;
            flex-grow: 1; /* Allow the cards to adjust dynamically within the container */
        }
        .card {
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            padding: 10px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card h2 {
            margin: 10px 0;
            font-size: 1.6rem;
            color: #4caf50;
        }
        .card p {
            font-size: 0.9rem;
            color: #666;
        }
        .card i {
            font-size: 2rem; /* Icon size */
            color: #4caf50;
            margin-bottom: 8px;
        }
        footer {
            width: 100%; /* Align footer width with the layout */
            margin-left: auto;
            margin-right: auto; /* Center it horizontally */
            margin-top: 10px;
            padding: 10px 0;
            text-align: center;
            background: #282c34;
            color: white;
            font-size: 0.9rem;
            border-radius: 5px;
        }
    </style>
    <!-- Link to Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Marquee for Admissions Announcement -->
        <div class="marquee">
            <span>🔥 Admissions Open: Enroll Now in Our 200+ Courses! 🔥</span>
        </div>

        <!-- Cards Section -->
        <div class="cards">
            <div class="card">
                <i class="fas fa-user-graduate"></i>
                <h2>500+</h2>
                <p>Students</p>
            </div>
            <div class="card">
                <i class="fas fa-book"></i>
                <h2>200+</h2>
                <p>Courses</p>
            </div>
            <div class="card">
                <i class="fas fa-university"></i>
                <h2>50+</h2>
                <p>Degrees</p>
            </div>
            <div class="card">
                <i class="fas fa-laptop-code"></i>
                <h2>1000+</h2>
                <p>Learning Resources</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <p>&copy; 2025 LLMS. All rights reserved.</p>
            <p><em>Developed by Anees ur Rehman</em></p>
        </footer>
    </div>
</body>
</html>





<?php 
ob_end_flush(); 
?>