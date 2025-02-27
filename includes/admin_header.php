

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP NCR Station 1</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your stylesheet path -->
    <style>
        /* General Styling */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #fff; /* White background */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 50px; /* Adjust as needed */
            margin-right: 10px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #000; /* Black text color */
        }

        nav {
            flex-grow: 1;
            margin-left: 20px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: #000; /* Black text */
            font-weight: bold;
        }

        .nav-links a:hover {
            color: #007BFF; /* Highlight color */
        }

        .user-icons {
            display: flex;
            gap: 15px;
        }

        .user-icons a {
            text-decoration: none;
            color: #000;
            font-size: 20px; /* Icon size */
        }

        .user-icons a:hover {
            color: #007BFF;
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="user/images/BFP STATION 1 LOGO.png" alt="BFP Logo" class="logo">
        <div class="title">BFP NCR - STATION 1</div>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="/RESCUEFLOW/personel/personel_index.php">PERSONNELS</a></li>
            <li><a href="/RESCUEFLOW/incidents/reports.php">REPORTS</a></li>
            <li><a href="/RESCUEFLOW/asset/item_index.php">ASSETS</a></li>
            <li><a href="">ATTENDANCE</a></li>
            <li><a href="/RESCUEFLOW/shift/shift_index.php">SHIFTS</a></li>
            <li><a href="/RESCUEFLOW/trainings/training_index.php">TRAINING SCHEDULE</a></li>
            <li><a href="/RESCUEFLOW/dispatch/dispatchindex.php">Dipatch</a></li>
            <li><a href="/RESCUEFLOW/analysis/analysisindex.php">ANALYSIS</a></li>
        </ul>
    </nav>


    <div class="user-icons">
        
        <a href="" title="Profile">&#128100;</a> <!-- Unicode icon for user -->
        <div class="dropdown">
            <a href="#" title="Settings" class="icon dropdown-toggle">&#9881;</a> <!-- Settings icon -->
            <div class="dropdown-menu">
                <a href="/RESCUEFLOW/personel/index.php">add personel</a>
                <a href="/RESCUEFLOW/trainings/index.php">add training</a>
                <a href="/RESCUEFLOW/asset/index.php">add asset</a>
                <a href="/RESCUEFLOW/shift/index.php">add shifts</a>
                <a href="/RESCUEFLOW/incidents/index.php">add incidents</a>
                <a href="/RESCUEFLOW/dispatch/dispatchindex.php">Dipatch</a>
            </div>
        </div>
    </div>
</header>
<style>
    .user-icons {
        display: flex;
        gap: 10px;
        align-items: center;
        position: relative;
    }

    .user-icons a {
        text-decoration: none;
        font-size: 20px;
        color: #000;
        padding: 5px;
    }

    .user-icons .dropdown {
        position: relative;
    }

    .user-icons .dropdown .dropdown-menu {
        display: none;
        position: absolute;
        top: 30px; /* Adjust for spacing below the icon */
        right: 0;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        min-width: 150px;
        padding: 10px 0;
        z-index: 1000;
    }

    .user-icons .dropdown .dropdown-menu a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #000;
        font-size: 16px;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .user-icons .dropdown .dropdown-menu a:hover {
        background-color: #f0f0f0;
    }

    .user-icons .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
</body>
</html>
