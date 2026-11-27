<?php
session_start();
$basePath = '../';
$role = 'student';
$activePage = 'slots';
$pageTitle = 'Available Consultation Slots';
$pageSubtitle = 'Filter open lecturer slots by course, consultation type, or date';

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$search = trim($_GET['search'] ?? '');
$typeFilter = $_GET['type'] ?? 'All';
$dateFilter = $_GET['date'] ?? '';

// TODO: Replace dummy slots with MySQL SELECT query
$dummySlots = [
    ['slot_id' => 101, 'lecturer' => 'Dr. Nurul Huda', 'department' => 'Software Engineering', 'course' => 'STIWK2014', 'date' => '2026-11-27', 'time' => '10:00 AM - 10:30 AM', 'type' => 'Online', 'location' => 'Google Meet', 'is_booked' => false],
    ['slot_id' => 102, 'lecturer' => 'Assoc. Prof. Dr. Hafizuddin', 'department' => 'Database Systems', 'course' => 'STID3014', 'date' => '2026-11-27', 'time' => '02:30 PM - 03:00 PM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Room 3.14', 'is_booked' => false],
    ['slot_id' => 103, 'lecturer' => 'Dr. Nurul Huda', 'department' => 'Software Engineering', 'course' => 'STIWK2014', 'date' => '2026-11-28', 'time' => '11:00 AM - 11:30 AM', 'type' => 'Online', 'location' => 'Microsoft Teams', 'is_booked' => true],
    ['slot_id' => 104, 'lecturer' => 'Dr. Syafiqah Roslan', 'department' => 'HCI', 'course' => 'STIW2044', 'date' => '2026-11-29', 'time' => '09:30 AM - 10:00 AM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Lab 2.08', 'is_booked' => false],
];

$slots = array_filter($dummySlots, function ($slot) use ($search, $typeFilter, $dateFilter) {
    $mSearch = $search === '' || stripos($slot['lecturer'], $search) !== false || stripos($slot['course'], $search) !== false;
    $mType = $typeFilter === 'All' || $slot['type'] === $typeFilter;
    $mDate = $dateFilter === '' || $slot['date'] === $dateFilter;
    return $mSearch && $mType && $mDate;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Available Slots — Student Portal</title>
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

        <form action="slots.php" method="GET" class="panel filter-panel">
          <div class="filter-row">
            <div class="form-group">
              <label for="search">Search Lecturer or Course</label>
              <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="e.g. Dr. Nurul, STIWK2014..." />
            </div>
            <div class="form-group">
              <label for="type">Consultation Type</label>
              <select id="type" name="type">
                <option value="All" <?= $typeFilter === 'All' ? 'selected' : '' ?>>All Types</option>
                <option value="Online" <?= $typeFilter === 'Online' ? 'selected' : '' ?>>Online</option>
                <option value="Face-to-Face" <?= $typeFilter === 'Face-to-Face' ? 'selected' : '' ?>>Face-to-Face</option>
              </select>
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" id="date" name="date" value="<?= htmlspecialchars($dateFilter) ?>" />
            </div>
            <div class="form-group filter-btns">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="slots.php" class="btn btn-ghost">Reset</a>
            </div>
          </div>
        </form>

        <div class="slots-grid">
          <?php foreach ($slots as $slot): ?>
            <article class="slot-card <?= $slot['is_booked'] ? 'is-booked' : '' ?>">
              <div class="slot-top">
                <div>
                  <div class="slot-lecturer"><?= htmlspecialchars($slot['lecturer']) ?></div>
                  <div class="slot-dept"><?= htmlspecialchars($slot['department']) ?> • <?= htmlspecialchars($slot['course']) ?></div>
                </div>
                <span class="status-badge <?= $slot['is_booked'] ? 'status-rejected' : 'status-approved' ?>">
                  <?= $slot['is_booked'] ? 'Booked' : 'Available' ?>
                </span>
              </div>
              <div class="slot-details">
                <span><strong>Date:</strong> <?= htmlspecialchars($slot['date']) ?></span>
                <span><strong>Time:</strong> <?= htmlspecialchars($slot['time']) ?></span>
                <span><strong><?= $slot['type'] === 'Online' ? 'Platform' : 'Location' ?>:</strong> <?= htmlspecialchars($slot['location']) ?></span>
              </div>
              <div class="slot-footer">
                <span class="type-badge <?= $slot['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($slot['type']) ?></span>
                <?php if ($slot['is_booked']): ?>
                  <form action="../actions/student_book_slot.php" method="POST">
                    <input type="hidden" name="slot_id" value="<?= (int) $slot['slot_id'] ?>" />
                    <input type="hidden" name="is_booked_dummy" value="1" />
                    <button type="submit" class="btn btn-ghost btn-sm">Try Booking</button>
                  </form>
                <?php else: ?>
                  <button type="button" class="btn btn-primary btn-sm"
                    data-open-booking
                    data-slot-id="<?= (int) $slot['slot_id'] ?>"
                    data-lecturer="<?= htmlspecialchars($slot['lecturer']) ?>"
                    data-course="<?= htmlspecialchars($slot['course']) ?>"
                    data-schedule="<?= htmlspecialchars($slot['date'] . ' • ' . $slot['time']) ?>"
                    data-type="<?= htmlspecialchars($slot['type']) ?>"
                    data-location="<?= htmlspecialchars($slot['location']) ?>">
                    Request Slot
                  </button>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </main>
    </div>
  </div>

  <div id="bookingModal" class="modal-backdrop hidden" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-header">
        <h3>Request Consultation</h3>
        <button type="button" class="icon-btn" data-close-modal="bookingModal">✕</button>
      </div>
      <div id="bookingSlotSummary" class="slot-summary-box"></div>
      <form action="../actions/student_book_slot.php" method="POST">
        <input type="hidden" id="modalSlotId" name="slot_id" value="" />
        <div class="form-group">
          <label for="course_code">Course Code</label>
          <input type="text" id="course_code" name="course_code" required />
        </div>
        <div class="form-group">
          <label for="topic">Consultation Topic <span class="required">*</span></label>
          <input type="text" id="topic" name="topic" minlength="5" placeholder="e.g. Review ER Diagram" required />
        </div>
        <div class="form-group">
          <label for="notes">Additional Notes</label>
          <textarea id="notes" name="notes" rows="3"></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" data-close-modal="bookingModal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../js/ui.js"></script>
</body>
</html>
