<?php
session_start();

include __DIR__ . "/config/db.php";

// Redirect if already logged in
if (isset($_SESSION['studentID'])) {
    $role = 'student';
    header("Location: student/dashboard.php");
    exit();
}

if (isset($_SESSION['lecturerID'])) {
    $role = 'lecturer';
    header("Location: lecturer/dashboard.php");
    exit();
}

if (isset($_SESSION['adminID'])) {
    $role = 'admin';
    header("Location: admin/dashboard.php");
    exit();
}

// $role = $_SESSION['role'] ?? '';
// if ($role === 'student') {
//     header("Location: student/dashboard.php");
//     exit;
// }
// if ($role === 'lecturer') {
//     header("Location: lecturer/dashboard.php");
//     exit;
// }
// if ($role === 'admin') {
//     header("Location: admin/dashboard.php");
//     exit;
// }

header("Location: login.php");
exit;

