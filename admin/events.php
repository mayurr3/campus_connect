<?php
// ==========================================================
// Campus Connect — Admin Events Management
// File: admin/events.php
// Purpose: View, manage, edit, and delete events
// ==========================================================

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

require_admin_login();

$page_title = "Manage Events";

$success_msg = "";
if (isset($_GET['created']) && $_GET['created'] == '1') {
    $success_msg = "Event created successfully!";
}
if (isset($_GET['updated']) && $_GET['updated'] == '1') {
    $success_msg = "Event updated successfully!";
}
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    $success_msg = "Event deleted successfully.";
}

// Fetch all events with total registrations count for each event
$sql = "
    SELECT e.*, COUNT(r.id) AS reg_count
    FROM events e
    LEFT JOIN registrations r ON e.id = r.event_id
    GROUP BY e.id
    ORDER BY e.event_date ASC
";
$result = $conn->query($sql);
$events = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

require_once "header.php";
?>

<section class="dashboard-wrapper">
    <div class="container">
        <!-- Header & Action Button -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Manage Events</h2>
                <p>Add new events, edit details, and track student registrations</p>
            </div>

            <div class="dashboard-nav">
                <a href="add_event.php" class="btn btn-primary btn-sm">
                    + Add New Event
                </a>
            </div>
        </div>

        <!-- Success Notifications -->
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><?php echo htmlspecialchars($success_msg); ?></span>
            </div>
        <?php endif; ?>

        <!-- Events Data Table -->
        <div class="glass-card" style="padding: 32px;">
            <?php if (!empty($events)): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Event Title</th>
                                <th>Category</th>
                                <th>Schedule</th>
                                <th>Location</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $ev): ?>
                                <tr>
                                    <td style="color: var(--text-dim); font-weight: 700;">#<?php echo $ev['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($ev['title']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?php echo htmlspecialchars($ev['category']); ?></span>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <?php echo date("d M Y", strtotime($ev['event_date'])); ?>
                                        </span><br>
                                        <small style="color: var(--text-dim); display: inline-flex; align-items: center; gap: 4px; margin-top: 2px;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <?php echo date("h:i A", strtotime($ev['event_time'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <?php echo htmlspecialchars($ev['location']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            <?php echo $ev['reg_count']; ?> Students
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="edit_event.php?id=<?php echo $ev['id']; ?>" class="btn btn-outline btn-sm">
                                                Edit
                                            </a>
                                            <a href="delete_event.php?id=<?php echo $ev['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this event? All associated student registrations will also be removed.');">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <p style="color: var(--text-muted); margin-bottom: 16px;">No events created yet.</p>
                    <a href="add_event.php" class="btn btn-primary btn-sm">+ Create First Event</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once "footer.php"; ?>
