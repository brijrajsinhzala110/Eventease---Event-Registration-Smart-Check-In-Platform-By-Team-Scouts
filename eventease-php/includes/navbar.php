<?php
$role = $_SESSION['user_role'] ?? null;
$is_logged_in = isset($_SESSION['user_id']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid max-w-7xl">
    <a class="navbar-brand fw-bolder d-flex align-items-center" href="/eventease-php/" style="letter-spacing: -0.05em; font-size: 1.5rem;">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 512 512" class="me-2" style="fill: white;">
        <rect width="512" height="512" rx="112" fill="white" fill-opacity="0.1"/>
        <path d="M144 144c-17.7 0-32 14.3-32 32v160c0 17.7 14.3 32 32 32h224c17.7 0 32-14.3 32-32V176c0-17.7-14.3-32-32-32H144zm0 32h224v160H144V176zm32 32v32h160v-32H176zm0 64v32h160v-32H176z"/>
      </svg>
      EventEase
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
