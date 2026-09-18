<?php
// ==========================================================
// Campus Connect — Student My Registered Events
// File: student/my_events.php
// Purpose: Displays all events registered by the logged-in student using SQL JOIN
// ==========================================================

$path_prefix = "../";
$page_title = "My Registered Events";

require_once "../config/database.php";
require_once "../includes/auth.php";

// Guard: Student must be logged in
require_student_login();

$student_id = get_student_id();

// Execute SQL JOIN query between registrations and events for this student
$sql = "
    SELECT 
        r.id AS reg_id, 
        r.registration_date, 
        r.status,
        e.id AS event_id, 
        e.title, 
        e.event_date, 
        e.event_time, 
        e.location, 
        e.category, 
        e.image
    FROM registrations r
    INNER JOIN events e ON r.event_id = e.id
    WHERE r.user_id = ?
    ORDER BY r.registration_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$my_registrations = [];
while ($row = $result->fetch_assoc()) {
    $my_registrations[] = $row;
}

require_once "../includes/header.php";
?>

<section class="dashboard-wrapper">
    <div class="container">
        <!-- Breadcrumb & Header -->
        <div style="margin-bottom: 24px;">
            <a href="dashboard.php" style="color: var(--text-muted); font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px;">
                ← Back to Dashboard
            </a>
        </div>

        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>My Event Registrations</h2>
                <p>Track all the campus events you have signed up for</p>
            </div>

            <div>
                <a href="../events.php" class="btn btn-primary btn-sm">
                    + Browse More Events
                </a>
            </div>
        </div>

        <!-- Registered Events Table -->
        <div class="glass-card" style="padding: 32px;">
            <?php if (!empty($my_registrations)): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event Title</th>
                                <th>Category</th>
                                <th>Event Schedule</th>
                                <th>Venue</th>
                                <th>Registered On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            foreach ($my_registrations as $reg): 
                            ?>
                                <tr>
                                    <td style="color: var(--text-dim);"><?php echo $count++; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($reg['title']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo htmlspecialchars($reg['category']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <?php echo date("d M Y", strtotime($reg['event_date'])); ?>
                                        </span><br>
                                        <small style="color: var(--text-dim); display: inline-flex; align-items: center; gap: 4px; margin-top: 2px;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <?php echo date("h:i A", strtotime($reg['event_time'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <?php echo htmlspecialchars($reg['location']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date("d M Y, h:i A", strtotime($reg['registration_date'])); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            <?php echo htmlspecialchars($reg['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="../event_details.php?id=<?php echo $reg['event_id']; ?>" class="btn btn-outline btn-sm">
                                            View Page
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(121, 40, 202, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <h3 style="font-size: 1.4rem; margin-bottom: 8px;">No event registrations yet</h3>
                    <p style="color: var(--text-muted); max-width: 480px; margin: 0 auto 24px;">
                        You have not registered for any events yet. Check out our upcoming college events directory and register in one click!
                    </p>
                    <a href="../events.php" class="btn btn-primary">
                        Browse Upcoming Events →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once "../includes/footer.php"; ?>
