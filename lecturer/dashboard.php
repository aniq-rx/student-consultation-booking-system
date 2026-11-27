<?php
session_start();
// TODO: Enforce lecturer session & include ../config/db.php
// if (($_SESSION['role'] ?? '') !== 'lecturer') { header("Location: ../login.php"); exit; }

$basePath = '../';
$role = 'lecturer';
$activePage = 'dashboard';
$pageTitle = 'Lecturer Dashboard';
$pageSubtitle = 'Manage your consultation slots, review student requests, and view upcoming sessions';
$topbarCta = ['label' => '+ Create Slot', 'href' => 'slots.php'];

// $currentUser = [
//     'name' => $_SESSION['user_name'] ?? 'Dr. Nurul Huda',
//     'initials' => 'NH',
//     'id_label' => 'Staff ID: ' . ($_SESSION['user_code'] ?? 'LEC101')
// ];

    $name = $_SESSION['lecturer_name'] ?? 'Null';
    $matricNo = $_SESSION['lecturer_id'] ?? 'Null';

// TODO: Replace dummy data with MySQL queries for this lecturer_id
$kpis = [
    'pending_requests' => 2,
    'upcoming_approved' => 2,
    'open_slots' => 3,
    'completed' => 9
];
$navPill1 = $kpis['open_slots'];
$navPill2 = $kpis['pending_requests'];

$upcomingSessions = [
    ['student' => 'Aina Sofea (294812)', 'course' => 'STIWK2014', 'date' => '2026-11-26', 'time' => '02:00 PM - 02:30 PM', 'type' => 'Online', 'location' => 'Google Meet', 'meeting_link' => 'https://meet.google.com/uum-se-201', 'topic' => 'D2 Software Design Review'],
    ['student' => 'Muhammad Danish (295104)', 'course' => 'STIWK2014', 'date' => '2026-11-27', 'time' => '11:00 AM - 11:30 AM', 'type' => 'Face-to-Face', 'location' => 'SOC Building, Room 4.02', 'meeting_link' => '', 'topic' => 'Class Diagram Relationships']
];

$pendingPreview = [
    ['request_id' => 301, 'student' => 'Nurul Izzah (294990)', 'course' => 'STIWK2014', 'date' => '2026-11-28', 'time' => '10:00 AM - 10:30 AM', 'type' => 'Online', 'topic' => 'Double Booking Validation Logic'],
    ['request_id' => 302, 'student' => 'Faris Haikal (295211)', 'course' => 'STIWK2014', 'date' => '2026-11-29', 'time' => '03:00 PM - 03:30 PM', 'type' => 'Face-to-Face', 'topic' => 'Test Cases Documentation']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lecturer Dashboard — ConsultBook</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/dashboard.css" />
</head>
<body>
  <div class="app-shell">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-wrapper">
      <?php include '../includes/topbar.php'; ?>

      <main class="content">
        <div class="welcome-banner banner-lecturer">
          <div>
            <span class="banner-tag">School of Computing • Lecturer Portal</span>
            <h2>Welcome, <?= htmlspecialchars($name ?? 'Null') ?></h2>
            <p>Publish new Face-to-Face or Online consultation slots, approve student requests, and mark completed sessions.</p>
          </div>
          <div class="banner-actions">
            <a href="slots.php" class="btn btn-light">Create New Slot</a>
            <a href="requests.php" class="btn btn-outline-light">Review Pending (<?= (int) $kpis['pending_requests'] ?>)</a>
          </div>
        </div>

        <div class="kpi-grid">
          <article class="kpi-card">
            <span class="kpi-label">Pending Requests</span>
            <strong class="kpi-value"><?= (int) $kpis['pending_requests'] ?></strong>
            <span class="kpi-foot text-warning">Action required</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Upcoming Approved</span>
            <strong class="kpi-value"><?= (int) $kpis['upcoming_approved'] ?></strong>
            <span class="kpi-foot text-success">Confirmed sessions</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Open Slots</span>
            <strong class="kpi-value"><?= (int) $kpis['open_slots'] ?></strong>
            <span class="kpi-foot text-primary">Available to students</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Completed Sessions</span>
            <strong class="kpi-value"><?= (int) $kpis['completed'] ?></strong>
            <span class="kpi-foot text-muted">This semester</span>
          </article>
        </div>

        <div class="dashboard-grid">
          <!-- Upcoming Approved Consultations -->
          <article class="panel">
            <div class="panel-header">
              <h3>Upcoming Approved Consultations</h3>
              <a href="requests.php?status=Approved" class="link-btn">Manage →</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <?php foreach ($upcomingSessions as $session): ?>
                <div class="spotlight-box">
                  <div class="spotlight-header">
                    <span class="spotlight-lecturer"><?= htmlspecialchars($session['student']) ?></span>
                    <span class="type-badge <?= $session['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($session['type']) ?></span>
                  </div>
                  <div class="spotlight-meta">
                    <span><strong>Schedule:</strong> <?= htmlspecialchars($session['date']) ?> • <?= htmlspecialchars($session['time']) ?></span>
                    <span><strong>Topic:</strong> <?= htmlspecialchars($session['topic']) ?></span>
                    <span><strong><?= $session['type'] === 'Online' ? 'Platform' : 'Location' ?>:</strong> <?= htmlspecialchars($session['location']) ?></span>
                  </div>
                  <?php if ($session['type'] === 'Online' && !empty($session['meeting_link'])): ?>
                    <div class="spotlight-actions">
                      <a href="<?= htmlspecialchars($session['meeting_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">Start Meeting</a>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </article>

          <!-- Pending Student Requests Queue -->
          <article class="panel">
            <div class="panel-header">
              <h3>Pending Student Requests</h3>
              <a href="requests.php?status=Pending" class="link-btn">Review all →</a>
            </div>
            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr><th>Student</th><th>Schedule</th><th>Type</th><th>Topic</th></tr>
                </thead>
                <tbody>
                  <?php foreach ($pendingPreview as $req): ?>
                    <tr>
                      <td><strong><?= htmlspecialchars($req['student']) ?></strong><br /><small class="text-muted"><?= htmlspecialchars($req['course']) ?></small></td>
                      <td><?= htmlspecialchars($req['date']) ?><br /><small class="text-muted"><?= htmlspecialchars($req['time']) ?></small></td>
                      <td><span class="type-badge <?= $req['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($req['type']) ?></span></td>
                      <td><?= htmlspecialchars($req['topic']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </article>
        </div>
      </main>
    </div>
  </div>
  <script src="../js/ui.js"></script>
</body>
</html>
