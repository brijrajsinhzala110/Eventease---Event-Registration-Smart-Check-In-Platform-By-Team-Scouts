<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('STUDENT');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('my-events.php');
}

$reg_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT r.*, e.title, e.starts_at, e.venue, u.name as student_name
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    JOIN users u ON r.user_id = u.id
    WHERE r.id = ? AND r.user_id = ? AND r.status = 'REGISTERED'
");
$stmt->execute([$reg_id, $_SESSION['user_id']]);
$reg = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reg) {
    $_SESSION['error'] = "Ticket not found or not eligible for QR code.";
    redirect('my-events.php');
}

// Generate the plain text note for the QR Code
$qr_text = "=== EVENTEASE TICKET ===\n";
$qr_text .= "Ticket Code: " . $reg['ticket_code'] . "\n";
$qr_text .= "Attendee: " . $reg['student_name'] . "\n";
$qr_text .= "Event: " . $reg['title'] . "\n";
$qr_text .= "Date: " . format_date($reg['starts_at']) . "\n";
$qr_text .= "Venue: " . $reg['venue'] . "\n";
$qr_text .= "Status: " . $reg['status'];

$page_title = "Ticket QR Code";
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white border-0 py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-ticket-perforated me-2"></i>Event Ticket</h4>
                </div>
                <div class="card-body py-5">
                    <h3 class="card-title fw-bold text-dark"><?php echo h($reg['title']); ?></h3>
                    <p class="text-muted mb-4"><i class="bi bi-calendar-event me-1"></i> <?php echo format_date($reg['starts_at']); ?></p>
                    
                    <div id="qrcode" class="d-flex justify-content-center mb-4 p-3 bg-white rounded-4 shadow-sm mx-auto" style="width: 232px; height: 232px;"></div>
                    
                    <p class="lead fw-bold text-primary tracking-wide">Code: <?php echo h($reg['ticket_code']); ?></p>
                    <p class="small text-muted mb-0">Scan with any phone camera to view ticket details offline.</p>
                </div>
                <div class="card-footer bg-light py-3 border-0 rounded-bottom-4">
                    <button class="btn btn-secondary rounded-pill px-4 fw-medium shadow-sm" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print Ticket</button>
                    <a href="my-events.php" class="btn btn-outline-primary rounded-pill px-4 ms-2 fw-medium shadow-sm"><i class="bi bi-arrow-left me-2"></i>Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: <?php echo json_encode($qr_text, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        width: 200,
        height: 200,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.L
    });
</script>

<?php require_once '../includes/footer.php'; ?>
