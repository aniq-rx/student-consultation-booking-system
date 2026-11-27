<?php
session_start();
// TODO: Enforce admin session & include ../config/db.php
// if (($_SESSION['role'] ?? '') !== 'admin') { header("Location: ../login.php"); exit; }

$basePath = '../';
$role = 'admin';
$activePage = 'dashboard';
$pageTitle = 'Admin System Overview';
$pageSubtitle = 'Monitor university users, consultation activity, and booking status breakdown';
$topbarCta = ['label' => '+ Add User', 'href' => 'users.php'];

$currentUser = [
    'name' => $_SESSION['user_name'] ?? 'System Administrator',
    'initials' => 'AD',
    'id_label' => 'Admin ID: ' . ($_SESSION['user_code'] ?? 'ADM001')
];

// TODO: Replace dummy stats with SQL COUNT() queries
$stats = [
    'total_students' => 142,
    'total_lecturers' => 18,
    'active_slots' => 24,
    'total_bookings' => 64
];

$statusBreakdown = [
    ['label' => 'Approved', 'count' => 22, 'percent' => 34, 'class' => 'bar-success'],
    ['label' => 'Pending', 'count' => 14, 'percent' => 22, 'class' => 'bar-warning'],
    ['label' => 'Completed', 'count' => 21, 'percent' => 33, 'class' => 'bar-info'],
    ['label' => 'Rejected', 'count' => 7, 'percent' => 11, 'class' => 'bar-danger'],
];

$recentActivity = [
    ['actor' => 'Aina Sofea (Student)', 'action' => 'Requested STIWK2014 Online slot with Dr. Nurul Huda', 'time' => '15 mins ago'],
    ['actor' => 'Dr. Nurul Huda (Lecturer)', 'action' => 'Published 2 new consultation slots for Week 10', 'time' => '1 hour ago'],
    ['actor' => 'Assoc. Prof. Dr. Hafizuddin (Lecturer)', 'action' => 'Approved consultation request #205', 'time' => '3 hours ago'],
    ['actor' => 'Admin (ADM001)', 'action' => 'Registered new lecturer account LEC105', 'time' => 'Yesterday'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard — ConsultBook</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/dashboard.css" />
  <link rel="stylesheet" href="../css/admin.css" />
</head>
<body>
  <div class="app-shell">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-wrapper">
      <?php include '../includes/topbar.php'; ?>

      <main class="content">
        <div class="welcome-banner banner-admin">
          <div>
            <span class="banner-tag">STIWK2014 • Administrator Control Center</span>
            <h2>System Administration Dashboard</h2>
            <p>Manage student and lecturer accounts, audit consultation bookings, and monitor overall system utilization.</p>
          </div>
          <div class="banner-actions">
            <a href="users.php" class="btn btn-light">Manage Users</a>
            <a href="consultations.php" class="btn btn-outline-light">Audit All Bookings</a>
          </div>
        </div>

        <div class="kpi-grid">
          <article class="kpi-card">
            <span class="kpi-label">Registered Students</span>
            <strong class="kpi-value"><?= (int) $stats['total_students'] ?></strong>
            <span class="kpi-foot text-primary">Active accounts</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Registered Lecturers</span>
            <strong class="kpi-value"><?= (int) $stats['total_lecturers'] ?></strong>
            <span class="kpi-foot text-success">Across 4 departments</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Open Slots</span>
            <strong class="kpi-value"><?= (int) $stats['active_slots'] ?></strong>
            <span class="kpi-foot text-warning">Available this week</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Total Bookings</span>
            <strong class="kpi-value"><?= (int) $stats['total_bookings'] ?></strong>
            <span class="kpi-foot text-muted">Semester A261</span>
          </article>
        </div>

        <div class="dashboard-grid">
          <!-- Booking Status Breakdown -->
          <article class="panel">
            <div class="panel-header">
              <h3>Consultation Status Breakdown</h3>
              <a href="consultations.php" class="link-btn">View records →</a>
            </div>
            <div class="breakdown-list">
              <?php foreach ($statusBreakdown as $row): ?>
                <div class="breakdown-item">
                  <div class="breakdown-head">
                    <span><?= htmlspecialchars($row['label']) ?></span>
                    <strong><?= (int) $row['count'] ?> (<?= (int) $row['percent'] ?>%)</strong>
                  </div>
                  <div class="progress-track">
                    <div class="progress-fill <?= $row['class'] ?>" style="width: <?= (int) $row['percent'] ?>%;"></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </article>

          <!-- Recent System Activity -->
          <article class="panel">
            <div class="panel-header">
              <h3>Recent System Activity</h3>
            </div>
            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr><th>User</th><th>Activity</th><th>Time</th></tr>
                </thead>
                <tbody>
                  <?php foreach ($recentActivity as $act): ?>
                    <tr>
                      <td><strong><?= htmlspecialchars($act['actor']) ?></strong></td>
                      <td><?= htmlspecialchars($act['action']) ?></td>
                      <td class="text-muted"><?= htmlspecialchars($act['time']) ?></td>
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
