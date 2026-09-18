<?php
// ==========================================================
// Campus Connect — Event Details & Registration Handler
// File: event_details.php
// Purpose: Displays comprehensive event info and processes student registration
// ==========================================================

require_once "config/database.php";
require_once "includes/auth.php";

$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id <= 0) {
    header("Location: events.php");
    exit();
}

// 1. Fetch Event Details from Database using Prepared Statement
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: events.php");
    exit();
}

$event = $result->fetch_assoc();
$page_title = $event['title'];

// 2. Check Student Registration Status if Logged In
$is_logged = is_student_logged_in();
$student_id = get_student_id();
$already_registered = false;
$msg_success = "";
$msg_error = "";

if ($is_logged) {
    $reg_check = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
    $reg_check->bind_param("ii", $student_id, $event_id);
    $reg_check->execute();
    $already_registered = ($reg_check->get_result()->num_rows > 0);
}

// 3. Handle Registration Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    if (!$is_logged) {
        $msg_error = "Please login to register for an event.";
    } elseif ($already_registered) {
        $msg_error = "You have already registered for this event.";
    } else {
        // Insert new registration record
        $insert_stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id, status) VALUES (?, ?, 'Registered')");
        $insert_stmt->bind_param("ii", $student_id, $event_id);
        
        if ($insert_stmt->execute()) {
            $already_registered = true;
            $msg_success = "Registration successful! You have secured your spot for this event.";
        } else {
            $msg_error = "Registration failed. Please try again or contact the event administrator.";
        }
    }
}

require_once "includes/header.php";
?>

<section class="section">
    <div class="container">
        <!-- Breadcrumb / Back Link -->
        <div style="margin-bottom: 24px;">
            <a href="events.php" style="color: var(--text-muted); font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px;">
                ← Back to All Events
            </a>
        </div>

        <!-- Success & Error Alerts -->
        <?php if ($msg_success): ?>
            <div class="alert alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><?php echo htmlspecialchars($msg_success); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($msg_error): ?>
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span><?php echo htmlspecialchars($msg_error); ?></span>
            </div>
        <?php endif; ?>

        <div class="event-details-grid">
            <!-- Left: Media & Event Description -->
            <div>
                <img src="assets/images/<?php echo htmlspecialchars($event['image'] ?: 'fest.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($event['title']); ?>" 
                     class="event-details-banner">

                <div class="details-box">
                    <span class="pill-badge" style="margin-bottom: 14px;">
                        <?php echo htmlspecialchars($event['category']); ?>
                    </span>
                    <h1 style="font-size: 2.2rem; margin-bottom: 20px;">
                        <?php echo htmlspecialchars($event['title']); ?>
                    </h1>

                    <h3>About This Event</h3>
                    <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

                    <h3 style="margin-top: 32px;">Rules & Participation Guidelines</h3>
                    <ul style="color: var(--text-muted); padding-left: 20px; line-height: 1.8; margin-bottom: 24px;">
                        <li>Open to all registered college students across all departments.</li>
                        <li>Please carry your valid college student identity card to the venue.</li>
                        <li>Reporting time is at least 15 minutes prior to the scheduled start time.</li>
                        <li>Certificate of participation will be provided to all attendees.</li>
                    </ul>
                </div>
            </div>

            <!-- Right: Event Quick Info & Registration Action Card -->
            <div class="details-sidebar-card">
                <h3 style="font-size: 1.4rem; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--border-color);">
                    Event Overview
                </h3>

                <div class="event-meta" style="font-size: 0.95rem; gap: 16px; margin-bottom: 28px;">
                    <div class="meta-row">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/></svg>
                        <div>
                            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Date</div>
                            <strong style="color: var(--text-main);"><?php echo date("l, F j, Y", strtotime($event['event_date'])); ?></strong>
                        </div>
                    </div>

                    <div class="meta-row">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/></svg>
                        <div>
                            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Time</div>
                            <strong style="color: var(--text-main);"><?php echo date("h:i A", strtotime($event['event_time'])); ?></strong>
                        </div>
                    </div>

                    <div class="meta-row">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                        <div>
                            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Location</div>
                            <strong style="color: var(--text-main);"><?php echo htmlspecialchars($event['location']); ?></strong>
                        </div>
                    </div>

                    <div class="meta-row">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                        <div>
                            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Fee</div>
                            <strong style="color: var(--accent-green);">FREE FOR ALL STUDENTS</strong>
                        </div>
                    </div>
                </div>

                <!-- Registration Action Button States -->
                <div style="padding-top: 20px; border-top: 1px solid var(--border-color);">
                    <?php if (!$is_logged): ?>
                        <div class="alert alert-info" style="margin-bottom: 16px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Please login to register for an event.</span>
                        </div>
                        <a href="login.php?redirect=event_details.php?id=<?php echo $event_id; ?>" class="btn btn-primary" style="width: 100%; padding: 14px;">
                            Sign In to Register
                        </a>
                        <div style="text-align: center; margin-top: 12px; font-size: 0.85rem; color: var(--text-dim);">
                            Don't have an account? <a href="register.php" style="color: var(--primary-pink);">Register here</a>
                        </div>

                    <?php elseif ($already_registered): ?>
                        <div class="alert alert-success" style="margin-bottom: 16px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>You have already registered for this event!</span>
                        </div>
                        <a href="student/my_events.php" class="btn btn-outline" style="width: 100%; padding: 14px;">
                            View in My Registered Events →
                        </a>

                    <?php else: ?>
                        <form method="POST" action="event_details.php?id=<?php echo $event_id; ?>">
                            <input type="hidden" name="action" value="register">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 1.05rem;">
                                Confirm & Register Now
                            </button>
                        </form>
                        <p style="color: var(--text-dim); font-size: 0.82rem; text-align: center; margin-top: 10px;">
                            Instant confirmation. You can view this anytime in your student dashboard.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once "includes/footer.php"; ?>
