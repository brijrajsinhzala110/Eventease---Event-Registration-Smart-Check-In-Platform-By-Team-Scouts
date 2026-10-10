<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_roles(['ORGANIZER', 'ADMIN']);

$event_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT r.*, u.name, u.email FROM registrations r JOIN users u ON r.user_id = u.id WHERE r.event_id = ?");
$stmt->execute([$event_id]);
$registrations = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Registrations for Event #<?= htmlspecialchars($event_id) ?></h1>
    <table class="table table-hover align-middle bg-white shadow-sm rounded-3">
        <thead>
            <tr>
                <th >Name</th>
                <th >Email</th>
                <th >Status</th>
                <th >Ticket Code</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registrations as $reg): ?>
            <tr>
                <td ><?= htmlspecialchars($reg['name']) ?></td>
                <td ><?= htmlspecialchars($reg['email']) ?></td>
                <td ><?= htmlspecialchars($reg['status']) ?></td>
                <td ><?= htmlspecialchars($reg['ticket_code']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
