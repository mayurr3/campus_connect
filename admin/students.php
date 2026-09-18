<?php
// ==========================================================
// Campus Connect — Admin Students Management
// File: admin/students.php
// Purpose: View, search, and manage registered student accounts
// ==========================================================

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

require_admin_login();

$page_title = "Student Directory";
$success_msg = "";

// Handle Student Account Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($delete_id > 0) {
        $del_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $del_stmt->bind_param("i", $delete_id);
        if ($del_stmt->execute()) {
            $success_msg = "Student account and all associated registrations removed successfully.";
        }
    }
}

// Fetch all registered students along with their count of registered events
$sql = "
    SELECT u.*, COUNT(r.id) AS total_events_registered
    FROM users u
    LEFT JOIN registrations r ON u.id = r.user_id
    GROUP BY u.id
    ORDER BY u.created_at DESC
";
$result = $conn->query($sql);
$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

require_once "header.php";
?>

<section class="dashboard-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Manage Students</h2>
                <p>View registered student accounts, course profiles, and event involvement</p>
            </div>
        </div>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg><?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>

        <!-- Students Table -->
        <div class="glass-card" style="padding: 32px;">
            <?php if (!empty($students)): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Email Address</th>
                                <th>Contact No</th>
                                <th>Course & Semester</th>
                                <th>Joined On</th>
                                <th>Events Registered</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $stu): ?>
                                <tr>
                                    <td style="color: var(--text-dim); font-weight: 700;">#<?php echo $stu['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($stu['name']); ?></strong>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($stu['email']); ?>" style="color: var(--accent-cyan);">
                                            <?php echo htmlspecialchars($stu['email']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($stu['phone'] ?: 'N/A'); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($stu['course']); ?><br>
                                        <small style="color: var(--text-dim);"><?php echo htmlspecialchars($stu['semester']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo date("d M Y", strtotime($stu['created_at'])); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo $stu['total_events_registered']; ?> Events
                                        </span>
                                    </td>
                                    <td>
                                        <a href="students.php?delete_id=<?php echo $stu['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this student? All their registrations will also be deleted.');">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <p style="color: var(--text-muted);">No students registered in the database yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once "footer.php"; ?>
