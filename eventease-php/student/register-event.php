<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

require_role('STUDENT');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Invalid request.";
        redirect('events.php');
    }
    
    $event_id = (int)($_POST['event_id'] ?? 0);
    $user_id = $_SESSION['user_id'];
    
    if (!$event_id) {
        redirect('events.php');
    }
    
    try {
        $pdo->beginTransaction();
        
        // Lock event row
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? FOR UPDATE");
        $stmt->execute([$event_id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$event) {
            throw new Exception("Event not found.");
        }
        
        if (strtotime($event['registration_deadline']) < time()) {
            throw new Exception("Registration deadline has passed.");
        }
        
        // Check if already registered
        $stmt = $pdo->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
        $stmt->execute([$user_id, $event_id]);
        if ($stmt->fetch()) {
            throw new Exception("You are already registered for this event.");
        }
        
        // Count existing registrations
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ? AND status = 'REGISTERED'");
        $stmt->execute([$event_id]);
        $current_count = $stmt->fetchColumn();
        
        $status = ($current_count < $event['capacity']) ? 'REGISTERED' : 'WAITLISTED';
        $ticket_code = generate_ticket_code();
        
        $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id, status, ticket_code) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $event_id, $status, $ticket_code]);
        
        $pdo->commit();
        
        $_SESSION['success'] = "Successfully $status for " . h($event['title']);
        redirect('my-events.php');
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
        redirect("event-details.php?id=$event_id");
    }
} else {
    redirect('events.php');
}
