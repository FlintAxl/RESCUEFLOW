<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Register Form</title>
</head>

<?php
session_start();
include("../includes/config.php"); // Add this line to connect to the database

if (isset($_SESSION['message'])) {
    echo "<p>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Register Form</title>
</head>
<body>

<div class="wrapper">
    <form action="store.php" method="POST">

        <!-- Email input -->
        <div class="input-box">
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required />
            <i class='bx bxs-envelope'></i>
        </div>

        <!-- Username input -->
        <div class="input-box">
            <input type="text" class="form-control" id="uname" name="uname" placeholder="Enter your username" required />
            <i class='bx bxs-user-circle'></i>
        </div>

        <!-- Password input -->
        <div class="input-box">
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required />
            <i class='bx bxs-lock-alt'></i>
        </div>

        <!-- Confirm password input -->
        <div class="input-box">
            <input type="password" class="form-control" id="password2" name="confirmPass" placeholder="Confirm your password" required />
            <i class='bx bxs-lock-alt'></i>
        </div>

       <!-- Role selection -->
<div class="input-box">
    <select class="form-control" id="roles" name="role_id" required>
        <option value="">Select role</option>

        <?php
        // Ensure connection is established before querying
        if ($conn) {
            $sql = "SELECT role_id, role_name FROM roles WHERE role_id != 4"; // Exclude admin
            $result = mysqli_query($conn, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value=\"{$row['role_id']}\">{$row['role_name']}</option>";
                }
            } else {
                echo "<option value=''>Error loading roles</option>";
            }
        } else {
            echo "<option value=''>Database connection error</option>";
        }
        ?>
    </select>
    <i class='bx bxs-key'></i>
</div>


        <!-- Submit button -->
        <button type="submit" class="btn" name="submit">Register</button>

        <!-- Login link -->
        <div class="register-link">
            <p>Already a member? <a href="login.php">Login</a></p>
        </div>
    </form>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: url('../item/images/new.jpg') no-repeat;
        background-size: cover;
        background-position: center;
    }

    .wrapper {
        width: 400px;
        background: transparent;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .wrapper h1 {
        font-size: 50px;
        margin-bottom: 20px;
        color: seagreen;
    }

    .input-box {
        position: relative;
        margin: 20px 0;
    }

    .input-box input, .input-box select {
        width: 100%;
        padding: 12px 20px 12px 40px;
        border: 2px solid rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        font-size: 16px;
        color: #333;
    }

    .input-box i {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 18px;
    }

    .btn {
        width: 100%;
        padding: 12px;
        background-color: #1a8f77;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #0e6a55;
    }

    .register-link p {
        font-size: 14px;
        margin-top: 15px;
        color: white;
    }

    .register-link a {
        color: #1a8f77;
        text-decoration: none;
        font-weight: bold;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    .logo {
    display: block;
    margin: 0 auto 20px;
    width: 150px;
    height: auto;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.logo:hover {
    transform: scale(1.1); /* Slightly enlarge the logo */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Add a stronger shadow */
    cursor: pointer; /* Change the cursor to a pointer */
}
</style>

</body>
</html>