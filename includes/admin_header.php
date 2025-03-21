<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP NCR Station 1</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        #sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            color: white;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            transition: width 0.3s;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 50px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-links li {
            padding: 10px 0;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: block;
            padding: 8px 10px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background-color: #555;
            padding-left: 15px;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background: white;
            color: black;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            min-width: 150px;
            padding: 10px 0;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
        }

        .dropdown-menu a:hover {
            background-color: #f0f0f0;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s;
        }

        #sidebar .toggle-btn {
            position: absolute;
            top: 15px;
            right: -35px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            width: 35px;
            height: 35px;
            text-align: center;
            font-size: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .submenu {
            display: none;
            list-style: none;
            padding-left: 20px;
            background: #444;
            border-radius: 5px;
        }

        .submenu li a {
            color: white;
            padding: 5px 10px;
            display: block;
        }

        .submenu li a:hover {
            background: #555;
        }

        .dropdown:hover .submenu {
            display: block;
        }
    </style>
</head>
<body>

    <nav id="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>
        <div class="logo-container">
            <img src="user/images/BFP STATION 1 LOGO.png" alt="BFP Logo" class="logo">
            <div class="title">BFP NCR - STATION 1</div>
        </div>
        <div class="user-icons">
    <a href="#" title="Profile">&#128100;</a>
    <div class="dropdown">
        <a href="#" title="Settings" id="settings-icon">&#9881;</a>
        <div class="dropdown-menu" id="dropdown-menu">
            <a href="/RESCUEFLOW/personel/index.php">Add Personnel</a>
            <a href="/RESCUEFLOW/trainings/index.php">Add Training</a>
            <a href="/RESCUEFLOW/asset/index.php">Add Asset</a>
            <a href="/RESCUEFLOW/shift/index.php">Add Shifts</a>
            <a href="/RESCUEFLOW/incidents/index.php">Add Incidents</a>
            <a href="/RESCUEFLOW/dispatch/dispatchindex.php">Dispatch</a>
        </div>
    </div>
</div>
<script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var content = document.querySelector(".main-content");

            if (sidebar.style.width === "60px") {
                sidebar.style.width = "250px";
                content.style.marginLeft = "250px";
            } else {
                sidebar.style.width = "60px";
                content.style.marginLeft = "60px";
            }
        }
    </script>
        <ul class="nav-links">
        <li><a href="/RESCUEFLOW/personel/personel_index.php">👨‍🚒 Personnel</a></li>
        <li><a href="/RESCUEFLOW/incidents/reports.php">📄 Reports</a></li>
        <li class="dropdown">
            <a href="#">📦 Assets</a>
            <ul class="submenu">
                <li><a href="#">Fire Truck 1</a></li>
                <li><a href="#">Fire Truck 2</a></li>
                <li><a href="#">Fire Truck 3</a></li>
                <li><a href="#">Fire Truck 4</a></li>
                <li><a href="#">Emergency Vehicle</a></li>
                <li><a href="#">Stationary</a></li>
            </ul>
        </li>
        <li><a href="/RESCUEFLOW/attendance/attendance.php">📋 Attendance</a></li>
        <li><a href="/RESCUEFLOW/shift/shift_index.php">🕒 Shifts</a></li>
        <li><a href="/RESCUEFLOW/trainings/training_index.php">🎓 Training</a></li>
        <li><a href="/RESCUEFLOW/analysis/analysisindex.php">📊 Analysis</a></li>
        <li><a href="/RESCUEFLOW/dispatch/dispatchindex.php">🚨 DISPATCH</a></li>
        <li><a href="/RESCUEFLOW/notif/notif.php">🔔 Notif</a></li>
        </ul>
    </nav>
</body>
</html>
