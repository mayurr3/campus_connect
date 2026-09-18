<?php
// ==========================================================
// Campus Connect — Browse Events Catalog
// File: events.php
// Purpose: Displays all college events with live search and filtering
// ==========================================================

$page_title = "Browse Events";
require_once "config/database.php";
require_once "includes/header.php";

// Fetch all events from database ordered by event_date
$events = [];
$query = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>

<section class="section">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <span class="section-subtitle">Event Directory</span>
            <h1 class="section-title">Explore Campus Events</h1>
            <p class="section-desc">
                Find upcoming workshops, hackathons, sports tournaments, and cultural gatherings. Filter by category or search by event name.
            </p>
        </div>

        <!-- Search & Filter Controls -->
        <div class="event-filters glass-card" style="padding: 20px 24px; margin-bottom: 36px;">
            <!-- Live Search Bar -->
            <div class="search-box">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="eventSearch" placeholder="Search event by name..." autocomplete="off">
            </div>

            <!-- Category Filter Buttons -->
            <div class="category-pills">
                <button type="button" class="cat-btn active" data-category="all">All</button>
                <button type="button" class="cat-btn" data-category="Technical">Technical</button>
                <button type="button" class="cat-btn" data-category="Cultural">Cultural</button>
                <button type="button" class="cat-btn" data-category="Sports">Sports</button>
                <button type="button" class="cat-btn" data-category="Workshop">Workshop</button>
                <button type="button" class="cat-btn" data-category="Creative">Creative</button>
                <button type="button" class="cat-btn" data-category="Academic">Academic</button>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="events-grid" id="eventsContainer">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card" 
                         data-title="<?php echo htmlspecialchars($event['title']); ?>" 
                         data-category="<?php echo htmlspecialchars($event['category']); ?>">
                        
                        <div class="event-img-wrap">
                            <span class="event-cat-tag">
                                <?php echo htmlspecialchars($event['category']); ?>
                            </span>
                            <img src="assets/images/<?php echo htmlspecialchars($event['image'] ?: 'fest.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>"
                                 loading="lazy">
                        </div>

                        <div class="event-card-content">
                            <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>

                            <div class="event-meta">
                                <div class="meta-row">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/></svg>
                                    <span><?php echo date("D, d M Y", strtotime($event['event_date'])); ?></span>
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
                                    View Details & Register →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <p style="color: var(--text-muted);">No events found in the database.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- No search matches message placeholder -->
        <div id="noEventsMessage" class="glass-card" style="display: none; text-align: center; padding: 50px; margin-top: 24px;">
            <div style="margin-bottom: 16px; color: var(--primary-pink);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <h3 style="font-size: 1.4rem; margin-bottom: 8px;">No matching events found</h3>
            <p style="color: var(--text-muted);">Try adjusting your search keywords or switching category filters.</p>
        </div>
    </div>
</section>

<?php require_once "includes/footer.php"; ?>
