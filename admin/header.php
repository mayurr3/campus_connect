<?php
// ==========================================================
// Campus Connect — Admin Header & Navigation
// File: admin/header.php
// Purpose: Renders admin layout, topbar, and administration navigation
// ==========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_admin_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " — Admin Panel" : "Admin Panel — Campus Connect"; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Admin Navbar -->
<header class="navbar" style="border-bottom: 2px solid rgba(121, 40, 202, 0.4);">
    <div class="container">
        <!-- Brand Logo with Admin Badge -->
        <a href="dashboard.php" class="brand-logo">
            <div class="logo-badge" style="background: linear-gradient(135deg, #ef4444, #f59e0b);">CC</div>
            <div class="brand-name">Campus Connect <span style="font-size: 0.85rem; color: #f87171; letter-spacing: 1px; font-weight: 700;">ADMIN</span></div>
        </a>

        <!-- Mobile Nav Toggle -->
        <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>

        <!-- Admin Navigation Links -->
        <ul class="nav-menu" id="navMenu">
            <li>
                <a href="dashboard.php" class="nav-link <?php echo ($current_admin_page == 'dashboard.php') ? 'active' : ''; ?>" style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="events.php" class="nav-link <?php echo ($current_admin_page == 'events.php' || $current_admin_page == 'add_event.php' || $current_admin_page == 'edit_event.php') ? 'active' : ''; ?>" style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    Events
                </a>
            </li>
            <li>
                <a href="students.php" class="nav-link <?php echo ($current_admin_page == 'students.php') ? 'active' : ''; ?>" style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                    Students
                </a>
            </li>
            <li>
                <a href="registrations.php" class="nav-link <?php echo ($current_admin_page == 'registrations.php') ? 'active' : ''; ?>" style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Registrations
                </a>
            </li>
            <li>
                <a href="../index.php" target="_blank" class="nav-link" style="color: var(--accent-cyan); display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Site
                </a>
            </li>
        </ul>

        <!-- Admin User & Logout -->
        <div class="nav-actions">
            <span style="font-size: 0.88rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 6px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Administrator'); ?>
            </span>
            <a href="logout.php" class="btn btn-danger btn-sm">
                Logout
            </a>
        </div>
    </div>
</header>
