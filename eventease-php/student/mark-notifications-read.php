<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

require_role('STUDENT');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

header("Location: dashboard.php");
exit;
