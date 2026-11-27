<?php
session_start();
// TODO: require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/users.php");
    exit;
}

$userId = (int) ($_POST['user_id'] ?? 0);
// TODO: DELETE FROM users WHERE user_id = ? AND role != 'admin'
$_SESSION['flash_success'] = "Dummy user #{$userId} removed. Connect MySQL in actions/admin_delete_user.php.";
header("Location: ../admin/users.php");
exit;
