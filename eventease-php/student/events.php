<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('STUDENT');

$page_title = "Browse Events";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Fetch all upcoming events with registration counts
$stmt = $pdo->query("
    SELECT e.*, 
           (SELECT COUNT(*) FROM registrations WHERE event_id = e.id AND status = 'REGISTERED') as current_count
    FROM events e
    WHERE e.starts_at >= CURDATE()
    ORDER BY e.starts_at ASC
");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container mt-4">
    <h1>Browse Events</h1>
    
    <div class="row mt-4">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 hover-card rounded-4 overflow-hidden">
                    <?php if (!empty($event['image_url'])): ?>
                        <img src="<?php echo strpos($event['image_url'], 'http') === 0 ? h($event['image_url']) : '/eventease-php/' . h($event['image_url']); ?>" class="card-img-top" alt="Event Image" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span>No Image</span>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo h($event['title']); ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-calendar"></i> <?php echo format_date($event['starts_at']); ?><br>
                            <i class="bi bi-geo-alt"></i> <?php echo h($event['venue']); ?>
                        </p>
                        <p class="card-text">
                            <strong>Capacity:</strong> <?php echo $event['current_count']; ?> / <?php echo h($event['capacity']); ?>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="event-details.php?id=<?php echo $event['id']; ?>" class="btn btn-primary w-100 rounded-pill fw-bold">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($events)): ?>
            <div class="col-12">
                <p>No upcoming events available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
