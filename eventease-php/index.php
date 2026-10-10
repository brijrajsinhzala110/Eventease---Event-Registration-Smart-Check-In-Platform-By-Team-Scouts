<?php
session_start();
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    $role = $_SESSION['user_role'] ?? '';
    if ($role === 'STUDENT') {
        header("Location: /eventease-php/student/dashboard.php");
        exit;
    } elseif ($role === 'ORGANIZER') {
        header("Location: /eventease-php/organizer/dashboard.php");
        exit;
    } elseif ($role === 'ADMIN') {
        header("Location: /eventease-php/admin/dashboard.php");
        exit;
    }
}
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="flex-grow bg-light">
    <!-- Hero Section -->
    <div class="container text-center py-5 mt-5">
        <div class="mb-4">
            <span class="badge bg-primary rounded-pill px-3 py-2 mb-3 shadow-sm text-uppercase tracking-wide">v2.0 Now Live</span>
        </div>
        <h1 class="display-3 fw-bolder text-dark mb-4">
            <span class="d-block">Welcome to</span>
            <span class="d-block text-primary mt-2">EventEase</span>
        </h1>
        <p class="lead text-muted mx-auto mb-5" style="max-width: 600px; font-size: 1.25rem;">
            The ultimate platform to discover, register, and manage college events. Join thousands of students and never miss out on campus life.
        </p>
        <div class="d-flex justify-content-center gap-3 mb-5">
            <a href="/eventease-php/register.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow-sm">
                Get Started
            </a>
            <a href="/eventease-php/login.php" class="btn btn-outline-primary btn-lg px-5 py-3 rounded-pill fw-bold bg-white shadow-sm">
                Login
            </a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-5 mb-5">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card h-100 hover-card p-4 rounded-4">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-lightning-charge-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Instant Registration</h4>
                        <p class="text-muted">Browse upcoming events and secure your spot in seconds with just one click. No long forms.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 hover-card p-4 rounded-4">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-qr-code-scan fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Smart QR Tickets</h4>
                        <p class="text-muted">Get a secure digital QR ticket for every event. Scan at the door for lightning-fast check-ins.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 hover-card p-4 rounded-4">
                    <div class="card-body">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-graph-up-arrow fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Live Dashboards</h4>
                        <p class="text-muted">Organizers get powerful tools to track registrations, capacity, and live attendance metrics.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
