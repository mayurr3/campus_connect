<?php
// ==========================================================
// Campus Connect — Delete College Event
// File: admin/delete_event.php
// Purpose: Deletes an event and cascades to its registrations
// ==========================================================

require_once "../config/database.php";
require_once "../includes/admin_auth.php";

require_admin_login();

$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id > 0) {
    // Delete event using prepared statement
    // Foreign key CASCADE will automatically delete related registrations safely
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
}

header("Location: events.php?deleted=1");
exit();
?>
