<?php
$role = $_SESSION['user_role'] ?? null;
$is_logged_in = isset($_SESSION['user_id']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid max-w-7xl">
    <a class="navbar-brand fw-bold" href="/eventease-php/">
      <i class="bi bi-calendar-event me-2"></i>EventEase
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($role === 'STUDENT'): ?>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/student/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/student/events.php">Browse Events</a></li>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/student/my-events.php">My Tickets</a></li>
        <?php elseif ($role === 'ORGANIZER'): ?>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/organizer/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/organizer/manage-events.php">Manage Events</a></li>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/organizer/scanner.php">Scanner</a></li>
        <?php elseif ($role === 'ADMIN'): ?>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/admin/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/admin/users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="/eventease-php/admin/events.php">Events</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center">
        <?php if ($is_logged_in): ?>
            <span class="text-light me-3 fw-medium"><i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></span>
            <a href="/eventease-php/logout.php" class="btn btn-light btn-sm text-primary fw-bold">Logout</a>
        <?php else: ?>
            <a href="/eventease-php/login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="/eventease-php/register.php" class="btn btn-light text-primary fw-bold">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
