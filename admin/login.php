<?php
// ==========================================================
// Campus Connect — Admin Login
// File: admin/login.php
// Purpose: Authenticates administrator using bcrypt hash
// ==========================================================

$path_prefix = "../";
$page_title = "Admin Login";

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

// If already logged in as admin, redirect to admin dashboard
if (is_admin_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$error_msg = "";
$success_msg = "";

if (isset($_GET['logged_out']) && $_GET['logged_out'] == '1') {
    $success_msg = "Admin session ended successfully.";
}
if (isset($_SESSION['error_message'])) {
    $error_msg = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_msg = "Please enter both admin username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $admin = $res->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                // Success: store admin session variables
                $_SESSION['admin_id']       = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error_msg = "Invalid password. Access denied.";
            }
        } else {
            $error_msg = "Invalid admin username. Access denied.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Campus Connect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card" style="border-top: 4px solid #ef4444;">
        <div class="auth-header">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(239, 68, 68, 0.15); color: #f87171; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2>Admin Control Panel</h2>
            <p>Campus Connect System Administration</p>
        </div>

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

        <form method="POST" action="login.php">
            <div class="form-group">
                <label class="form-label" for="username">Admin Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter admin username" required
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Admin Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" data-target="password" title="Show/Hide Password" aria-label="Toggle password visibility">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 10px; background: linear-gradient(135deg, #ef4444, #f59e0b);">
                Authorize & Log In
            </button>
        </form>

        <div class="auth-footer">
            <a href="../index.php">← Back to Public Website</a>
        </div>
    </div>
</div>

<script src="../assets/js/script.js"></script>
</body>
</html>
