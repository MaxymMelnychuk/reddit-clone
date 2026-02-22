<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'ADMIN') {
    header('Location: ../index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_admin'] ?? '', $_POST['csrf_token'])) {
    header('Location: ../index.php');
    exit;
}
$comment_id = (int) ($_POST['comment_id'] ?? 0);
if ($comment_id < 1) {
    header('Location: ../index.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';
$pdo = getPdo();
$stmt = $pdo->prepare('SELECT discussion_id, hidden FROM comments WHERE id = :id');
$stmt->execute(['id' => $comment_id]);
$row = $stmt->fetch();
if (!$row) {
    header('Location: ../index.php');
    exit;
}
$stmt = $pdo->prepare('SELECT slug FROM discussions WHERE id = :id');
$stmt->execute(['id' => $row['discussion_id']]);
$disc = $stmt->fetch();
$slug = $disc ? $disc['slug'] : null;
$newHidden = $row['hidden'] ? 0 : 1;
$pdo->prepare('UPDATE comments SET hidden = :hidden WHERE id = :id')->execute(['hidden' => $newHidden, 'id' => $comment_id]);
header('Location: ' . ($slug ? '../discussions/view.php?s=' . urlencode($slug) : '../index.php'));
exit;
