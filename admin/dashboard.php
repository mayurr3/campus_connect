<?php
// ==========================================================
// Campus Connect — Admin Dashboard
// File: admin/dashboard.php
// Purpose: Displays dynamic metrics, recent registrations, and event highlights
// ==========================================================

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

// Guard: Only authenticated admins can access this page
require_admin_login();

$page_title = "Admin Dashboard";

// 1. Fetch Dynamic Statistics directly from MySQL
$total_students = 0;
$total_events = 0;
$total_registrations = 0;

$r = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($r) { $total_students = $r->fetch_assoc()['total']; }

$r = $conn->query("SELECT COUNT(*) AS total FROM events");
if ($r) { $total_events = $r->fetch_assoc()['total']; }

$r = $conn->query("SELECT COUNT(*) AS total FROM registrations");
if ($r) { $total_registrations = $r->fetch_assoc()['total']; }

// 2. Fetch 5 Most Recent Events
$recent_events = [];
$res_events = $conn->query("SELECT id, title, category, event_date, event_time, location FROM events ORDER BY created_at DESC LIMIT 5");
if ($res_events) {
    while ($row = $res_events->fetch_assoc()) {
        $recent_events[] = $row;
    }
}

// 3. Fetch 5 Most Recent Registrations with Student Name & Event Title (SQL JOIN)
$recent_registrations = [];
$reg_query = "
    SELECT r.id AS reg_id, r.registration_date, r.status,
           u.name AS student_name, u.email AS student_email,
           e.title AS event_title, e.event_date
    FROM registrations r
    INNER JOIN users u ON r.user_id = u.id
    INNER JOIN events e ON r.event_id = e.id
    ORDER BY r.registration_date DESC
    LIMIT 5
";
$res_reg = $conn->query($reg_query);
if ($res_reg) {
    while ($row = $res_reg->fetch_assoc()) {
        $recent_registrations[] = $row;
    }
}

require_once "header.php";
?>

<section class="dashboard-wrapper">
    <div class="container">
        <!-- Dashboard Greeting -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Admin Control Center</h2>
                <p>Real-time metrics and event operations for Campus Connect</p>
            </div>

            <div class="dashboard-nav">
                <a href="add_event.php" class="btn btn-primary btn-sm">
                    + Add New Event
                </a>
                <a href="registrations.php" class="btn btn-outline btn-sm">
                    View Registrations
                </a>
            </div>
        </div>

        <!-- 3 Primary Metric Statistics Cards (As specified in prompt item 18) -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon purple">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                </div>
                <div class="metric-info">
                    <h3><?php echo number_format($total_students); ?></h3>
                    <p>Total Registered Students</p>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon pink">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <div class="metric-info">
                    <h3><?php echo number_format($total_events); ?></h3>
                    <p>Total Events Managed</p>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon yellow">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="metric-info">
                    <h3><?php echo number_format($total_registrations); ?></h3>
                    <p>Total Registrations</p>
                </div>
            </div>
        </div>

        <!-- Two Column Grid for Recent Events & Recent Registrations -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 40px;">
            <!-- Column 1: Recent Events -->
            <div class="glass-card" style="padding: 28px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <h3 style="font-size: 1.25rem;">Recent Events</h3>
                    <a href="events.php" style="color: var(--primary-pink); font-size: 0.88rem;">Manage All →</a>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_events)): ?>
                                <?php foreach ($recent_events as $ev): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($ev['title']); ?></strong></td>
                                        <td><?php echo date("d M Y", strtotime($ev['event_date'])); ?></td>
                                        <td><span class="badge badge-info"><?php echo htmlspecialchars($ev['category']); ?></span></td>
                                        <td>
                                            <a href="edit_event.php?id=<?php echo $ev['id']; ?>" style="color: var(--accent-cyan); font-size: 0.85rem;">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align: center; color: var(--text-dim);">No events created yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Column 2: Recent Registrations -->
            <div class="glass-card" style="padding: 28px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <h3 style="font-size: 1.25rem;">Recent Registrations</h3>
                    <a href="registrations.php" style="color: var(--primary-pink); font-size: 0.88rem;">View Log →</a>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Event</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_registrations)): ?>
                                <?php foreach ($recent_registrations as $reg): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($reg['student_name']); ?></strong><br>
                                            <small style="color: var(--text-dim);"><?php echo htmlspecialchars($reg['student_email']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($reg['event_title']); ?></td>
                                        <td><span class="badge badge-success"><?php echo date("d M", strtotime($reg['registration_date'])); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" style="text-align: center; color: var(--text-dim);">No registrations yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Admin Quick Actions Bar -->
        <div class="glass-card" style="padding: 24px;">
            <h4 style="font-size: 1.1rem; margin-bottom: 14px;">Quick Management Tools</h4>
            <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                <a href="add_event.php" class="btn btn-primary btn-sm">+ Create New Event</a>
                <a href="events.php" class="btn btn-outline btn-sm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>All Events List
                </a>
                <a href="students.php" class="btn btn-outline btn-sm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>Student Accounts
                </a>
                <a href="registrations.php" class="btn btn-outline btn-sm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>Registration Master Records
                </a>
                <a href="../index.php" target="_blank" class="btn btn-outline btn-sm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Open Public Website
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once "footer.php"; ?>
