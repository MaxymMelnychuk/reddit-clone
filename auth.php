<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    $_SESSION['auth_error'] = 'Invalid token';
    $redirect = ($_POST['action'] ?? '') === 'register' ? 'register.php' : 'login.php';
    header('Location: ' . $redirect);
    exit;
}

$action = $_POST['action'] ?? '';
if (!in_array($action, ['login', 'register'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config.php';

if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $_SESSION['auth_error'] = 'Invalid username (min 3 characters, letters, numbers, underscore only)';
        header('Location: register.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['auth_error'] = 'Invalid email';
        header('Location: register.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['auth_error'] = 'Password must be at least 8 characters';
        header('Location: register.php');
        exit;
    }

    if ($password !== $password_confirm) {
        $_SESSION['auth_error'] = 'Passwords do not match';
        header('Location: register.php');
        exit;
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);
    if ($stmt->fetch()) {
        $_SESSION['auth_error'] = 'Username or email already in use';
        header('Location: register.php');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, \'USER\')');
    $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hash]);

    $_SESSION['user_id'] = (int) $pdo->lastInsertId();
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'USER';
    session_regenerate_id(true);
    header('Location: index.php');
    exit;
}

if ($action === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['auth_error'] = 'Credentials required';
        header('Location: login.php');
        exit;
    }

    $pdo = getPdo();
    $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['auth_error'] = 'Invalid credentials';
        header('Location: login.php');
        exit;
    }

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    session_regenerate_id(true);
    header('Location: index.php');
    exit;
}
