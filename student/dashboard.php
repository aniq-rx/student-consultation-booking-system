<?php
session_start();
// TODO: Enforce student session & include ../config/db.php
// if (($_SESSION['role'] ?? '') !== 'student') { header("Location: ../login.php"); exit; }

$basePath = '../';
$role = 'student';
$activePage = 'dashboard';
$pageTitle = 'Student Dashboard';
$pageSubtitle = 'Track your consultation requests and upcoming academic sessions';
$topbarCta = ['label' => '+ Book Consultation', 'href' => 'slots.php'];

// Retrieve the student's name and matric number using a prepared SQL query.
// This assumes db.php creates a PDO connection named $pdo and the table is `students`.
include '../config/db.php';
// $name = 'Null';
// $matricNo = $_SESSION['studentMatricNo'] ?? 'Null';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
//         echo "<script>
//             alert('You must be logged in first!');
//             window.location.href='../login_form.php';
//         </script>";
//         exit();
//     }

    // $user_id = $_SESSION['studentID'] ?? null;
    // $sql = "SELECT full_name, matric_no
    //         FROM students
    //         WHERE student_id = '$user_id'";

    // $result = $conn->query($sql);
    // $row = $result->fetch_assoc();

    // Store retrieved data into variables
    $name = $_SESSION['student_name'] ?? 'Null';
    $matricNo = $_SESSION['matric_no'] ?? 'Null';

// TODO: Replace dummy KPIs & lists with MySQL queries
$kpis = ['approved' => 1, 'pending' => 1, 'available' => 4, 'completed' => 1];
$navPill1 = $kpis['available'];
$navPill2 = $kpis['pending'];

$upcomingConsultation = [
    'lecturer' => 'Dr. Nurul Huda',
    'course' => 'STIWK2014',
    'date' => '2026-11-26',
    'time' => '02:00 PM - 02:30 PM',
    'type' => 'Online',
    'location' => 'Google Meet',
    'meeting_link' => 'https://meet.google.com/uum-se-201',
    'topic' => 'D2 Software Design Review (Use Case & ER Diagram)',
    'status' => 'Approved'
];

$recentRequests = [
    ['lecturer' => 'Dr. Nurul Huda', 'course' => 'STIWK2014', 'date' => '2026-11-26', 'time' => '02:00 PM - 02:30 PM', 'type' => 'Online', 'status' => 'Approved'],
    ['lecturer' => 'Assoc. Prof. Dr. Hafizuddin', 'course' => 'STID3014', 'date' => '2026-11-28', 'time' => '04:00 PM - 04:30 PM', 'type' => 'Face-to-Face', 'status' => 'Pending'],
    ['lecturer' => 'Dr. Syafiqah Roslan', 'course' => 'STIW2044', 'date' => '2026-11-20', 'time' => '11:00 AM - 11:30 AM', 'type' => 'Face-to-Face', 'status' => 'Completed'],
    ['lecturer' => 'Dr. Amirul Hakim', 'course' => 'STIW3084', 'date' => '2026-11-22', 'time' => '10:00 AM - 10:30 AM', 'type' => 'Online', 'status' => 'Rejected']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard — ConsultBook</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/dashboard.css" />
</head>
<body>
  <div class="app-shell">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-wrapper">
      <?php include '../includes/topbar.php'; ?>

      <main class="content">
        <div class="welcome-banner banner-student">
          <div>
            <span class="banner-tag">Semester A261 • Student Portal</span>
            <h2>Welcome back, <?= htmlspecialchars($name) ?></h2>
            <p>Browse lecturer consultation slots, request online or face-to-face sessions, and track approval statuses in one place.</p>
          </div>
          <div class="banner-actions">
            <a href="slots.php" class="btn btn-light">Browse Open Slots</a>
            <a href="consultations.php" class="btn btn-outline-light">My Requests</a>
          </div>
        </div>

        <div class="kpi-grid">
          <article class="kpi-card">
            <span class="kpi-label">Upcoming Approved</span>
            <strong class="kpi-value"><?= (int) $kpis['approved'] ?></strong>
            <span class="kpi-foot text-success">Confirmed sessions</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Pending Requests</span>
            <strong class="kpi-value"><?= (int) $kpis['pending'] ?></strong>
            <span class="kpi-foot text-warning">Awaiting lecturer review</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Available Slots</span>
            <strong class="kpi-value"><?= (int) $kpis['available'] ?></strong>
            <span class="kpi-foot text-primary">Open for booking</span>
          </article>
          <article class="kpi-card">
            <span class="kpi-label">Completed Sessions</span>
            <strong class="kpi-value"><?= (int) $kpis['completed'] ?></strong>
            <span class="kpi-foot text-muted">This semester</span>
          </article>
        </div>

        <div class="dashboard-grid">
          <article class="panel">
            <div class="panel-header">
              <h3>Next Upcoming Consultation</h3>
              <span class="status-badge status-approved">Approved</span>
            </div>
            <div class="spotlight-box">
              <div class="spotlight-header">
                <span class="spotlight-lecturer"><?= htmlspecialchars($upcomingConsultation['lecturer']) ?></span>
                <span class="type-badge <?= $upcomingConsultation['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($upcomingConsultation['type']) ?></span>
              </div>
              <div class="spotlight-meta">
                <span><strong>Course:</strong> <?= htmlspecialchars($upcomingConsultation['course']) ?></span>
                <span><strong>Schedule:</strong> <?= htmlspecialchars($upcomingConsultation['date']) ?> • <?= htmlspecialchars($upcomingConsultation['time']) ?></span>
                <span><strong>Topic:</strong> <?= htmlspecialchars($upcomingConsultation['topic']) ?></span>
                <span><strong>Platform:</strong> <?= htmlspecialchars($upcomingConsultation['location']) ?></span>
              </div>
              <div class="spotlight-actions">
                <a href="<?= htmlspecialchars($upcomingConsultation['meeting_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">Join Meeting</a>
                <button type="button" class="btn btn-ghost btn-sm" data-copy-link="<?= htmlspecialchars($upcomingConsultation['meeting_link']) ?>">Copy Link</button>
              </div>
            </div>
          </article>

          <article class="panel">
            <div class="panel-header">
              <h3>Recent Consultation Requests</h3>
              <a href="consultations.php" class="link-btn">View all →</a>
            </div>
            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr><th>Lecturer</th><th>Schedule</th><th>Type</th><th>Status</th></tr>
                </thead>
                <tbody>
                  <?php foreach ($recentRequests as $item): ?>
                    <tr>
                      <td><strong><?= htmlspecialchars($item['lecturer']) ?></strong><br /><small class="text-muted"><?= htmlspecialchars($item['course']) ?></small></td>
                      <td><?= htmlspecialchars($item['date']) ?><br /><small class="text-muted"><?= htmlspecialchars($item['time']) ?></small></td>
                      <td><span class="type-badge <?= $item['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($item['type']) ?></span></td>
                      <td><span class="status-badge status-<?= strtolower($item['status']) ?>"><?= htmlspecialchars($item['status']) ?></span></td>
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

  <div id="toastContainer" class="toast-container" aria-live="polite"></div>
  <script src="../js/ui.js"></script>
</body>
</html>
