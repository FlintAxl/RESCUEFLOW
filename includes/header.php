<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP NCR Station 1</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your stylesheet path -->
    <style>
        /* General Reset */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1E1E1E; /* Dark Sidebar */
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: width 0.3s ease;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background: #007BFF;
        }

        .sidebar .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .logo {
            width: 80px;
            border-radius: 50%;
        }

        .sidebar .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        .sidebar .toggle-btn {
            position: absolute;
            top: 15px;
            right: -30px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            width: 30px;
            height: 30px;
            text-align: center;
            font-size: 18px;
            border-radius: 5px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            width: 100%;
        }

        /* User Icons */
        .user-icons {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
        }

        .user-icons a {
            text-decoration: none;
            font-size: 20px;
            color: #fff;
        }

        .user-icons a:hover {
            color: #007BFF;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            .sidebar a {
                text-align: center;
                font-size: 14px;
                padding: 10px 5px;
            }
            .sidebar .title {
                display: none;
            }
            .main-content {
                margin-left: 60px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>
        <div class="logo-container">
            <img src="user/images/BFP STATION 1 LOGO.png" alt="BFP Logo" class="logo">
            <div class="title">BFP NCR - STATION 1</div>
        </div>
        <a href="/RESCUEFLOW/personel/personel_index.php">👨‍🚒 Personnel</a>
        <a href="/RESCUEFLOW/incidents/reports.php">📄 Reports</a>
        <a href="/RESCUEFLOW/asset/item_index.php">📦 Assets</a>
        <a href="/RESCUEFLOW/attendance/attendance.php">📋 Attendance</a>
        <a href="/RESCUEFLOW/shift/shift_index.php">🕒 Shifts</a>
        <a href="/RESCUEFLOW/trainings/training_index.php">🎓 Training</a>
        <a href="/RESCUEFLOW/analysis/analysisindex.php">📊 Analysis</a>
        
        <div class="user-icons">
            <a href="" title="Profile">&#128100;</a>
        </div>
    </div>

    <!-- Main Content -->
    

    <!-- JavaScript for Sidebar Toggle -->
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

</body>
</html>
