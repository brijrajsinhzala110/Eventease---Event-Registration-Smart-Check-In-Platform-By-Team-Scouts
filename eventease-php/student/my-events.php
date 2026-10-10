<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

require_role('STUDENT');

$page_title = "My Tickets";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$stmt = $pdo->prepare("
    SELECT r.id as reg_id, r.status, r.ticket_code, r.checked_in_at, e.id as event_id, e.title, e.starts_at, e.venue
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
                <?php foreach ($registrations as $reg): 
                    $can_cancel = (!$reg['checked_in_at'] && strtotime($reg['starts_at']) > time() && $reg['status'] !== 'CANCELLED');
                    
                    $waitlist_text = '';
                    if ($reg['status'] === 'WAITLISTED') {
                        $w_stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ? AND status = 'WAITLISTED' AND created_at <= (SELECT created_at FROM registrations WHERE id = ?)");
                        $w_stmt->execute([$reg['event_id'], $reg['reg_id']]);
                        $pos = $w_stmt->fetchColumn();
                        $waitlist_text = " (Pos: $pos)";
                    }
                ?>
                    <tr>
                        <td><a href="event-details.php?id=<?php echo $reg['event_id']; ?>" class="text-decoration-none fw-bold"><?php echo h($reg['title']); ?></a></td>
                        <td><?php echo format_date($reg['starts_at']); ?></td>
                        <td><?php echo h($reg['venue']); ?></td>
                        <td>
                            <?php if ($reg['status'] === 'REGISTERED'): ?>
                                <span class="badge bg-success rounded-pill px-3">REGISTERED</span>
                            <?php elseif ($reg['status'] === 'WAITLISTED'): ?>
                                <span class="badge bg-warning rounded-pill px-3 text-dark">WAITLISTED<?php echo $waitlist_text; ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger rounded-pill px-3"><?php echo h($reg['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php if ($reg['status'] === 'REGISTERED'): ?>
                                    <a href="qr-code.php?id=<?php echo $reg['reg_id']; ?>" class="btn btn-sm btn-primary rounded-pill shadow-sm"><i class="bi bi-qr-code me-1"></i> Ticket</a>
                                <?php endif; ?>
                                
                                <?php if ($can_cancel): ?>
                                    <form action="cancel-registration.php" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to cancel this registration?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="reg_id" value="<?php echo $reg['reg_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm"><i class="bi bi-x-circle me-1"></i> Cancel</button>
                                    </form>
                                <?php endif; ?>
                            </div>
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
