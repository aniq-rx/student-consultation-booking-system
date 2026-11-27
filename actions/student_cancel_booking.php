<?php
session_start();
// TODO: require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../student/consultations.php");
    exit;
}

$requestId = (int) ($_POST['request_id'] ?? 0);
// TODO: DELETE FROM consultation_requests & UPDATE consultation_slots SET is_booked = 0
$_SESSION['flash_success'] = "Dummy cancellation processed for Request #{$requestId}. Connect MySQL in actions/student_cancel_booking.php.";
header("Location: ../student/consultations.php");
exit;
