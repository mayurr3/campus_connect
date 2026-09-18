<?php
// ==========================================================
// Campus Connect — Home Landing Page
// File: index.php
// Purpose: Main portal landing page featuring dynamic events & metrics
// Design: Figma-inspired Dark Mode & Neon Gradient UI
// ==========================================================

$page_title = "Home";
require_once "config/database.php";
require_once "includes/header.php";

// Fetch Live Statistics from MySQL Database for the Stats Ticker
$students_count = 0;
$events_count = 0;
$registrations_count = 0;

$stat_query = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($stat_query) { $students_count = $stat_query->fetch_assoc()['total']; }

$stat_query = $conn->query("SELECT COUNT(*) AS total FROM events");
if ($stat_query) { $events_count = $stat_query->fetch_assoc()['total']; }

$stat_query = $conn->query("SELECT COUNT(*) AS total FROM registrations");
if ($stat_query) { $registrations_count = $stat_query->fetch_assoc()['total']; }

// Fetch the nearest upcoming event to display in the Hero Right Feature Card
$featured_event = null;
$feat_result = $conn->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 1");
if ($feat_result && $feat_result->num_rows > 0) {
    $featured_event = $feat_result->fetch_assoc();
}

// Fetch up to 6 upcoming events for the "Featured Events" grid
$upcoming_events = [];
$events_result = $conn->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 6");
if ($events_result) {
    while ($row = $events_result->fetch_assoc()) {
        $upcoming_events[] = $row;
    }
}
?>

<!-- =======================================================
     1. HERO SECTION (FIGMA UI REFERENCE)
     ======================================================= -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <!-- Hero Left: Headline & Actions -->
            <div class="hero-content">
                <div class="pill-badge">
                    <span class="pill-dot"></span>
                    Connect • Participate • Celebrate
                </div>

                <h1 class="hero-title">
                    YOUR CAMPUS
                    <span class="highlight">ALIVE.</span>
                </h1>

                <p class="hero-desc">
                    Discover and participate in exciting events happening across your campus. 
                    From high-octane coding competitions to vibrant cultural fests, never miss out on what shapes your college journey.
                </p>

                <div class="hero-buttons">
                    <?php if ($is_student): ?>
                        <a href="student/dashboard.php" class="btn btn-primary">
                            My Dashboard
                        </a>
                        <a href="events.php" class="btn btn-outline">
                            Explore Events
                        </a>
                    <?php else: ?>
                        <a href="events.php" class="btn btn-primary">
                            Explore Events
                        </a>
                        <a href="about.php" class="btn btn-outline">
                            Learn More
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Dynamic Stats Ticker -->
                <div class="stats-ticker">
                    <div class="stat-item">
                        <h4><?php echo number_format($students_count + 120); ?>+</h4>
                        <p>Students</p>
                    </div>
                    <div class="stat-item">
                        <h4><?php echo number_format($events_count); ?>+</h4>
                        <p>Live Events</p>
                    </div>
                    <div class="stat-item">
                        <h4><?php echo number_format($registrations_count + 85); ?>+</h4>
                        <p>Registrations</p>
                    </div>
                    <div class="stat-item">
                        <h4>100%</h4>
                        <p>Free Entry</p>
                    </div>
                </div>
            </div>

            <!-- Hero Right: Featured Event Card (Matching Figma Layout) -->
            <?php if ($featured_event): ?>
            <div class="hero-card">
                <div class="hero-card-img-wrapper">
                    <span class="live-badge"><span class="pill-dot" style="display:inline-block; width:6px; height:6px; margin-right:4px;"></span> FEATURED EVENT</span>
                    <img src="assets/images/<?php echo htmlspecialchars($featured_event['image'] ?: 'fest.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($featured_event['title']); ?>">
                </div>
                <div class="hero-card-body">
                    <span style="color: var(--accent-cyan); font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">
                        <?php echo htmlspecialchars($featured_event['category']); ?>
                    </span>
                    <h3><?php echo htmlspecialchars($featured_event['title']); ?></h3>
                    <p style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 6px;">
                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <?php echo htmlspecialchars($featured_event['location']); ?>
                        </span>
                        <span>•</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <?php echo date("d M Y", strtotime($featured_event['event_date'])); ?>
                        </span>
                    </p>
                    <div class="hero-card-footer">
                        <span style="color: var(--accent-yellow); font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php echo date("h:i A", strtotime($featured_event['event_time'])); ?>
                        </span>
                        <a href="event_details.php?id=<?php echo $featured_event['id']; ?>" class="btn btn-primary btn-sm">
                            View & Register →
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- =======================================================
     2. CATEGORIES OVERVIEW BAR
     ======================================================= -->
<section style="padding: 20px 0 40px; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
            <span style="font-family: var(--font-heading); font-size: 0.9rem; font-weight: 700; letter-spacing: 1px; color: var(--text-dim); text-transform: uppercase;">
                DISCOVER BY CATEGORY:
            </span>
            <div class="category-pills">
                <a href="events.php" class="cat-btn active">All Events</a>
                <a href="events.php" class="cat-btn">Cultural</a>
                <a href="events.php" class="cat-btn">Technical</a>
                <a href="events.php" class="cat-btn">Sports</a>
                <a href="events.php" class="cat-btn">Workshops</a>
                <a href="events.php" class="cat-btn">Academic</a>
            </div>
        </div>
    </div>
</section>

<!-- =======================================================
     3. UPCOMING EVENTS SHOWCASE GRID
     ======================================================= -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Upcoming Schedule</span>
            <h2 class="section-title">Campus Happenings</h2>
            <p class="section-desc">
                Browse through exciting competitions, seminars, workshops, and fests hosted by various college societies.
            </p>
        </div>

        <div class="events-grid">
            <?php if (!empty($upcoming_events)): ?>
                <?php foreach ($upcoming_events as $event): ?>
                    <div class="event-card">
                        <div class="event-img-wrap">
                            <span class="event-cat-tag">
                                <?php echo htmlspecialchars($event['category']); ?>
                            </span>
                            <img src="assets/images/<?php echo htmlspecialchars($event['image'] ?: 'fest.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>">
                        </div>
                        <div class="event-card-content">
                            <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                            
                            <div class="event-meta">
                                <div class="meta-row">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/></svg>
                                    <span><?php echo date("l, F j, Y", strtotime($event['event_date'])); ?></span>
                                </div>
                                <div class="meta-row">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/></svg>
                                    <span><?php echo date("h:i A", strtotime($event['event_time'])); ?></span>
                                </div>
                                <div class="meta-row">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                                    <span><?php echo htmlspecialchars($event['location']); ?></span>
                                </div>
                            </div>

                            <p class="event-snippet">
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>

                            <div class="event-card-action">
                                <a href="event_details.php?id=<?php echo $event['id']; ?>" class="btn btn-primary" style="width: 100%;">
                                    View Details & Register
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px;" class="glass-card">
                    <p style="color: var(--text-muted);">No events found in the database yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 48px;">
            <a href="events.php" class="btn btn-outline" style="padding: 14px 36px; font-size: 1.05rem;">
                Browse All Campus Events →
            </a>
        </div>
    </div>
</section>

<!-- =======================================================
     4. CALL TO ACTION BANNER
     ======================================================= -->
<section style="padding: 60px 0 100px;">
    <div class="container">
        <div class="glass-card" style="padding: 60px 40px; text-align: center; position: relative; overflow: hidden; border: 1px solid rgba(121, 40, 202, 0.4);">
            <div style="position: absolute; top: -50px; left: 50%; transform: translateX(-50%); width: 300px; height: 100px; background: var(--primary-gradient); filter: blur(60px); opacity: 0.3; pointer-events: none;"></div>
            
            <?php if ($is_student): ?>
                <h2 style="font-size: 2.6rem; margin-bottom: 16px;">
                    Welcome Back, <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Student'); ?></span>!
                </h2>
                <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 32px; font-size: 1.1rem;">
                    You are logged in to your student portal. Discover new campus events, monitor your registrations, or manage your student profile.
                </p>
                <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                    <a href="student/dashboard.php" class="btn btn-primary" style="padding: 14px 36px; font-size: 1rem;">
                        Go to My Dashboard
                    </a>
                    <a href="student/my_events.php" class="btn btn-outline" style="padding: 14px 36px; font-size: 1rem;">
                        View My Registered Events
                    </a>
                </div>
            <?php else: ?>
                <h2 style="font-size: 2.6rem; margin-bottom: 16px;">
                    Ready to Experience Campus Life?
                </h2>
                <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 32px; font-size: 1.1rem;">
                    Create your free student account now to register for events, download passes, and track all your campus participations in one place.
                </p>
                <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                    <a href="register.php" class="btn btn-primary" style="padding: 14px 36px; font-size: 1rem;">
                        Sign Up as Student
                    </a>
                    <a href="login.php" class="btn btn-outline" style="padding: 14px 36px; font-size: 1rem;">
                        Existing Student Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once "includes/footer.php"; ?>
