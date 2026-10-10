<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('STUDENT');

$page_title = "Student Dashboard";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Fetch upcoming registered events
$stmt = $pdo->prepare("
    SELECT e.id, e.title, e.starts_at, e.venue, r.status
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    WHERE r.user_id = ? AND e.starts_at >= CURDATE()
    ORDER BY e.starts_at ASC
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$upcoming_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch available upcoming events (not registered by user)
$stmt2 = $pdo->prepare("
    SELECT e.id, e.title, e.starts_at, e.venue 
    FROM events e 
    WHERE e.starts_at >= CURDATE() 
      AND e.id NOT IN (SELECT event_id FROM registrations WHERE user_id = ?)
    ORDER BY e.starts_at ASC 
    LIMIT 3
");
$stmt2->execute([$_SESSION['user_id']]);
$available_events = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container mt-4 mb-5">
    <h1>Welcome, <?php echo h($_SESSION['user_name']); ?>!</h1>
    
    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100 hover-card rounded-4 p-2">
                <div class="card-body">
                    <h5 class="card-title">Quick Links</h5>
                    <a href="events.php" class="btn btn-primary mb-2 rounded-pill fw-bold">Browse Events</a>
                    <br>
                    <a href="my-events.php" class="btn btn-secondary rounded-pill fw-bold">My Tickets</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 hover-card rounded-4 p-2">
                <div class="card-body">
                    <h5 class="card-title">My Registered Events</h5>
                    <?php if (empty($upcoming_events)): ?>
                        <p class="text-muted">You haven't registered for any upcoming events yet.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($upcoming_events as $event): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo h($event['title']); ?></strong><br>
                                        <small><?php echo format_date($event['starts_at']); ?> | <?php echo h($event['venue']); ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo $event['status'] === 'REGISTERED' ? 'success' : 'warning'; ?>">
                                        <?php echo h($event['status']); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card hover-card rounded-4 p-2">
                <div class="card-body">
                    <h5 class="card-title">Featured Upcoming Events</h5>
                    <?php if (empty($available_events)): ?>
                        <p class="text-muted">No new upcoming events at the moment.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($available_events as $event): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo h($event['title']); ?></strong><br>
                                        <small><?php echo format_date($event['starts_at']); ?> | <?php echo h($event['venue']); ?></small>
                                    </div>
                                    <a href="event-details.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
