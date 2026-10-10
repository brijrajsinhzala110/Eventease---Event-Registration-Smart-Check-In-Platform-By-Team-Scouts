<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /eventease-php/login.php');
        exit;
    }
}

function require_role($role) {
    require_login();
    if ($_SESSION['user_role'] !== $role) {
        die('Unauthorized Access');
    }
}

function require_roles($roles) {
    require_login();
    if (!in_array($_SESSION['user_role'], $roles)) {
        die('Unauthorized Access');
    }
}

function login_user($user_id, $role, $name) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_role'] = $role;
    $_SESSION['user_name'] = $name;
}

function logout_user() {
    session_destroy();
    header('Location: /eventease-php/login.php');
    exit;
}
