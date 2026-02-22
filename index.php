<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}
if (($_SESSION['role'] ?? '') === 'ADMIN' && !isset($_SESSION['csrf_admin'])) {
    $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));
}
require_once __DIR__ . '/config/database.php';
$pdo = getPdo();
$stmt = $pdo->query('SELECT d.id, d.title, d.slug, d.created_at, u.username FROM discussions d JOIN users u ON d.user_id = u.id ORDER BY d.created_at DESC');
$discussions = $stmt->fetchAll();
$isAdmin = ($_SESSION['role'] ?? '') === 'ADMIN';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussions</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Discussions</h1>
        <nav>
            <a href="discussions/add.php">New discussion</a>
            <span><?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="auth/logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <?php if (empty($discussions)): ?>
        <p>No discussions yet. <a href="discussions/add.php">Create the first one</a></p>
        <?php else: ?>
        <ul class="discussion-list">
            <?php foreach ($discussions as $d): ?>
            <li>
                <div class="discussion-row">
                    <a href="discussions/view.php?s=<?= urlencode($d['slug']) ?>"><?= htmlspecialchars($d['title']) ?></a>
                    <?php if ($isAdmin): ?>
                    <form action="discussions/delete.php" method="post" class="admin-action" onsubmit="return confirm('Delete this discussion?');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin']) ?>">
                        <input type="hidden" name="discussion_id" value="<?= (int) $d['id'] ?>">
                        <button type="submit" class="icon-btn" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></button>
                    </form>
                    <?php endif; ?>
                </div>
                <span class="meta">by <?= htmlspecialchars($d['username']) ?> - <?= htmlspecialchars(date('m/d/Y H:i', strtotime($d['created_at']))) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </main>
</body>
</html>
