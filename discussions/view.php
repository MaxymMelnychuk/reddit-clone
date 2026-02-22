<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
if (!isset($_GET['s']) || trim($_GET['s']) === '') {
    header('Location: ../index.php');
    exit;
}
$slug = trim($_GET['s']);
if (strlen($slug) > 255) {
    header('Location: ../index.php');
    exit;
}
if (($_SESSION['role'] ?? '') === 'ADMIN' && !isset($_SESSION['csrf_admin'])) {
    $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));
}
require_once __DIR__ . '/../config/database.php';
$pdo = getPdo();
$stmt = $pdo->prepare('SELECT d.id, d.title, d.content, d.created_at, u.username FROM discussions d JOIN users u ON d.user_id = u.id WHERE d.slug = :slug');
$stmt->execute(['slug' => $slug]);
$discussion = $stmt->fetch();
if (!$discussion) {
    header('Location: ../index.php');
    exit;
}
$stmt = $pdo->prepare('SELECT c.id, c.content, c.hidden, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.discussion_id = :id ORDER BY c.created_at ASC');
$stmt->execute(['id' => $discussion['id']]);
$comments = $stmt->fetchAll();
if (!isset($_SESSION['csrf_comment'])) {
    $_SESSION['csrf_comment'] = bin2hex(random_bytes(32));
}
$isAdmin = ($_SESSION['role'] ?? '') === 'ADMIN';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($discussion['title']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1><a href="../index.php">Discussions</a></h1>
        <nav>
            <a href="add.php">New discussion</a>
            <span><?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <article class="discussion">
            <div class="discussion-header">
                <h2><?= htmlspecialchars($discussion['title']) ?></h2>
                <?php if ($isAdmin): ?>
                <form action="delete.php" method="post" class="admin-action" onsubmit="return confirm('Delete this discussion?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin']) ?>">
                    <input type="hidden" name="discussion_id" value="<?= (int) $discussion['id'] ?>">
                    <button type="submit" class="icon-btn" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></button>
                </form>
                <?php endif; ?>
            </div>
            <p class="meta">by <?= htmlspecialchars($discussion['username']) ?> - <?= htmlspecialchars(date('m/d/Y H:i', strtotime($discussion['created_at']))) ?></p>
            <div class="content"><?= nl2br(htmlspecialchars($discussion['content'])) ?></div>
        </article>
        <section class="comments">
            <h3>Comments</h3>
            <form action="../comments/add.php" method="post" class="comment-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_comment']) ?>">
                <input type="hidden" name="discussion_id" value="<?= (int) $discussion['id'] ?>">
                <textarea name="content" required minlength="1" maxlength="5000" placeholder="Add a comment..."></textarea>
                <button type="submit">Post</button>
            </form>
            <?php if (empty($comments)): ?>
            <p class="no-comments">No comments yet.</p>
            <?php else: ?>
            <ul class="comment-list">
                <?php foreach ($comments as $c): ?>
                <li>
                    <div class="comment-header">
                        <strong><?= htmlspecialchars($c['username']) ?></strong>
                        <span class="meta"><?= htmlspecialchars(date('m/d/Y H:i', strtotime($c['created_at']))) ?></span>
                        <?php if ($isAdmin): ?>
                        <div class="admin-actions">
                            <form action="../comments/toggle.php" method="post" class="admin-action">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin']) ?>">
                                <input type="hidden" name="comment_id" value="<?= (int) $c['id'] ?>">
                                <button type="submit" class="icon-btn" title="<?= $c['hidden'] ? 'Show' : 'Hide' ?>">
                                    <?php if ($c['hidden']): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                    <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <?php endif; ?>
                                </button>
                            </form>
                            <form action="../comments/delete.php" method="post" class="admin-action" onsubmit="return confirm('Delete this comment?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin']) ?>">
                                <input type="hidden" name="comment_id" value="<?= (int) $c['id'] ?>">
                                <button type="submit" class="icon-btn" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                    <p class="<?= $c['hidden'] ? 'hidden-content' : '' ?>"><?= $c['hidden'] ? 'This content appears to be inappropriate. If this is an error, please contact support.' : nl2br(htmlspecialchars($c['content'])) ?></p>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
