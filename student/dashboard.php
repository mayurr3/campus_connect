<?php
// ==========================================================
// Campus Connect — Student Dashboard
// File: student/dashboard.php
// Purpose: Displays student metrics, upcoming registered events, and quick actions
// ==========================================================

$path_prefix = "../";
$page_title = "Student Dashboard";

require_once "../config/database.php";
require_once "../includes/auth.php";

// Guard: Only logged in students can access this page
require_student_login();

$student_id = get_student_id();

// 1. Fetch current student profile from database
$user_stmt = $conn->prepare("SELECT name, email, course, semester FROM users WHERE id = ?");
$user_stmt->bind_param("i", $student_id);
$user_stmt->execute();
$student = $user_stmt->get_result()->fetch_assoc();

// 2. Fetch Total Registered Events Count
$total_reg_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM registrations WHERE user_id = ?");
$total_reg_stmt->bind_param("i", $student_id);
$total_reg_stmt->execute();
$total_registrations = $total_reg_stmt->get_result()->fetch_assoc()['total'];

// 3. Fetch Upcoming Registered Events using SQL JOIN
$upcoming_stmt = $conn->prepare("
    SELECT r.id AS reg_id, r.registration_date, r.status,
           e.id AS event_id, e.title, e.event_date, e.event_time, e.location, e.category, e.image
    FROM registrations r
    INNER JOIN events e ON r.event_id = e.id
    WHERE r.user_id = ? AND e.event_date >= CURDATE()
    ORDER BY e.event_date ASC
    LIMIT 5
");
$upcoming_stmt->bind_param("i", $student_id);
$upcoming_stmt->execute();
$upcoming_result = $upcoming_stmt->get_result();

$my_upcoming_events = [];
while ($row = $upcoming_result->fetch_assoc()) {
    $my_upcoming_events[] = $row;
}

require_once "../includes/header.php";
?>

<section class="dashboard-wrapper">
    <div class="container">
        <!-- Dashboard Header & Greeting -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h2>
                <p><?php echo htmlspecialchars($student['course']); ?> • <?php echo htmlspecialchars($student['semester']); ?> • <?php echo htmlspecialchars($student['email']); ?></p>
            </div>

            <div class="dashboard-nav">
                <a href="../events.php" class="btn btn-primary btn-sm">
                    + Register for More Events
                </a>
                <a href="profile.php" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Metric Statistics Cards -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon purple">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <div class="metric-info">
                    <h3><?php echo number_format($total_registrations); ?></h3>
                    <p>Total Registered Events</p>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon pink">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="metric-info">
                    <h3><?php echo count($my_upcoming_events); ?></h3>
                    <p>Upcoming Scheduled</p>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon yellow">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <div class="metric-info">
                    <h3>Active</h3>
                    <p>Student Membership</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Registered Events Table / Cards -->
        <div class="glass-card" style="padding: 32px; margin-bottom: 40px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
                <h3 style="font-size: 1.4rem;">Your Upcoming Events Schedule</h3>
                <a href="my_events.php" style="color: var(--primary-pink); font-size: 0.9rem; font-weight: 600;">
                    View All Registrations →
                </a>
            </div>

            <?php if (!empty($my_upcoming_events)): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Category</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_upcoming_events as $ev): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($ev['title']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo htmlspecialchars($ev['category']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date("d M Y", strtotime($ev['event_date'])); ?><br>
                                        <small style="color: var(--text-dim);"><?php echo date("h:i A", strtotime($ev['event_time'])); ?></small>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <?php echo htmlspecialchars($ev['location']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            <?php echo htmlspecialchars($ev['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="../event_details.php?id=<?php echo $ev['event_id']; ?>" class="btn btn-outline btn-sm">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(121, 40, 202, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <h4 style="font-size: 1.2rem; margin-bottom: 8px;">No upcoming registered events</h4>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">
                        You haven't registered for any future events yet. Explore upcoming campus happenings!
                    </p>
                    <a href="../events.php" class="btn btn-primary">
                        Browse Events Catalog
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Links Hub -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            <div class="glass-card" style="padding: 24px;">
                <h4 style="font-size: 1.15rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    My Registered Events
                </h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">
                    Check the complete historical and upcoming log of every event you registered for.
                </p>
                <a href="my_events.php" class="btn btn-outline btn-sm">
                    Open My Events
                </a>
            </div>

            <div class="glass-card" style="padding: 24px;">
                <h4 style="font-size: 1.15rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile Details
                </h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">
                    Review your student account details, course, semester, and update contact details.
                </p>
                <a href="profile.php" class="btn btn-outline btn-sm">
                    Update Profile
                </a>
            </div>

            <div class="glass-card" style="padding: 24px;">
                <h4 style="font-size: 1.15rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Discover More
                </h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">
                    Discover workshops, coding hackathons, sports matches, and cultural festivals.
                </p>
                <a href="../events.php" class="btn btn-primary btn-sm">
                    Explore Directory
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once "../includes/footer.php"; ?>
