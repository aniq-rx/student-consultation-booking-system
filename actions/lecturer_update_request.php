<?php
session_start();
// TODO: require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../lecturer/requests.php");
    exit;
}

$requestId = (int) ($_POST['request_id'] ?? 0);
$newStatus = $_POST['new_status'] ?? '';
$remark = trim($_POST['lecturer_remark'] ?? '');

$allowed = ['Approved', 'Rejected', 'Completed'];
if ($requestId <= 0 || !in_array($newStatus, $allowed, true)) {
    $_SESSION['flash_error'] = "Invalid status transition requested.";
    header("Location: ../lecturer/requests.php");
    exit;
}

// MODIFICATION 1 — UPDATE CONSULTATION STATUS
// TODO: UPDATE consultation_requests SET status = ?, lecturer_remark = ? WHERE request_id = ?
// TODO: If $newStatus === 'Rejected', also UPDATE consultation_slots SET is_booked = 0
$_SESSION['flash_success'] = "Dummy request #{$requestId} updated to '{$newStatus}'. Connect MySQL in actions/lecturer_update_request.php.";
header("Location: ../lecturer/requests.php");
exit;
