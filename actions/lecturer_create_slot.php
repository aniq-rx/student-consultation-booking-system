<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../lecturer/slots.php");
    exit;
}

$courseId = trim($_POST['course_code'] ?? '');
$slotDate = trim($_POST['slot_date'] ?? '');
$startTime = trim($_POST['start_time'] ?? '');
$endTime = trim($_POST['end_time'] ?? '');
$type = $_POST['consultation_type'] ?? 'Face-to-Face';
$location = trim($_POST['location'] ?? '');
$meetingLink = trim($_POST['meeting_link'] ?? '');

if ($courseId === '' || $slotDate === '' || $startTime === '' || $endTime === '') {
    $_SESSION['flash_error'] = "Please fill in all required date, time, and course fields.";
    header("Location: ../lecturer/slots.php");
    exit;
}

// MODIFICATION 3 — CONSULTATION TYPE VALIDATION
if ($endTime <= $startTime) {
    $_SESSION['flash_error'] = "End time must be later than start time.";
    header("Location: ../lecturer/slots.php");
    exit;
}

if ($type === 'Online' && $meetingLink === '') {
    $_SESSION['flash_error'] = "Please provide a meeting link for Online consultations.";
    header("Location: ../lecturer/slots.php");
    exit;
}

$lecturerId = $_SESSION['lecturerID'] ?? $_SESSION['user_id'] ?? null;
if (!$lecturerId || !isset($conn) || !($conn instanceof mysqli)) {
    $_SESSION['flash_error'] = 'Unable to save the slot. Please sign in again.';
    header("Location: ../lecturer/slots.php");
    exit;
}

$sql = 'INSERT INTO consultation_slots
        (lecturer_id, course_id, slot_date, start_time, end_time, consultation_type, location, meeting_link)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

try {
    $courseStmt = $conn->prepare('SELECT course_code, course_name FROM courses WHERE course_id = ?');
    $courseStmt->bind_param('s', $courseId);
    $courseStmt->execute();
    $courseStmt->bind_result($courseCode, $courseName);
    if (!$courseStmt->fetch()) {
        $courseStmt->close();
        throw new RuntimeException('Selected course was not found.');
    }
    $courseStmt->close();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'isssssss',
        $lecturerId,
        $courseId,
        $slotDate,
        $startTime,
        $endTime,
        $type,
        $location,
        $meetingLink
    );
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash_success'] = "Consultation slot created for {$courseCode} - {$courseName} on {$slotDate}.";
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        $_SESSION['flash_error'] = 'You already have a slot at this date and start time.';
    } else {
        $_SESSION['flash_error'] = 'Unable to create the consultation slot. Please try again. Database error: ' . $e->getMessage();
    }
}

header("Location: ../lecturer/slots.php");
exit;
