<?php
session_start();
include("../includes/config.php");

if (isset($_POST['submit'])) {

    $uname = trim($_POST['uname']); // Match with form input name
    $pass = sha1(trim($_POST['password'])); // Match with form input name

    // Corrected query to match the actual table name and column names
    $sql = "SELECT user_id, username, role_id, member_id FROM users WHERE username = ? AND password_hash = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $uname, $pass);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $user_id, $username, $role_id, $member_id);
            mysqli_stmt_fetch($stmt);

            // Store user data in session
            $_SESSION['username'] = $username; // Fixed typo from 'uername'
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role_id;
            $_SESSION['member_id'] = $member_id;

            header("Location: ../index.php"); // If index.php is in RESCUENET folder
            exit();
        } else {
            $_SESSION['message'] = 'Wrong username or password';
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = 'Database error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Login Form</title>
</head>
<body>
<div class="wrapper">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

        <!-- Username input -->
        <div class="input-box">
            <input type="text" name="uname" placeholder="Enter your username" required />
            <i class='bx bxs-user-circle'></i>
        </div>

        <!-- Password input -->
        <div class="input-box">
            <input type="password" name="password" placeholder="Enter your password" required />
            <i class='bx bxs-lock-alt'></i>
        </div>

        <div class="remember-forgot">
            <label><input type="checkbox"> Remember Me</label>
            <a href="#">Forgot Password?</a>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn" name="submit">Login</button>

        <!-- Register link -->
        <div class="register-link">
            <p>Not a member? <a href="register.php">Register</a></p>
        </div>
    </form>

    <!-- Display session message if any -->
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: red;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
</div>
</body>
</html>

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
        color: seagreen}

    .input-box {
        position: relative;
        margin: 20px 0;
    }

    .input-box input {
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

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin: 15px 0;
    }
    .remember-forgot label {
    color: #fff; 
    }

    .remember-forgot a {
        color: #1a8f77;
        text-decoration: none;
    }

    .remember-forgot a:hover {
        text-decoration: underline;
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