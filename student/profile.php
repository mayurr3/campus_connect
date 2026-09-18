<?php
// ==========================================================
// Campus Connect — Student Profile Management
// File: student/profile.php
// Purpose: Allows student to view and update contact details and academic info
// ==========================================================

$path_prefix = "../";
$page_title = "My Profile";

require_once "../config/database.php";
require_once "../includes/auth.php";

// Guard: Student must be logged in
require_student_login();

$student_id = get_student_id();
$success_msg = "";
$error_msg = "";

// 1. Handle Profile Update Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($_POST['name'] ?? '');
    $phone    = sanitize($_POST['phone'] ?? '');
    $course   = sanitize($_POST['course'] ?? '');
    $semester = sanitize($_POST['semester'] ?? '');

    if (empty($name)) {
        $error_msg = "Name field cannot be empty.";
    } else {
        $update_stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, course = ?, semester = ? WHERE id = ?");
        $update_stmt->bind_param("ssssi", $name, $phone, $course, $semester, $student_id);

        if ($update_stmt->execute()) {
            $_SESSION['user_name']   = $name;
            $_SESSION['user_course'] = $course;
            $success_msg = "Profile updated successfully!";
        } else {
            $error_msg = "Failed to update profile. Please try again.";
        }
    }
}

// 2. Fetch Latest Profile Data from Database
$stmt = $conn->prepare("SELECT name, email, phone, course, semester, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

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
                <h2>Manage Student Profile</h2>
                <p>Keep your academic information and contact details up to date</p>
            </div>
        </div>

        <div style="max-width: 680px; margin: 0 auto;">
            <!-- Feedback Alerts -->
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span><?php echo htmlspecialchars($success_msg); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-error">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span><?php echo htmlspecialchars($error_msg); ?></span>
                </div>
            <?php endif; ?>

            <div class="glass-card" style="padding: 40px;">
                <form method="POST" action="profile.php">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-control" required
                               value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address (Account Identifier)</label>
                        <input type="email" id="email" class="form-control" readonly 
                               style="opacity: 0.65; cursor: not-allowed;"
                               value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>">
                        <small style="color: var(--text-dim); display: block; margin-top: 4px;">
                            Email cannot be altered as it is uniquely bound to your student account records.
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control"
                               value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="course">Course / Degree</label>
                            <select id="course" name="course" class="form-control">
                                <?php 
                                $courses = ['BCA', 'B.Sc Computer Science', 'B.Tech CS/IT', 'MCA', 'BBA', 'Other'];
                                foreach ($courses as $c) {
                                    $sel = ($student['course'] === $c) ? 'selected' : '';
                                    echo "<option value=\"$c\" $sel>$c</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="semester">Current Semester</label>
                            <select id="semester" name="semester" class="form-control">
                                <?php 
                                $sems = ['1st Semester', '2nd Semester', '3rd Semester', '4th Semester', '5th Semester', '6th Semester'];
                                foreach ($sems as $s) {
                                    $sel = ($student['semester'] === $s) ? 'selected' : '';
                                    echo "<option value=\"$s\" $sel>$s</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Member Since</label>
                        <div style="color: var(--text-muted); font-size: 0.95rem; display: inline-flex; align-items: center; gap: 6px;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span><?php echo date("F j, Y, g:i A", strtotime($student['created_at'])); ?></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">
                        Save Profile Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once "../includes/footer.php"; ?>
