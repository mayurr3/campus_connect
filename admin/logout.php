<?php
// ==========================================================
// Campus Connect — Admin Logout
// File: admin/logout.php
// Purpose: Destroys admin session and redirects to admin login
// ==========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

header("Location: login.php?logged_out=1");
exit();
?>
