<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_roles(['ORGANIZER', 'ADMIN']);

$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Manage Events</h1>
    <table class="table table-hover align-middle bg-white shadow-sm rounded-3">
        <thead>
            <tr>
                <th >Title</th>
                <th >Date</th>
                <th >Capacity</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td ><?= htmlspecialchars($event['title']) ?></td>
                <td ><?= htmlspecialchars($event['starts_at']) ?></td>
                <td ><?= htmlspecialchars($event['capacity']) ?></td>
                <td >
                    <a href="registrations.php?id=<?= $event['id'] ?>" class="text-blue-500">View Registrations</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
