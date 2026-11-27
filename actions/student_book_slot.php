<?php
session_start();
// TODO: require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../student/slots.php");
    exit;
}

$slotId = (int) ($_POST['slot_id'] ?? 0);
$courseCode = trim($_POST['course_code'] ?? '');
$topic = trim($_POST['topic'] ?? '');
$isBookedDummy = (int) ($_POST['is_booked_dummy'] ?? 0);

// MODIFICATION 2 — PREVENT DOUBLE BOOKING
if ($isBookedDummy === 1) {
    $_SESSION['flash_error'] = "This consultation slot is no longer available.";
    header("Location: ../student/slots.php");
    exit;
}

if ($slotId <= 0 || $courseCode === '' || strlen($topic) < 5) {
    $_SESSION['flash_error'] = "Please enter a valid course code and topic (at least 5 characters).";
    header("Location: ../student/slots.php");
    exit;
}

// TODO: INSERT INTO consultation_requests & UPDATE consultation_slots SET is_booked = 1
$_SESSION['flash_success'] = "Dummy booking submitted for Slot #{$slotId} (Status: Pending). Connect MySQL in actions/student_book_slot.php.";
header("Location: ../student/slots.php");
exit;
