<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an admin (role_id = 4)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 4) {
    echo "<script>alert('Access denied! Only administrators can access this page.'); window.location.href='/RESCUEFLOW/index.php';</script>";
    exit();
}
?>
