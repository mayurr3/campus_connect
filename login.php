<?php
// ==========================================================
// Campus Connect — Student Login
// File: login.php
// Purpose: Authenticates student via email and password_verify()
// ==========================================================

$page_title = "Student Login";
require_once "config/database.php";
require_once "includes/auth.php";

// If student is already logged in, redirect directly to dashboard
if (is_student_logged_in()) {
    header("Location: student/dashboard.php");
    exit();
}

$error_msg = "";
$success_msg = "";

// Check for redirect flash messages
if (isset($_GET['registered']) && $_GET['registered'] == '1') {
    $success_msg = "Account created successfully! Please log in below.";
}
if (isset($_GET['logged_out']) && $_GET['logged_out'] == '1') {
    $success_msg = "You have been logged out successfully.";
}
if (isset($_SESSION['error_message'])) {
    $error_msg = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

$redirect = isset($_GET['redirect']) ? sanitize($_GET['redirect']) : 'student/dashboard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = sanitize($_POST['redirect'] ?? 'student/dashboard.php');

    if (empty($email) || empty($password)) {
        $error_msg = "Please enter both your email address and password.";
    } else {
        // Query user by email using prepared statement
        $stmt = $conn->prepare("SELECT id, name, email, password, course FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();

            // Verify entered password against bcrypt hash in database
            if (password_verify($password, $user['password'])) {
                // Password is correct! Establish session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_course'] = $user['course'];

                // Clean redirect path to prevent open redirect vulnerabilities
                $target = (!empty($redirect) && !str_starts_with($redirect, 'http')) ? $redirect : 'student/dashboard.php';
                header("Location: " . $target);
                exit();
            } else {
                $error_msg = "Invalid email or password. Please check your credentials.";
            }
        } else {
            $error_msg = "Invalid email or password. Please check your credentials.";
        }
    }
}

require_once "includes/header.php";
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Log in to access your student dashboard and registered events</p>
        </div>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span><?php echo htmlspecialchars($success_msg); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span><?php echo htmlspecialchars($error_msg); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="needs-validation">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

            <div class="form-group">
                <label class="form-label" for="email">Student Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="student@college.edu"
                    required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                        required>
                    <button type="button" class="password-toggle" data-target="password" title="Show/Hide Password"
                        aria-label="Toggle password visibility">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">
                Sign In to Dashboard
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account yet? <a href="register.php">Create Account</a>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>