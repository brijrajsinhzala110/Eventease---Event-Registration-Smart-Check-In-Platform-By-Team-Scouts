<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$client_id = $_ENV['GOOGLE_CLIENT_ID'] ?? '';
$client_secret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
$redirect_uri = $_ENV['GOOGLE_REDIRECT_URI'] ?? '';

if (empty($client_id)) {
    $_SESSION['error'] = "Google Login is not configured yet.";
    header("Location: login.php");
    exit;
}

if ($client_id === 'your-google-client-id') {
    if (isset($_GET['mock_email'])) {
        // DEVELOPER MODE BYPASS: Simulate a successful Google Login for local testing without real API keys
        $email = $_GET['mock_email'];
        $name = ucfirst(explode('@', $email)[0]) . ' (Google)';
        $google_id = 'mock-google-id-' . md5($email);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            login_user($user['id'], $user['role'], $user['name']);
        } else {
            $role = 'STUDENT';
            $dummy_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, google_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $dummy_password, $role, $google_id]);
            $new_id = $pdo->lastInsertId();
            login_user($new_id, $role, $name);
        }
        header("Location: index.php");
        exit;
    } else {
        // Redirect to mock UI to allow user to enter an email
        header("Location: mock-google-login.php");
        exit;
    }
}

if (isset($_GET['code'])) {
    // Exchange code for token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://oauth2.googleapis.com/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
        'code' => $_GET['code']
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (isset($data['access_token'])) {
        // Get user profile
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "https://www.googleapis.com/oauth2/v3/userinfo");
        curl_setopt($ch2, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $data['access_token']]);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $profile_res = curl_exec($ch2);
        curl_close($ch2);
        
        $profile = json_decode($profile_res, true);
        
        if (isset($profile['email'])) {
            $email = $profile['email'];
            $name = $profile['name'];
            $google_id = $profile['sub'];
            
            // Check if user exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Update google_id if not set
                if (empty($user['google_id'])) {
                    $upd = $pdo->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                    $upd->execute([$google_id, $user['id']]);
                }
                
                if (!$user['is_active']) {
                    $_SESSION['error'] = "Your account has been deactivated.";
                    header("Location: login.php");
                    exit;
                }
                
                login_user($user['id'], $user['role'], $user['name']);
                header("Location: index.php");
                exit;
            } else {
                // Auto-register student
                $role = 'STUDENT';
                // Dummy password for google users (cannot login via normal password)
                $dummy_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, google_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $dummy_password, $role, $google_id]);
                $new_id = $pdo->lastInsertId();
                
                login_user($new_id, $role, $name);
                header("Location: index.php");
                exit;
            }
        }
    }
    
    $_SESSION['error'] = "Google Authentication failed.";
    header("Location: login.php");
    exit;
}

// Redirect to Google
$auth_url = "https://accounts.google.com/o/oauth2/auth?" . http_build_query([
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'online',
    'prompt' => 'select_account'
]);

header("Location: $auth_url");
exit;
