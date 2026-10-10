<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_roles(['ORGANIZER', 'ADMIN']);

$stmt = $pdo->prepare("SELECT e.title, 
    COUNT(r.id) as registered,
    SUM(CASE WHEN r.checked_in_at IS NOT NULL THEN 1 ELSE 0 END) as checked_in
    FROM events e 
    LEFT JOIN registrations r ON e.id = r.event_id 
    GROUP BY e.id, e.title");
$stmt->execute();
$stats = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Attendance Dashboard</h1>
    <table class="table table-hover align-middle bg-white shadow-sm rounded-3">
        <thead>
            <tr>
                <th >Event</th>
                <th >Registered</th>
                <th >Checked In</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats as $stat): ?>
            <tr>
                <td ><?= htmlspecialchars($stat['title']) ?></td>
                <td ><?= htmlspecialchars($stat['registered']) ?></td>
                <td ><?= htmlspecialchars($stat['checked_in']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
