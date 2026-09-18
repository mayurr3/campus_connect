<?php
// ==========================================================
// Campus Connect — Student Registration
// File: register.php
// Purpose: Allows new college students to create an account
// Security: Server-side validation + password_hash (BCRYPT)
// ==========================================================

$page_title = "Student Registration";
require_once "config/database.php";
require_once "includes/auth.php";

// If student is already logged in, redirect straight to dashboard
if (is_student_logged_in()) {
    header("Location: student/dashboard.php");
    exit();
}

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form inputs
    $name     = sanitize($_POST['name'] ?? '');
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $phone    = sanitize($_POST['phone'] ?? '');
    $course   = sanitize($_POST['course'] ?? '');
    $semester = sanitize($_POST['semester'] ?? '');

    // Basic Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error_msg = "Please fill in all required fields (Name, Email, Password).";
    } elseif (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters long.";
    } else {
        // Check for duplicate email in database
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();

        if ($check_res->num_rows > 0) {
            $error_msg = "An account with this email already exists. Please sign in instead.";
        } else {
            // Hash password securely using PHP standard bcrypt algorithm
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new student into MySQL database
            $insert_stmt = $conn->prepare(
                "INSERT INTO users (name, email, password, phone, course, semester) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $insert_stmt->bind_param("ssssss", $name, $email, $hashed_password, $phone, $course, $semester);

            if ($insert_stmt->execute()) {
                // Registration successful -> redirect to login with confirmation
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error_msg = "Failed to create account. Please try again or contact support.";
            }
        }
    }
}

require_once "includes/header.php";
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Create Student Account</h2>
            <p>Join Campus Connect to register and participate in college events</p>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span><?php echo htmlspecialchars($error_msg); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" class="needs-validation">
            <div class="form-group">
                <label class="form-label" for="name">Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Rahul Sharma" required
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">College Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="name@college.edu" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password (Min 6 Characters) *</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" data-target="password" title="Show/Hide Password" aria-label="Toggle password visibility">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="10-digit mobile number"
                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="course">Course / Degree</label>
                    <select id="course" name="course" class="form-control">
                        <option value="BCA" selected>BCA</option>
                        <option value="B.Sc Computer Science">B.Sc Computer Science</option>
                        <option value="B.Tech CS/IT">B.Tech CS/IT</option>
                        <option value="MCA">MCA</option>
                        <option value="BBA">BBA</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="semester">Semester</label>
                    <select id="semester" name="semester" class="form-control">
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                        <option value="3rd Semester">3rd Semester</option>
                        <option value="4th Semester">4th Semester</option>
                        <option value="5th Semester" selected>5th Semester</option>
                        <option value="6th Semester">6th Semester</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">
                Complete Registration
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Log In Here</a>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
