<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// FIX 1: Use $_SESSION['user_role'] and allow 'STAFF'
if (!in_array($_SESSION['user_role'] ?? '', ['ORGANIZER', 'ADMIN', 'STAFF'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$event_id = $input['event_id'] ?? null;
$ticket_code = $input['ticket_code'] ?? null;

// Extract TKT code if it's embedded in a URL or a multi-line note
if ($ticket_code && preg_match('/(TKT-[A-Z0-9]+)/', $ticket_code, $matches)) {
    $ticket_code = $matches[1];
}

if (!$event_id || !$ticket_code) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing event_id or ticket_code']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Fetch registration (for FOR UPDATE to prevent race conditions during check-in if possible)
    $stmt = $pdo->prepare("SELECT id, status, checked_in_at FROM registrations WHERE event_id = ? AND ticket_code = ? FOR UPDATE");
    $stmt->execute([$event_id, $ticket_code]);
    $registration = $stmt->fetch();

    if (!$registration) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Registration not found']);
        exit;
    }

    if ($registration['status'] !== 'REGISTERED') {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Registration status is ' . $registration['status']]);
        exit;
    }

    if ($registration['checked_in_at'] !== null) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Already checked in']);
        exit;
    }

    // FIX 3: Atomic check-in update
    $checker_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("UPDATE registrations SET checked_in_at = NOW(), checked_in_by_id = ? WHERE id = ? AND checked_in_at IS NULL AND status = 'REGISTERED'");
    $stmt->execute([$checker_id, $registration['id']]);

    if ($stmt->rowCount() === 0) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Check-in failed or already checked in by another process.']);
        exit;
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Check-in successful']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error during check-in.']);
}
