<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="auth-page">
    <h1>Login</h1>
    <?php if (isset($_SESSION['auth_error'])): ?>
    <p class="error"><?= htmlspecialchars($_SESSION['auth_error']) ?></p>
    <?php unset($_SESSION['auth_error']); endif; ?>
    <form action="process.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="action" value="login">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required maxlength="50" autocomplete="username">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required autocomplete="current-password">
        <button type="submit">Sign in</button>
    </form>
    <p><a href="register.php">Create an account</a></p>
</body>
</html>
