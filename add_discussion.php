<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if (!isset($_SESSION['csrf_discussion'])) {
    $_SESSION['csrf_discussion'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New discussion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1><a href="index.php">Discussions</a></h1>
        <nav>
            <span><?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>New discussion</h2>
        <?php if (isset($_SESSION['form_error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['form_error']) ?></p>
        <?php unset($_SESSION['form_error']); endif; ?>
        <form action="process_discussion.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_discussion']) ?>">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required maxlength="255">
            <label for="content">Content</label>
            <textarea name="content" id="content" required minlength="1" maxlength="10000"></textarea>
            <button type="submit">Create</button>
        </form>
    </main>
</body>
</html>
