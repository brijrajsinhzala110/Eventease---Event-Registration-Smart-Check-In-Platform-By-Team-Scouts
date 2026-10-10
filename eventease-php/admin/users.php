<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('ADMIN');

$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Manage Users</h1>
    <table class="table table-hover align-middle bg-white shadow-sm rounded-3">
        <thead>
            <tr>
                <th >ID</th>
                <th >Name</th>
                <th >Email</th>
                <th >Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td ><?= htmlspecialchars($user['id']) ?></td>
                <td ><?= htmlspecialchars($user['name']) ?></td>
                <td ><?= htmlspecialchars($user['email']) ?></td>
                <td ><?= htmlspecialchars($user['role']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
