<?php
session_start();
include("../includes/config.php");

// Sanitize and fetch form data
$email = trim($_POST['email']);
$uname = trim($_POST['uname']);
$password = trim($_POST['password']);
$role_id = $_POST['role_id']; // fetch role_id directly

// Validate email format
if (!preg_match("/^\w+@\w+\.\w+/", $email)) {
    $_SESSION['message'] = 'Invalid email format';
    header("Location: register.php");
    exit();
} 

// Validate password length
if (strlen($password) < 3) {
    $_SESSION['message'] = 'Password should be at least 3 characters';
    header("Location: register.php");
    exit();
}

// Validate password match
$confirmPass = trim($_POST['confirmPass']);
if ($password !== $confirmPass) {
    $_SESSION['message'] = 'Passwords do not match';
    header("Location: register.php");
    exit();
}

// Check if the selected role_id exists in the roles table
$sql = "SELECT role_id FROM roles WHERE role_id = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $role_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        $_SESSION['message'] = 'Invalid role selected';
        header("Location: register.php");
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = 'Database error: Unable to check role';
    header("Location: register.php");
    exit();
}

// Encrypt the password
$password = sha1($password);
// Fetch member_id (optional)
$member_id = isset($_POST['member_id']) && !empty($_POST['member_id']) ? $_POST['member_id'] : NULL;

// Insert the new user into the database
$sql = "INSERT INTO users (username, email, password_hash, role_id, member_id) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssii", $uname, $email, $password, $role_id, $member_id);
    $execute = mysqli_stmt_execute($stmt);

    if ($execute) {
        $_SESSION['message'] = 'Registration successful';
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['message'] = 'Error during registration';
        header("Location: register.php");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = 'Database error';
    header("Location: register.php");
    exit();
}

?>