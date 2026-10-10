<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('ADMIN');

$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Manage Events</h1>
        <a href="../organizer/create-event.php" class="btn btn-primary fw-bold shadow-sm">
            + Add New Event
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-hover align-middle bg-white shadow-sm rounded-3">
            <thead>
                <tr class="table-light">
                    <th >ID</th>
                    <th >Title</th>
                    <th >Starts At</th>
                    <th >Capacity</th>
                    <th >Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td ><?= htmlspecialchars($event['id']) ?></td>
                    <td class=" font-medium"><?= htmlspecialchars($event['title']) ?></td>
                    <td ><?= format_date($event['starts_at']) ?></td>
                    <td class=" text-center"><?= htmlspecialchars($event['capacity']) ?></td>
                    <td class=" text-center">
                        <a href="../organizer/registrations.php?id=<?= $event['id'] ?>" class="btn btn-success btn-sm fw-medium shadow-sm">
                            View Registrations
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
