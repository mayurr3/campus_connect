<?php
// ==========================================================
// Campus Connect — Add New Event
// File: admin/add_event.php
// Purpose: Allows administrator to create and publish a new college event
// ==========================================================

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

require_admin_login();

$page_title = "Add New Event";
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $event_date  = sanitize($_POST['event_date'] ?? '');
    $event_time  = sanitize($_POST['event_time'] ?? '');
    $location    = sanitize($_POST['location'] ?? '');
    $category    = sanitize($_POST['category'] ?? 'Technical');
    $image       = sanitize($_POST['image'] ?? 'fest.jpg');

    // Handle optional image file upload if uploaded
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
        $error_msg = "Please fill in all required fields (Title, Date, Time, Location).";
    } else {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, event_time, location, category, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $description, $event_date, $event_time, $location, $category, $image);

        if ($stmt->execute()) {
            header("Location: events.php?created=1");
            exit();
        } else {
            $error_msg = "Database error: Could not create event. " . $conn->error;
        }
    }
}

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
                <h2>Add New College Event</h2>
                <p>Create and publish an upcoming campus event for students</p>
            </div>
        </div>

        <div style="max-width: 760px; margin: 0 auto;">
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg><?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="glass-card" style="padding: 40px;">
                <form method="POST" action="add_event.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label" for="title">Event Title *</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="e.g. National Hackathon 2026" required
                               value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="category">Category *</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="Technical">Technical</option>
                                <option value="Cultural">Cultural</option>
                                <option value="Sports">Sports</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Creative">Creative</option>
                                <option value="Academic">Academic</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="location">Venue / Location *</label>
                            <input type="text" id="location" name="location" class="form-control" placeholder="e.g. Auditorium / Lab 3" required
                                   value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="event_date">Event Date *</label>
                            <input type="date" id="event_date" name="event_date" class="form-control" required
                                   value="<?php echo isset($_POST['event_date']) ? htmlspecialchars($_POST['event_date']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="event_time">Event Time *</label>
                            <input type="time" id="event_time" name="event_time" class="form-control" required
                                   value="<?php echo isset($_POST['event_time']) ? htmlspecialchars($_POST['event_time']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Event Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5" placeholder="Detailed description of rules, highlights, and schedule..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="image">Preset Banner Image</label>
                            <select id="image" name="image" class="form-control">
                                <option value="fest.jpg">Cultural Fest Banner (fest.jpg)</option>
                                <option value="coding.jpg" selected>Coding Battle Banner (coding.jpg)</option>
                                <option value="techtalk.jpg">Tech Talk Banner (techtalk.jpg)</option>
                                <option value="sports.jpg">Sports Day Banner (sports.jpg)</option>
                                <option value="photography.jpg">Photography Banner (photography.jpg)</option>
                                <option value="hackathon.jpg">Hackathon Banner (hackathon.jpg)</option>
                                <option value="freshers.jpg">Freshers Party Banner (freshers.jpg)</option>
                                <option value="quiz.jpg">Quiz Banner (quiz.jpg)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="image_file">Or Upload Custom Banner</label>
                            <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">
                        Publish Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once "footer.php"; ?>
