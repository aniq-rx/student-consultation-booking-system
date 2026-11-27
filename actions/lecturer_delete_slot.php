<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../lecturer/slots.php");
    exit;
}

// MODIFICATION 1 — DELETE SLOT
$slotId = (int) ($_POST['slot_id'] ?? 0);
if ($slotId < 1) {
    $_SESSION['flash_error'] = 'Invalid slot selected.';
    header("Location: ../lecturer/slots.php");
    exit;
}

try {
    $courseStmt = $conn->prepare(
        'SELECT c.course_code, c.course_name
         FROM consultation_slots
         JOIN courses c ON c.course_id = consultation_slots.course_id
         WHERE consultation_slots.slot_id = ? AND consultation_slots.is_available = 1'
    );

    if (!$courseStmt) {
        throw new RuntimeException('Could not prepare the course lookup query.');
    }

    $courseStmt->bind_param('i', $slotId);
    $courseStmt->execute();
    $course = $courseStmt->get_result()->fetch_assoc();
    $courseStmt->close();

    $stmt = $conn->prepare(
        'DELETE FROM consultation_slots WHERE slot_id = ? AND is_available = 1'
        // 'DELETE FROM consultation_slots WHERE slot_id = ? AND is_booked = 0'
    );

    if (!$stmt) {
        throw new RuntimeException('Could not prepare the delete query.');
    }

    $stmt->bind_param('i', $slotId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $courseLabel = $course
            ? htmlspecialchars($course['course_code'] . ' - ' . $course['course_name'], ENT_QUOTES, 'UTF-8')
            : 'unknown course';
        $_SESSION['flash_success'] = "Slot for {$courseLabel} deleted.";
       // $_SESSION['flash_success'] = "Slot #{$slotId} for {$courseLabel} deleted.";
    } else {
        $_SESSION['flash_error'] = 'Slot not found or already booked; it was not deleted.';
    }
    $stmt->close();
} catch (Throwable $e) {
    $_SESSION['flash_error'] = 'Could not delete the slot. Please try again. Database error: ' . $e->getMessage();
}

header("Location: ../lecturer/slots.php");
exit;

// $_SESSION['flash_success'] = "Dummy slot #{$slotId} deleted. Connect MySQL in actions/lecturer_delete_slot.php.";
// header("Location: ../lecturer/slots.php");
// exit;
