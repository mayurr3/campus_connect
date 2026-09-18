<?php
// ==========================================================
// Campus Connect — Edit College Event
// File: admin/edit_event.php
// Purpose: Allows administrator to modify existing event details
// ==========================================================

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

require_admin_login();

$page_title = "Edit Event";
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id <= 0) {
    header("Location: events.php");
    exit();
}

$error_msg = "";

// 1. Handle Update Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $event_date  = sanitize($_POST['event_date'] ?? '');
    $event_time  = sanitize($_POST['event_time'] ?? '');
    $location    = sanitize($_POST['location'] ?? '');
    $category    = sanitize($_POST['category'] ?? 'Technical');
    $image       = sanitize($_POST['image'] ?? 'fest.jpg');

    // Handle optional image file upload if provided
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES['image_file']['name']));
        $target_dir = "../assets/images/";
        $target_file = $target_dir . time() . "_" . $file_name;
        
        $image_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($image_ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
            if (move_uploaded_file($file_tmp, $target_file)) {
                $image = basename($target_file);
            }
        }
    }

    if (empty($title) || empty($event_date) || empty($event_time) || empty($location)) {
        $error_msg = "Please fill in all required fields.";
    } else {
        $update_stmt = $conn->prepare("
            UPDATE events 
            SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, category = ?, image = ? 
            WHERE id = ?
        ");
        $update_stmt->bind_param("sssssssi", $title, $description, $event_date, $event_time, $location, $category, $image, $event_id);

        if ($update_stmt->execute()) {
            header("Location: events.php?updated=1");
            exit();
        } else {
            $error_msg = "Failed to update event: " . $conn->error;
        }
    }
}

// 2. Fetch Existing Event Record from Database
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    header("Location: events.php");
    exit();
}

$event = $res->fetch_assoc();

require_once "header.php";
?>

<section class="dashboard-wrapper">
    <div class="container">
        <!-- Breadcrumb & Header -->
        <div style="margin-bottom: 24px;">
            <a href="events.php" style="color: var(--text-muted); font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px;">
                ← Back to Manage Events
            </a>
        </div>

        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Edit Event #<?php echo $event['id']; ?></h2>
                <p>Update schedule, location, or details for <?php echo htmlspecialchars($event['title']); ?></p>
            </div>
        </div>

        <div style="max-width: 760px; margin: 0 auto;">
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg><?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="glass-card" style="padding: 40px;">
                <form method="POST" action="edit_event.php?id=<?php echo $event_id; ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label" for="title">Event Title *</label>
                        <input type="text" id="title" name="title" class="form-control" required
                               value="<?php echo htmlspecialchars($event['title']); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="category">Category *</label>
                            <select id="category" name="category" class="form-control" required>
                                <?php
                                $categories = ['Technical', 'Cultural', 'Sports', 'Workshop', 'Creative', 'Academic'];
                                foreach ($categories as $cat) {
                                    $selected = ($event['category'] === $cat) ? 'selected' : '';
                                    echo "<option value=\"$cat\" $selected>$cat</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="location">Venue / Location *</label>
                            <input type="text" id="location" name="location" class="form-control" required
                                   value="<?php echo htmlspecialchars($event['location']); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="event_date">Event Date *</label>
                            <input type="date" id="event_date" name="event_date" class="form-control" required
                                   value="<?php echo htmlspecialchars($event['event_date']); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="event_time">Event Time *</label>
                            <input type="time" id="event_time" name="event_time" class="form-control" required
                                   value="<?php echo htmlspecialchars($event['event_time']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Event Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="image">Current / Preset Banner</label>
                            <select id="image" name="image" class="form-control">
                                <?php
                                $presets = [
                                    'fest.jpg' => 'Cultural Fest Banner (fest.jpg)',
                                    'coding.jpg' => 'Coding Battle Banner (coding.jpg)',
                                    'techtalk.jpg' => 'Tech Talk Banner (techtalk.jpg)',
                                    'sports.jpg' => 'Sports Day Banner (sports.jpg)',
                                    'photography.jpg' => 'Photography Banner (photography.jpg)',
                                    'hackathon.jpg' => 'Hackathon Banner (hackathon.jpg)',
                                    'freshers.jpg' => 'Freshers Party Banner (freshers.jpg)',
                                    'quiz.jpg' => 'Quiz Banner (quiz.jpg)'
                                ];
                                foreach ($presets as $file => $label) {
                                    $sel = ($event['image'] === $file) ? 'selected' : '';
                                    echo "<option value=\"$file\" $sel>$label</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="image_file">Replace Banner Image (Optional)</label>
                            <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once "footer.php"; ?>
