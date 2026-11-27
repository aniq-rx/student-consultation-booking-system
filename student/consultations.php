<?php
session_start();
$basePath = '../';
$role = 'student';
$activePage = 'consultations';
$pageTitle = 'My Consultations';
$pageSubtitle = 'Monitor request statuses, join online meetings, or cancel bookings';

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$statusFilter = $_GET['status'] ?? 'All';
$tabs = ['All', 'Pending', 'Approved', 'Completed', 'Rejected'];

// TODO: Replace dummy data with MySQL SELECT query
$dummyConsultations = [
    ['request_id' => 201, 'lecturer' => 'Dr. Nurul Huda', 'course' => 'STIWK2014', 'date' => '2026-11-26', 'time' => '02:00 PM - 02:30 PM', 'type' => 'Online', 'location' => 'Google Meet', 'meeting_link' => 'https://meet.google.com/uum-se-201', 'topic' => 'D2 Software Design Review', 'status' => 'Approved', 'remark' => 'Please prepare your diagrams.'],
    ['request_id' => 202, 'lecturer' => 'Assoc. Prof. Dr. Hafizuddin', 'course' => 'STID3014', 'date' => '2026-11-28', 'time' => '04:00 PM - 04:30 PM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Room 3.14', 'meeting_link' => '', 'topic' => 'Normalization & SQL Optimization', 'status' => 'Pending', 'remark' => ''],
    ['request_id' => 203, 'lecturer' => 'Dr. Syafiqah Roslan', 'course' => 'STIW2044', 'date' => '2026-11-20', 'time' => '11:00 AM - 11:30 AM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Room 2.05', 'meeting_link' => '', 'topic' => 'Wireframe Usability Feedback', 'status' => 'Completed', 'remark' => 'Good navigation hierarchy.'],
    ['request_id' => 204, 'lecturer' => 'Dr. Amirul Hakim', 'course' => 'STIW3084', 'date' => '2026-11-22', 'time' => '10:00 AM - 10:30 AM', 'type' => 'Online', 'location' => 'Zoom Meeting', 'meeting_link' => '', 'topic' => 'REST API Auth Middleware', 'status' => 'Rejected', 'remark' => 'Faculty meeting clash. Book Monday slot instead.']
];

$consultations = $statusFilter === 'All'
    ? $dummyConsultations
    : array_filter($dummyConsultations, fn($c) => $c['status'] === $statusFilter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Consultations — Student Portal</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/consultations.css" />
</head>
<body>
  <div class="app-shell">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-wrapper">
      <?php include '../includes/topbar.php'; ?>

      <main class="content">
        <?php if ($flashSuccess !== ''): ?><div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>
        <?php if ($flashError !== ''): ?><div class="alert alert-error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>

        <div class="tabs-bar">
          <?php foreach ($tabs as $tab): ?>
            <a href="consultations.php?status=<?= urlencode($tab) ?>" class="tab-btn <?= $statusFilter === $tab ? 'active' : '' ?>"><?= htmlspecialchars($tab) ?></a>
          <?php endforeach; ?>
        </div>

        <div class="consultations-list">
          <?php foreach ($consultations as $item): ?>
            <article class="consultation-card">
              <div class="consultation-main">
                <div class="consultation-header">
                  <span class="consultation-topic"><?= htmlspecialchars($item['topic']) ?></span>
                  <span class="status-badge status-<?= strtolower($item['status']) ?>"><?= htmlspecialchars($item['status']) ?></span>
                  <span class="type-badge <?= $item['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($item['type']) ?></span>
                </div>
                <div class="consultation-meta"><strong><?= htmlspecialchars($item['lecturer']) ?></strong> (<?= htmlspecialchars($item['course']) ?>) • <?= htmlspecialchars($item['date']) ?> • <?= htmlspecialchars($item['time']) ?></div>
                <div class="consultation-meta"><?= $item['type'] === 'Online' ? 'Platform' : 'Location' ?>: <strong><?= htmlspecialchars($item['location']) ?></strong></div>
                <?php if (!empty($item['remark'])): ?>
                  <div class="remark-box <?= $item['status'] === 'Rejected' ? 'rejected' : '' ?>"><strong>Lecturer Remark:</strong> <?= htmlspecialchars($item['remark']) ?></div>
                <?php endif; ?>
              </div>
              <div class="consultation-actions">
                <?php if ($item['status'] === 'Approved' && $item['type'] === 'Online' && !empty($item['meeting_link'])): ?>
                  <a href="<?= htmlspecialchars($item['meeting_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">Join Meeting</a>
                <?php endif; ?>
                <?php if (in_array($item['status'], ['Pending', 'Approved'], true)): ?>
                  <button type="button" class="btn btn-ghost btn-sm"
                    data-open-cancel
                    data-request-id="<?= (int) $item['request_id'] ?>"
                    data-summary="<?= htmlspecialchars($item['date'] . ' (' . $item['time'] . ') with ' . $item['lecturer']) ?>">
                    Cancel Booking
                  </button>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </main>
    </div>
  </div>

  <div id="cancelModal" class="modal-backdrop hidden" role="dialog" aria-modal="true">
    <div class="modal-card modal-sm">
      <div class="modal-header">
        <h3>Cancel Consultation?</h3>
        <button type="button" class="icon-btn" data-close-modal="cancelModal">✕</button>
      </div>
      <p id="cancelModalMessage" class="modal-body-text"></p>
      <form action="../actions/student_cancel_booking.php" method="POST">
        <input type="hidden" id="cancelRequestId" name="request_id" value="" />
        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" data-close-modal="cancelModal">Keep Booking</button>
          <button type="submit" class="btn btn-danger">Yes, Cancel Booking</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../js/ui.js"></script>
</body>
</html>
