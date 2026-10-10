<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('STUDENT');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('my-events.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = "Invalid CSRF token.";
    redirect('my-events.php');
}

$reg_id = (int)($_POST['reg_id'] ?? 0);
$user_id = $_SESSION['user_id'];

try {
    $pdo->beginTransaction();

    // Lock registration and event for update
    $stmt = $pdo->prepare("
        SELECT r.*, e.starts_at, e.title 
        FROM registrations r 
        JOIN events e ON r.event_id = e.id 
        WHERE r.id = ? FOR UPDATE
    ");
    $stmt->execute([$reg_id]);
    $reg = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reg) {
        throw new Exception("Registration not found.");
    }
    if ($reg['user_id'] != $user_id) {
        throw new Exception("Unauthorized.");
    }
    if ($reg['status'] === 'CANCELLED') {
        throw new Exception("Already cancelled.");
    }
    if ($reg['checked_in_at']) {
        throw new Exception("Cannot cancel after check-in.");
    }
    if (strtotime($reg['starts_at']) <= time()) {
        throw new Exception("Cannot cancel after the event has started.");
    }

    $was_registered = ($reg['status'] === 'REGISTERED');
    $event_id = $reg['event_id'];

    // Cancel it
    $upd = $pdo->prepare("UPDATE registrations SET status = 'CANCELLED' WHERE id = ?");
    $upd->execute([$reg_id]);

    // Notify user
    $notif = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $notif->execute([$user_id, "Your registration for " . $reg['title'] . " has been cancelled."]);

    // Promote if needed
    if ($was_registered) {
        // Find earliest waitlisted
        $wait = $pdo->prepare("SELECT id, user_id FROM registrations WHERE event_id = ? AND status = 'WAITLISTED' ORDER BY created_at ASC LIMIT 1 FOR UPDATE");
        $wait->execute([$event_id]);
        $next = $wait->fetch(PDO::FETCH_ASSOC);

        if ($next) {
            $prom = $pdo->prepare("UPDATE registrations SET status = 'REGISTERED' WHERE id = ?");
            $prom->execute([$next['id']]);

            $notif->execute([$next['user_id'], "You have been promoted from the waitlist for " . $reg['title'] . "!"]);
        }
    }

    $pdo->commit();
    $_SESSION['success'] = "Registration cancelled successfully.";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error'] = $e->getMessage();
}

redirect('my-events.php');
