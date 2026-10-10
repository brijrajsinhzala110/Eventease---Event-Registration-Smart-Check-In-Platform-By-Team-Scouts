<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_roles(['ORGANIZER', 'ADMIN']);

include '../includes/header.php';
include '../includes/navbar.php';

$stmt = $pdo->prepare("SELECT COUNT(*) as total_events FROM events");
$stmt->execute();
$total_events = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_registrations FROM registrations");
$stmt->execute();
$total_registrations = $stmt->fetchColumn();
?>
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Organizer Dashboard</h1>
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-blue-100 p-4 rounded">
            <h2 class="text-lg font-bold">Total Events</h2>
            <p class="text-3xl"><?= htmlspecialchars($total_events) ?></p>
        </div>
        <div class="bg-green-100 p-4 rounded">
            <h2 class="text-lg font-bold">Total Registrations</h2>
            <p class="text-3xl"><?= htmlspecialchars($total_registrations) ?></p>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
