<?php
session_start();
$basePath = '../';
$role = 'lecturer';
$activePage = 'requests';
$pageTitle = 'Student Booking Requests';
$pageSubtitle = 'Approve or reject incoming requests and mark approved sessions as completed';

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$statusFilter = $_GET['status'] ?? 'All';
$tabs = ['All', 'Pending', 'Approved', 'Completed', 'Rejected'];

// TODO: Replace dummy requests with MySQL SELECT joined with users & consultation_slots
$dummyRequests = [
    ['request_id' => 301, 'student' => 'Nurul Izzah (294990)', 'course' => 'STIWK2014', 'date' => '2026-11-28', 'time' => '10:00 AM - 10:30 AM', 'type' => 'Online', 'location' => 'Google Meet', 'meeting_link' => 'https://meet.google.com/uum-se-301', 'topic' => 'Double Booking Validation Logic', 'status' => 'Pending', 'remark' => ''],
    ['request_id' => 302, 'student' => 'Faris Haikal (295211)', 'course' => 'STIWK2014', 'date' => '2026-11-29', 'time' => '03:00 PM - 03:30 PM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Room 4.02', 'meeting_link' => '', 'topic' => 'Test Cases Documentation', 'status' => 'Pending', 'remark' => ''],
    ['request_id' => 201, 'student' => 'Aina Sofea (294812)', 'course' => 'STIWK2014', 'date' => '2026-11-26', 'time' => '02:00 PM - 02:30 PM', 'type' => 'Online', 'location' => 'Google Meet', 'meeting_link' => 'https://meet.google.com/uum-se-201', 'topic' => 'D2 Software Design Review', 'status' => 'Approved', 'remark' => 'Please prepare diagrams.'],
    ['request_id' => 299, 'student' => 'Haziq Zulkifli (294711)', 'course' => 'STIWK2014', 'date' => '2026-11-18', 'time' => '09:00 AM - 09:30 AM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Room 4.02', 'meeting_link' => '', 'topic' => 'D1 Requirements Scope Review', 'status' => 'Completed', 'remark' => 'Requirements approved.'],
];

$requests = $statusFilter === 'All'
    ? $dummyRequests
    : array_filter($dummyRequests, fn($r) => $r['status'] === $statusFilter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking Requests — Lecturer Portal</title>
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
            <a href="requests.php?status=<?= urlencode($tab) ?>" class="tab-btn <?= $statusFilter === $tab ? 'active' : '' ?>"><?= htmlspecialchars($tab) ?></a>
          <?php endforeach; ?>
        </div>

        <div class="consultations-list">
          <?php foreach ($requests as $req): ?>
            <article class="consultation-card">
              <div class="consultation-main">
                <div class="consultation-header">
                  <span class="consultation-topic"><?= htmlspecialchars($req['topic']) ?></span>
                  <span class="status-badge status-<?= strtolower($req['status']) ?>"><?= htmlspecialchars($req['status']) ?></span>
                  <span class="type-badge <?= $req['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($req['type']) ?></span>
                </div>
                <div class="consultation-meta"><strong>Student:</strong> <?= htmlspecialchars($req['student']) ?> • <?= htmlspecialchars($req['course']) ?></div>
                <div class="consultation-meta"><strong>Schedule:</strong> <?= htmlspecialchars($req['date']) ?> • <?= htmlspecialchars($req['time']) ?> • <?= htmlspecialchars($req['location']) ?></div>
                <?php if (!empty($req['remark'])): ?>
                  <div class="remark-box"><strong>Your Remark:</strong> <?= htmlspecialchars($req['remark']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Modification 1: Pending -> Approved -> Completed, Pending -> Rejected -->
              <div class="consultation-actions">
                <?php if ($req['status'] === 'Pending'): ?>
                  <form action="../actions/lecturer_update_request.php" method="POST">
                    <input type="hidden" name="request_id" value="<?= (int) $req['request_id'] ?>" />
                    <input type="hidden" name="new_status" value="Approved" />
                    <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                  </form>
                  <button type="button" class="btn btn-danger btn-sm"
                    data-open-reject
                    data-request-id="<?= (int) $req['request_id'] ?>"
                    data-student="<?= htmlspecialchars($req['student']) ?>">
                    Reject
                  </button>
                <?php elseif ($req['status'] === 'Approved'): ?>
                  <form action="../actions/lecturer_update_request.php" method="POST">
                    <input type="hidden" name="request_id" value="<?= (int) $req['request_id'] ?>" />
                    <input type="hidden" name="new_status" value="Completed" />
                    <button type="submit" class="btn btn-primary btn-sm">Mark Completed</button>
                  </form>
                <?php else: ?>
                  <span class="text-muted" style="font-size: 0.82rem;">No further action</span>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Reject Request Modal with Remark -->
  <div id="rejectModal" class="modal-backdrop hidden" role="dialog" aria-modal="true">
    <div class="modal-card modal-sm">
      <div class="modal-header">
        <h3>Reject Consultation Request</h3>
        <button type="button" class="icon-btn" data-close-modal="rejectModal">✕</button>
      </div>
      <p id="rejectModalStudent" class="modal-body-text"></p>
      <form action="../actions/lecturer_update_request.php" method="POST">
        <input type="hidden" id="rejectRequestId" name="request_id" value="" />
        <input type="hidden" name="new_status" value="Rejected" />
        <div class="form-group">
          <label for="lecturer_remark">Reason / Remark for Student <span class="required">*</span></label>
          <textarea id="lecturer_remark" name="lecturer_remark" rows="3" placeholder="e.g. Faculty meeting clash. Please choose another slot." required></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" data-close-modal="rejectModal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm Rejection</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../js/ui.js"></script>
</body>
</html>
