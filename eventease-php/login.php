<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!verify_csrf_token($csrf_token)) {
        $error = "Invalid CSRF token.";
    } elseif (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            login_user($user['id'], $user['role'], $user['name']);
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<div class="flex-grow d-flex align-items-center justify-content-center bg-light py-5">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 450px; width: 100%;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg>
                </div>
                <h2 class="fw-bold">Welcome Back</h2>
                <p class="text-muted">Please sign in to your account</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['register_success'])): ?>
                <div class="alert alert-success rounded-3" role="alert">
                    <?php echo htmlspecialchars($_SESSION['register_success']); unset($_SESSION['register_success']); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                
                <div class="form-floating mb-3">
                    <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="name@example.com" required>
                    <label for="email">Email address</label>
                </div>
                
                <div class="form-floating mb-4">
                    <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm" type="submit" style="font-size: 1.1rem;">
                    Sign In
                </button>
            </form>
            
            <div class="text-center mt-4">
                <p class="mb-0 text-muted">Don't have an account? <a href="register.php" class="text-primary fw-bold text-decoration-none">Register here</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
