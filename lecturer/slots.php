<?php
session_start();
require_once '../config/db.php';
$basePath = '../';
$role = 'lecturer';
$activePage = 'slots';
$pageTitle = 'Add Consultation Slots';
$pageSubtitle = 'Create available Face-to-Face or Online consultation slots for students';

if(!isset($_SESSION['lecturerID'])) {
        echo "<script>
            alert('You must be logged in first');
            window.location.href='../index.php';
        </script>";
    exit();
    }

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// TODO: Replace dummy slots with MySQL SELECT for this lecturer_id
// $mySlots = [
//     ['slot_id' => 101, 'course' => 'STIWK2014', 'date' => '2026-11-27', 'time' => '10:00 AM - 10:30 AM', 'type' => 'Online', 'location' => 'Google Meet', 'meeting_link' => 'https://meet.google.com/uum-se-101', 'is_booked' => false],
//     ['slot_id' => 103, 'course' => 'STIWK2014', 'date' => '2026-11-28', 'time' => '11:00 AM - 11:30 AM', 'type' => 'Online', 'location' => 'Microsoft Teams', 'meeting_link' => 'https://teams.microsoft.com/l/meetup-join/se-103', 'is_booked' => true],
//     ['slot_id' => 106, 'course' => 'STIWK2014', 'date' => '2026-11-29', 'time' => '02:00 PM - 02:30 PM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Room 4.02', 'meeting_link' => '', 'is_booked' => false],
// ];

$lecturerId = $_SESSION['lecturerID'] ?? 0;
$mySlots = [];
$courses = [];

if ($lecturerId && isset($conn) && $conn instanceof mysqli) {
  $courseStmt = $conn->prepare(
    'SELECT course_id, course_code, course_name
     FROM courses
     WHERE lecturer_id = ?
     ORDER BY course_code ASC'
  );
  $courseStmt->bind_param('i', $lecturerId);
  $courseStmt->execute();
  $courseResult = $courseStmt->get_result();
  while ($courseRow = $courseResult->fetch_assoc()) {
      $courses[] = $courseRow;
  }
  $courseStmt->close();

    $stmt = $conn->prepare(
        'SELECT cs.slot_id, c.course_code, cs.slot_date, cs.start_time, cs.end_time,
          cs.consultation_type, cs.location, cs.meeting_link, cs.is_available
         FROM consultation_slots AS cs
         JOIN courses AS c ON c.course_id = cs.course_id
         WHERE cs.lecturer_id = ?
         ORDER BY cs.slot_date ASC, cs.start_time ASC'
    );
    $stmt->bind_param('i', $lecturerId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $mySlots[] = [
            'slot_id'      => $row['slot_id'],
            'course'       => $row['course_code'],
            'date'         => $row['slot_date'],
            'time'         => date('h:i A', strtotime($row['start_time'])) . ' - ' . date('h:i A', strtotime($row['end_time'])),
            'type'         => $row['consultation_type'],
            'location'     => $row['location'],
            'meeting_link' => $row['meeting_link'],
            'is_booked'    => !$row['is_available'],
        ];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Slots — Lecturer Portal</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/slots.css" />
</head>
<body>
  <div class="app-shell">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-wrapper">
      <?php include '../includes/topbar.php'; ?>

      <main class="content">
        <?php if ($flashSuccess !== ''): ?><div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>
        <?php if ($flashError !== ''): ?><div class="alert alert-error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>

        <!-- Create New Slot Form (Modification 3: Face-to-Face vs Online) -->
        <section class="panel" style="margin-bottom: 24px;">
          <div class="panel-header">
            <h3>Create Available Consultation Slot</h3>
            <span class="type-badge type-f2f">Mod 3: Consultation Type</span>
          </div>

          <form action="../actions/lecturer_create_slot.php" method="POST" class="slot-create-grid">
            <div class="form-group">
              <label for="course_code">Course Code <span class="required">*</span></label>
              <select id="course_code" name="course_code" required>
                <option value="">Select a course</option>
                <?php foreach ($courses as $course): ?>
                  <option value="<?= (int) $course['course_id'] ?>">
                    <?= htmlspecialchars($course['course_code'] . ' — ' . $course['course_name'], ENT_QUOTES, 'UTF-8') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="slot_date">Date <span class="required">*</span></label>
              <input type="date" id="slot_date" name="slot_date" required />
            </div>
            <div class="form-group">
              <label for="start_time">Start Time <span class="required">*</span></label>
              <input type="time" id="start_time" name="start_time" required />
            </div>
            <div class="form-group">
              <label for="end_time">End Time <span class="required">*</span></label>
              <input type="time" id="end_time" name="end_time" required />
            </div>
            <div class="form-group">
              <label for="consultation_type">Consultation Type <span class="required">*</span></label>
              <select id="consultation_type" name="consultation_type" data-toggle-type>
                <option value="Face-to-Face">Face-to-Face</option>
                <option value="Online">Online</option>
              </select>
            </div>
            <div class="form-group" id="locationGroup">
              <label for="location">Consultation Location / Platform <span class="required">*</span></label>
              <input type="text" id="location" name="location" placeholder="e.g. SOC Building, Room 4.02" required />
            </div>
            <div class="form-group hidden" id="meetingLinkGroup">
              <label for="meeting_link">Online Meeting Link <span class="required">*</span></label>
              <input type="url" id="meeting_link" name="meeting_link" placeholder="https://meet.google.com/..." />
            </div>
            <div class="form-group" style="justify-content: flex-end;">
              <button type="submit" class="btn btn-primary">+ Publish Slot</button>
            </div>
          </form>
        </section>

        <!-- Lecturer's Existing Slots -->
        <h3 style="margin-bottom: 14px;">Your Published Slots</h3>
        <div class="slots-grid">
          <?php foreach ($mySlots as $slot): ?>
            <article class="slot-card <?= $slot['is_booked'] ? 'is-booked' : '' ?>">
              <div class="slot-top">
                <div>
                  <div class="slot-lecturer"><?= htmlspecialchars($slot['course']) ?> • <?= htmlspecialchars($slot['date']) ?></div>
                  <div class="slot-dept"><?= htmlspecialchars($slot['time']) ?></div>
                </div>
                <span class="status-badge <?= $slot['is_booked'] ? 'status-pending' : 'status-approved' ?>">
                  <?= $slot['is_booked'] ? 'Booked' : 'Open' ?>
                </span>
              </div>
              <div class="slot-details">
                <span><strong>Type:</strong> <?= htmlspecialchars($slot['type']) ?></span>
                <span><strong><?= $slot['type'] === 'Online' ? 'Platform' : 'Location' ?>:</strong> <?= htmlspecialchars($slot['location']) ?></span>
                <?php if ($slot['type'] === 'Online' && !empty($slot['meeting_link'])): ?>
                  <span><strong>Link:</strong> <a href="<?= htmlspecialchars($slot['meeting_link']) ?>" target="_blank" rel="noopener noreferrer">Open Link</a></span>
                <?php endif; ?>
              </div>
              <div class="slot-footer">
                <span class="type-badge <?= $slot['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($slot['type']) ?></span>
                <?php if (!$slot['is_booked']): ?>
                  <form action="../actions/lecturer_delete_slot.php" method="POST">
                    <input type="hidden" name="slot_id" value="<?= (int) $slot['slot_id'] ?>" />
                    <button type="submit" class="btn btn-ghost btn-sm">Delete Slot</button>
                  </form>
                <?php else: ?>
                  <span class="text-muted" style="font-size: 0.8rem;">Locked (Booked)</span>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </main>
    </div>
  </div>
  <script src="../js/ui.js"></script>
</body>
</html>
