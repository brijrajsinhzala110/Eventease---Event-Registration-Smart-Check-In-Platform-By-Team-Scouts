<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

require_role('STUDENT');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('events.php');
}

$event_id = (int)$_GET['id'];

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $_SESSION['error'] = "Event not found.";
    redirect('events.php');
}

// Check if user is already registered
$stmt = $pdo->prepare("SELECT status FROM registrations WHERE user_id = ? AND event_id = ?");
$stmt->execute([$_SESSION['user_id'], $event_id]);
$registration = $stmt->fetch(PDO::FETCH_ASSOC);

$page_title = h($event['title']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>
<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= h($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= h($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($event['image_url'])): ?>
                <img src="<?php echo strpos($event['image_url'], 'http') === 0 ? h($event['image_url']) : '/eventease-php/' . h($event['image_url']); ?>" class="img-fluid mb-4 rounded" alt="Event Image" style="max-height: 400px; width: 100%; object-fit: cover;">
            <?php endif; ?>
            
            <h1><?php echo h($event['title']); ?></h1>
            
            <div class="mt-4">
                <h5>Description</h5>
                <p><?php echo nl2br(h($event['description'])); ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-4 position-sticky top-0">
                <div class="card-body">
                    <h5 class="card-title">Event Information</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="bi bi-calendar"></i> <strong>Date & Time:</strong><br> <?php echo format_date($event['starts_at']); ?></li>
                        <li class="mb-2"><i class="bi bi-geo-alt"></i> <strong>Venue:</strong><br> <?php echo h($event['venue']); ?></li>
                        <li class="mb-2"><i class="bi bi-person"></i> <strong>Capacity:</strong><br> <?php echo h($event['capacity']); ?></li>
                        <li class="mb-2"><i class="bi bi-clock"></i> <strong>Registration Deadline:</strong><br> <?php echo format_date($event['registration_deadline']); ?></li>
                    </ul>
                    
                    <hr>
                    
                    <?php if ($registration): ?>
                        <div class="alert alert-info">
                            You are already <strong><?php echo h($registration['status']); ?></strong> for this event.
                        </div>
                        <a href="my-events.php" class="btn btn-secondary w-100 py-3 rounded-pill fw-bold fs-5">View My Tickets</a>
                    <?php elseif (strtotime($event['registration_deadline']) < time()): ?>
                        <div class="alert alert-warning">Registration for this event has closed.</div>
                    <?php else: ?>
                        <form action="register-event.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-5">Register Now</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
