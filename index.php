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
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <header>
        <h1>Reddit Clone</h1>
        <nav>
            <a class="new_discussion" href="discussions/add.php">+ New discussion</a>
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
            <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.4" d="M12 22.01C17.5228 22.01 22 17.5329 22 12.01C22 6.48716 17.5228 2.01001 12 2.01001C6.47715 2.01001 2 6.48716 2 12.01C2 17.5329 6.47715 22.01 12 22.01Z" fill="#6f7985"></path> <path d="M12 6.93994C9.93 6.93994 8.25 8.61994 8.25 10.6899C8.25 12.7199 9.84 14.3699 11.95 14.4299C11.98 14.4299 12.02 14.4299 12.04 14.4299C12.06 14.4299 12.09 14.4299 12.11 14.4299C12.12 14.4299 12.13 14.4299 12.13 14.4299C14.15 14.3599 15.74 12.7199 15.75 10.6899C15.75 8.61994 14.07 6.93994 12 6.93994Z" fill="#6f7985"></path> <path d="M18.7807 19.36C17.0007 21 14.6207 22.01 12.0007 22.01C9.3807 22.01 7.0007 21 5.2207 19.36C5.4607 18.45 6.1107 17.62 7.0607 16.98C9.7907 15.16 14.2307 15.16 16.9407 16.98C17.9007 17.62 18.5407 18.45 18.7807 19.36Z" fill="#6f7985"></path> </g></svg>
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
