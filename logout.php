<?php
// ==========================================================
// Campus Connect — Student Logout
// File: logout.php
// Purpose: Destroys user session and redirects to login
// ==========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset student session variables
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);
unset($_SESSION['user_course']);

// Destroy session entirely if no admin session exists
if (!isset($_SESSION['admin_id'])) {
    session_destroy();
}

// Redirect with confirmation query parameter
header("Location: login.php?logged_out=1");
exit();
?>
