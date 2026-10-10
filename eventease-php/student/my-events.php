<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('STUDENT');

$page_title = "My Tickets";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$stmt = $pdo->prepare("
    SELECT r.id as reg_id, r.status, r.ticket_code, e.id as event_id, e.title, e.starts_at, e.venue
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    WHERE r.user_id = ?
    ORDER BY e.starts_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <h1>My Tickets</h1>
    
    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $reg): ?>
                    <tr>
                        <td><a href="event-details.php?id=<?php echo $reg['event_id']; ?>"><?php echo h($reg['title']); ?></a></td>
                        <td><?php echo format_date($reg['starts_at']); ?></td>
                        <td><?php echo h($reg['venue']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $reg['status'] === 'REGISTERED' ? 'success' : 'warning'; ?>">
                                <?php echo h($reg['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($reg['status'] === 'REGISTERED'): ?>
                                <a href="qr-code.php?id=<?php echo $reg['reg_id']; ?>" class="btn btn-sm btn-outline-primary">View QR Code</a>
                            <?php else: ?>
                                <span class="text-muted">Waitlisted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($registrations)): ?>
                    <tr>
                        <td colspan="5" class="text-center">You haven't registered for any events yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
