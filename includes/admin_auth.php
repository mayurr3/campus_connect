<?php
// ==========================================================
// Campus Connect — Administrator Authentication Guard
// File: includes/admin_auth.php
// Purpose: Protects the admin panel from unauthorized users
// ==========================================================

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if admin is currently authenticated
 * @return bool
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Protect admin dashboard and management pages
 */
function require_admin_login() {
    if (!is_admin_logged_in()) {
        $_SESSION['error_message'] = "You must be logged in as an admin to access this area.";
        header("Location: login.php");
        exit();
    }
}

/**
 * Get current logged in admin username
 */
function get_admin_username() {
    return $_SESSION['admin_username'] ?? 'Administrator';
}
?>
