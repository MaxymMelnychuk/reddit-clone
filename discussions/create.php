<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add.php');
    exit;
}
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_discussion'] ?? '', $_POST['csrf_token'])) {
    $_SESSION['form_error'] = 'Invalid token';
    header('Location: add.php');
    exit;
}
unset($_SESSION['csrf_discussion']);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
if (strlen($title) < 1 || strlen($title) > 255) {
    $_SESSION['form_error'] = 'Invalid title';
    header('Location: add.php');
    exit;
}
if (strlen($content) < 1 || strlen($content) > 10000) {
    $_SESSION['form_error'] = 'Invalid content';
    header('Location: add.php');
    exit;
}
$slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
$slug = trim($slug, '-');
if (empty($slug)) {
    $slug = 'discussion-' . time();
}
$baseSlug = $slug;
$i = 0;
require_once __DIR__ . '/../config/database.php';
$pdo = getPdo();
$stmt = $pdo->prepare('SELECT id FROM discussions WHERE slug = :slug');
do {
    $stmt->execute(['slug' => $slug]);
    if ($stmt->fetch()) {
        $i++;
        $slug = $baseSlug . '-' . $i;
    } else {
        break;
    }
} while (true);
$stmt = $pdo->prepare('INSERT INTO discussions (user_id, title, slug, content) VALUES (:user_id, :title, :slug, :content)');
$stmt->execute([
    'user_id' => $_SESSION['user_id'],
    'title' => $title,
    'slug' => $slug,
    'content' => $content
]);
header('Location: view.php?s=' . urlencode($slug));
exit;
