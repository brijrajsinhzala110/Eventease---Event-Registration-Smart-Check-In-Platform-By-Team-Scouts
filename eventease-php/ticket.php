<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$code = $_GET['code'] ?? '';

if (empty($code)) {
    die("Invalid ticket code.");
}

// Fetch registration and event details
$stmt = $pdo->prepare("
    SELECT r.status, r.ticket_code, r.created_at as registered_on, r.checked_in_at,
           e.title, e.description, e.venue, e.starts_at, e.ends_at, e.image_url,
           u.name as student_name, u.email as student_email
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    JOIN users u ON r.user_id = u.id
    WHERE r.ticket_code = ?
");
$stmt->execute([$code]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Ticket not found.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket: <?php echo h($ticket['title']); ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootswatch Zephyr Theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/zephyr/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .ticket-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: #fff;
        }
        .ticket-header {
            background-color: #6610f2;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .ticket-body { padding: 2rem; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 0.75rem 0;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #6c757d; font-weight: 500; }
        .detail-value { font-weight: 600; color: #212529; text-align: right; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-h-screen py-5">
    <div class="container" style="max-width: 600px;">
        <div class="ticket-card">
            <?php if (!empty($ticket['image_url'])): ?>
                <?php $img_src = strpos($ticket['image_url'], 'http') === 0 ? h($ticket['image_url']) : '/eventease-php/' . h($ticket['image_url']); ?>
                <div style="height: 200px; overflow: hidden;">
                    <img src="<?php echo $img_src; ?>" class="w-100 h-100" style="object-fit: cover;" alt="Event Image">
                </div>
            <?php else: ?>
                <div class="ticket-header">
                    <i class="bi bi-ticket-perforated" style="font-size: 3rem;"></i>
                    <h2 class="mt-2 fw-bold">Event Ticket</h2>
                </div>
            <?php endif; ?>
            
            <div class="ticket-body">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1"><?php echo h($ticket['title']); ?></h3>
                    <p class="text-muted mb-0"><i class="bi bi-geo-alt-fill me-1"></i> <?php echo h($ticket['venue']); ?></p>
                </div>
                
                <div class="mb-4">
                    <div class="detail-row">
                        <span class="detail-label">Attendee Name</span>
                        <span class="detail-value"><?php echo h($ticket['student_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ticket Code</span>
                        <span class="detail-value text-primary"><?php echo h($ticket['ticket_code']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">
                            <?php if ($ticket['status'] === 'REGISTERED'): ?>
                                <span class="badge bg-success rounded-pill px-3">REGISTERED</span>
                            <?php else: ?>
                                <span class="badge bg-warning rounded-pill px-3"><?php echo h($ticket['status']); ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Event Date & Time</span>
                        <span class="detail-value"><?php echo format_date($ticket['starts_at']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Registered On</span>
                        <span class="detail-value"><?php echo format_date($ticket['registered_on']); ?></span>
                    </div>
                </div>

                <div class="text-center">
                    <?php if ($ticket['checked_in_at']): ?>
                        <div class="alert alert-warning mb-0 rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i> Ticket was checked in at <?php echo format_date($ticket['checked_in_at']); ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success mb-0 rounded-3">
                            <i class="bi bi-shield-check me-2"></i> Ticket is valid for entry
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-light text-center text-muted py-3 border-0">
                <small>Powered by EventEase</small>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="/eventease-php/" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-house me-2"></i>Return Home</a>
        </div>
    </div>
</body>
</html>
