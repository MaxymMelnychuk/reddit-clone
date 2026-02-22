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
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="auth-page">
    <form action="process.php" method="post">
        <h1>Register</h1>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="action" value="register">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required maxlength="50" pattern="[a-zA-Z0-9_]+" autocomplete="username">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required maxlength="255" autocomplete="email">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required minlength="8" autocomplete="new-password">
        <label for="password_confirm">Confirm password</label>
        <input type="password" name="password_confirm" id="password_confirm" required minlength="8" autocomplete="new-password">
        <button type="submit">Sign up</button>
        <p><a href="login.php">Already have an account? Sign in</a></p>
        <?php if (isset($_SESSION['auth_error'])): ?>
    <p class="error"><?= htmlspecialchars($_SESSION['auth_error']) ?></p>
    <?php unset($_SESSION['auth_error']); endif; ?>
    </form>
</body>
</html>
