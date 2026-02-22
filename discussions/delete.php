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
$discussion_id = (int) ($_POST['discussion_id'] ?? 0);
if ($discussion_id < 1) {
    header('Location: ../index.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';
$pdo = getPdo();
$pdo->prepare('DELETE FROM discussions WHERE id = :id')->execute(['id' => $discussion_id]);
header('Location: ../index.php');
exit;
