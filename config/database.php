<?php
// ==========================================================
// Campus Connect — Database Connection Configuration
// File: config/database.php
// Purpose: Establishes a connection to the MySQL database
// ==========================================================

// Database credentials for WAMPServer default configuration
$db_host = "127.0.0.1";    // Localhost server IP
$db_user = "root";         // Default WAMPServer MySQL username
$db_pass = "";             // Default WAMPServer MySQL password (empty)
$db_name = "collage_connect"; // Project database name
$db_port = 3306;           // Default MySQL port

// Create connection using MySQLi extension
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Check if connection failed
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set character encoding to UTF-8 for international character support
$conn->set_charset("utf8mb4");

// Helper function to safely sanitize user input against XSS
if (!function_exists('sanitize')) {
    function sanitize($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
?>
