<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_roles(['ORGANIZER', 'ADMIN']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $venue = $_POST['venue'];
    $starts_at = $_POST['starts_at'];
    $ends_at = $_POST['ends_at'];
    $capacity = $_POST['capacity'];
    $registration_deadline = $_POST['registration_deadline'];
    
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image_url = 'uploads/' . $filename;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO events (title, description, venue, starts_at, ends_at, capacity, registration_deadline, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $venue, $starts_at, $ends_at, $capacity, $registration_deadline, $image_url]);
    
    header('Location: manage-events.php');
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Create Event</h1>
    <form method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-4 rounded-4 shadow-sm">
        <div><label class="form-label fw-bold mt-3">Title</label><input type="text" name="title" required class="form-control"></div>
        <div><label class="form-label fw-bold mt-3">Description</label><textarea name="description" required class="form-control"></textarea></div>
        <div><label class="form-label fw-bold mt-3">Venue</label><input type="text" name="venue" required class="form-control"></div>
        <div><label class="form-label fw-bold mt-3">Starts At</label><input type="datetime-local" name="starts_at" required class="form-control"></div>
        <div><label class="form-label fw-bold mt-3">Ends At</label><input type="datetime-local" name="ends_at" required class="form-control"></div>
        <div><label class="form-label fw-bold mt-3">Capacity</label><input type="number" name="capacity" required class="form-control"></div>
        <div><label class="form-label fw-bold mt-3">Registration Deadline</label><input type="datetime-local" name="registration_deadline" required class="form-control"></div>
        <div><label class="form-label fw-bold mt-3">Event Image</label><input type="file" name="image" accept="image/*" class="form-control"></div>
        <button type="submit" class="btn btn-primary w-100 py-2 mt-4 fw-bold">Create Event</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
