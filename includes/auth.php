<?php
// ==========================================================
// Campus Connect — Student Authentication Guard
// File: includes/auth.php
// Purpose: Manages student session states and access control
// ==========================================================

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a student is currently logged in
 * @return bool
 */
function is_student_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Protect student pages from unauthorized guest access
 */
function require_student_login() {
    if (!is_student_logged_in()) {
        // Store intended destination to redirect back if needed
        $_SESSION['error_message'] = "Please log in to access this page.";
        header("Location: ../login.php");
        exit();
    }
}

/**
 * Get current student ID
 */
function get_student_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current student Name
 */
function get_student_name() {
    return $_SESSION['user_name'] ?? 'Student';
}
?>
