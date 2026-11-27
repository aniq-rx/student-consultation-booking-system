<?php
session_start();
// TODO: require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/users.php");
    exit;
}

$code = trim($_POST['matric_or_staff_id'] ?? '');
$name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = $_POST['user_role'] ?? 'student';

if ($code === '' || $name === '' || $email === '') {
    $_SESSION['flash_error'] = "Please complete all required user fields.";
    header("Location: ../admin/users.php");
    exit;
}

// TODO: INSERT INTO users (matric_or_staff_id, full_name, email, password_hash, role, department) VALUES (...)
$_SESSION['flash_success'] = "Dummy {$role} account '{$name}' ({$code}) registered. Connect MySQL in actions/admin_add_user.php.";
header("Location: ../admin/users.php");
exit;
