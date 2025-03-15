<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include this file in any page where you need to check the user's role
if (isset($_SESSION['role']) && $_SESSION['role'] == 4) {
    include('admin_header.php');
} else {
    include('header.php');
}
//para sa shift index eme 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $is_admin = false;
} else {
    $is_admin = true;
}
?>
