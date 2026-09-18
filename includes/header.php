<?php
// ==========================================================
// Campus Connect — Common Header & Navigation Bar
// File: includes/header.php
// Purpose: Renders standard HTML head, styles, and top navbar
// ==========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/auth.php";

// Determine relative path prefix depending on whether page is in root or subfolder
$prefix = isset($path_prefix) ? $path_prefix : './';
$current_page = basename($_SERVER['PHP_SELF']);
$is_student = is_student_logged_in();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " — Campus Connect" : "Campus Connect — Connect. Participate. Celebrate."; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo $prefix; ?>assets/css/style.css">
</head>
<body>

<!-- Header / Sticky Navigation Bar -->
<header class="navbar">
    <div class="container">
        <!-- Brand Logo -->
        <a href="<?php echo $prefix; ?>index.php" class="brand-logo">
            <div class="logo-badge">CC</div>
            <div class="brand-name">Campus <span>Connect</span></div>
        </a>

        <!-- Mobile Navigation Toggle -->
        <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>

        <!-- Main Navigation Menu -->
        <ul class="nav-menu" id="navMenu">
            <li>
                <a href="<?php echo $prefix; ?>index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    Home
                </a>
            </li>
            <li>
                <a href="<?php echo $prefix; ?>events.php" class="nav-link <?php echo ($current_page == 'events.php' || $current_page == 'event_details.php') ? 'active' : ''; ?>">
                    Events
                </a>
            </li>
            <li>
                <a href="<?php echo $prefix; ?>about.php" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">
                    About
                </a>
            </li>

            <?php if ($is_student): ?>
                <li>
                    <a href="<?php echo $prefix; ?>student/dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo $prefix; ?>student/my_events.php" class="nav-link <?php echo ($current_page == 'my_events.php') ? 'active' : ''; ?>">
                        My Events
                    </a>
                </li>
                <li>
                    <a href="<?php echo $prefix; ?>student/profile.php" class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                        Profile
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Authentication Buttons -->
        <div class="nav-actions">
            <?php if ($is_student): ?>
                <a href="<?php echo $prefix; ?>student/profile.php" class="btn btn-outline btn-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Student'); ?></span>
                </a>
                <a href="<?php echo $prefix; ?>logout.php" class="btn btn-danger btn-sm">
                    Logout
                </a>
            <?php else: ?>
                <a href="<?php echo $prefix; ?>login.php" class="btn btn-outline btn-sm">
                    Sign In
                </a>
                <a href="<?php echo $prefix; ?>register.php" class="btn btn-primary btn-sm">
                    Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
