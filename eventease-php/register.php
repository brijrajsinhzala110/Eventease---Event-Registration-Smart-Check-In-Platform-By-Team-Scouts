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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $student_id = trim($_POST['student_id'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $role = 'STUDENT'; // Only allow STUDENT role for normal registration
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!verify_csrf_token($csrf_token)) {
        $error = "Invalid CSRF token.";
    } elseif (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, student_id, department, role) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed_password, $student_id, $department, $role])) {
                $_SESSION['register_success'] = "Registration successful. Please login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<div class="flex-grow d-flex align-items-center justify-content-center bg-light py-5">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 500px; width: 100%;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                      <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                      <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                </div>
                <h2 class="fw-bold">Create an account</h2>
                <p class="text-muted">Join EventEase today</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="role" value="STUDENT">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="name" name="name" placeholder="Full Name" required>
                    <label for="name">Full Name</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="name@example.com" required>
                    <label for="email">Email address</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3" id="student_id" name="student_id" placeholder="Student ID (Optional)">
                            <label for="student_id">Student ID</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3" id="department" name="department" placeholder="Department (Optional)">
                            <label for="department">Department</label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm" type="submit" style="font-size: 1.1rem;">
                    Register
                </button>
            </form>
            
            <div class="text-center mt-4">
                <p class="mb-0 text-muted">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Sign in here</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
