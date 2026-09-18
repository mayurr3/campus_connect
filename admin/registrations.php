<?php
// ==========================================================
// Campus Connect — Admin Registration Management
// File: admin/registrations.php
// Purpose: View and manage all student event registrations using SQL JOIN
// ==========================================================

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

require_admin_login();

$page_title = "Event Registrations Master";
$success_msg = "";

// Handle Individual Registration Cancellation/Deletion
if (isset($_GET['cancel_id'])) {
    $cancel_id = intval($_GET['cancel_id']);
    if ($cancel_id > 0) {
        $del_stmt = $conn->prepare("DELETE FROM registrations WHERE id = ?");
        $del_stmt->bind_param("i", $cancel_id);
        if ($del_stmt->execute()) {
            $success_msg = "Registration record cancelled successfully.";
        }
    }
}

// Fetch all registrations connecting users and events using SQL INNER JOIN
$sql = "
    SELECT 
        r.id AS reg_id,
        r.registration_date,
        r.status,
        u.id AS user_id,
        u.name AS student_name,
        u.email AS student_email,
        u.course,
        u.phone,
        e.id AS event_id,
        e.title AS event_title,
        e.event_date,
        e.event_time,
        e.location
    FROM registrations r
    INNER JOIN users u ON r.user_id = u.id
    INNER JOIN events e ON r.event_id = e.id
    ORDER BY r.registration_date DESC
";

$result = $conn->query($sql);
$registrations = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $registrations[] = $row;
    }
}

require_once "header.php";
?>

<section class="dashboard-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Event Registrations Master</h2>
                <p>Master attendance and participation registry for all campus events</p>
            </div>

            <div>
                <span class="badge badge-info" style="font-size: 0.95rem; padding: 8px 16px;">
                    Total Records: <?php echo count($registrations); ?>
                </span>
            </div>
        </div>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg><?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>

        <!-- Registrations Master Table -->
        <div class="glass-card" style="padding: 32px;">
            <?php if (!empty($registrations)): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Reg ID</th>
                                <th>Student Details</th>
                                <th>Event Title</th>
                                <th>Event Date</th>
                                <th>Registered At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registrations as $row): ?>
                                <tr>
                                    <td style="color: var(--text-dim); font-weight: 700;">
                                        #REG-<?php echo str_pad($row['reg_id'], 4, '0', STR_PAD_LEFT); ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['student_name']); ?></strong><br>
                                        <small style="color: var(--accent-cyan);"><?php echo htmlspecialchars($row['student_email']); ?></small><br>
                                        <small style="color: var(--text-dim);"><?php echo htmlspecialchars($row['course']); ?> • <?php echo htmlspecialchars($row['phone'] ?: 'No Phone'); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['event_title']); ?></strong><br>
                                        <small style="color: var(--text-dim); display: inline-flex; align-items: center; gap: 4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg><?php echo htmlspecialchars($row['location']); ?></small>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><?php echo date("d M Y", strtotime($row['event_date'])); ?></span><br>
                                        <span style="display: inline-flex; align-items: center; gap: 4px; color: var(--text-dim);"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><small><?php echo date("h:i A", strtotime($row['event_time'])); ?></small></span>
                                    </td>
                                    <td>
                                        <?php echo date("d M Y, h:i A", strtotime($row['registration_date'])); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-success" style="display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg><?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="registrations.php?cancel_id=<?php echo $row['reg_id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to cancel this registration record?');">
                                            Cancel
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <p style="color: var(--text-muted);">No student registrations found in the system yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once "footer.php"; ?>
