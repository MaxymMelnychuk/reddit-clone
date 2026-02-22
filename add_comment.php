<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_comment'] ?? '', $_POST['csrf_token'])) {
    header('Location: index.php');
    exit;
}
$discussion_id = (int) ($_POST['discussion_id'] ?? 0);
$content = trim($_POST['content'] ?? '');
if ($discussion_id < 1 || strlen($content) < 1 || strlen($content) > 5000) {
    header('Location: index.php');
    exit;
}
require_once __DIR__ . '/config.php';
$pdo = getPdo();
$stmt = $pdo->prepare('SELECT slug FROM discussions WHERE id = :id');
$stmt->execute(['id' => $discussion_id]);
$disc = $stmt->fetch();
if (!$disc) {
    header('Location: index.php');
    exit;
}
$stmt = $pdo->prepare('INSERT INTO comments (discussion_id, user_id, content) VALUES (:discussion_id, :user_id, :content)');
$stmt->execute([
    'discussion_id' => $discussion_id,
    'user_id' => $_SESSION['user_id'],
    'content' => $content
]);
unset($_SESSION['csrf_comment']);
header('Location: discussion.php?s=' . urlencode($disc['slug']));
exit;
