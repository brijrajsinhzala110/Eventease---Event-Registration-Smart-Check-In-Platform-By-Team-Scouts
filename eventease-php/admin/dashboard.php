<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('ADMIN');

// Get some quick stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_events = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$total_regs = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Admin Dashboard</h1>
            <p class="text-muted">Welcome back, System Administrator.</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100 rounded-4 shadow-sm hover-card border-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold tracking-wide mb-1">Total Users</h6>
                        <h2 class="display-5 fw-bolder mb-0"><?= number_format($total_users) ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100 rounded-4 shadow-sm hover-card border-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold tracking-wide mb-1">Total Events</h6>
                        <h2 class="display-5 fw-bolder mb-0"><?= number_format($total_events) ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-calendar-check fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white h-100 rounded-4 shadow-sm hover-card border-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold tracking-wide mb-1">Total Tickets</h6>
                        <h2 class="display-5 fw-bolder mb-0"><?= number_format($total_regs) ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-ticket-perforated fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <h4 class="fw-bold mb-3">Management Hub</h4>
    <div class="row g-4">
        <div class="col-md-6">
            <a href="users.php" class="text-decoration-none text-dark">
                <div class="card h-100 hover-card rounded-4 p-3 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-person-gear fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Manage Users</h5>
                            <p class="text-muted mb-0">View, edit, or remove registered users and roles.</p>
                        </div>
                        <i class="bi bi-chevron-right ms-auto fs-4 text-muted"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="events.php" class="text-decoration-none text-dark">
                <div class="card h-100 hover-card rounded-4 p-3 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center me-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-calendar2-week fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Manage Events</h5>
                            <p class="text-muted mb-0">Oversee all platform events, capacities, and settings.</p>
                        </div>
                        <i class="bi bi-chevron-right ms-auto fs-4 text-muted"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
