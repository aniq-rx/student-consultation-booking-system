<?php
session_start();
$basePath = '../';
$role = 'admin';
$activePage = 'consultations';
$pageTitle = 'All University Consultations';
$pageSubtitle = 'Audit all student-lecturer consultation bookings across the system';

$statusFilter = $_GET['status'] ?? 'All';
$typeFilter = $_GET['type'] ?? 'All';

// TODO: Replace dummy data with MySQL JOIN across consultation_requests, consultation_slots, and users
$dummyRecords = [
    ['id' => 201, 'student' => 'Aina Sofea (294812)', 'lecturer' => 'Dr. Nurul Huda', 'course' => 'STIWK2014', 'date' => '2026-11-26', 'time' => '02:00 PM', 'type' => 'Online', 'status' => 'Approved'],
    ['id' => 202, 'student' => 'Aina Sofea (294812)', 'lecturer' => 'Assoc. Prof. Dr. Hafizuddin', 'course' => 'STID3014', 'date' => '2026-11-28', 'time' => '04:00 PM', 'type' => 'Face-to-Face', 'status' => 'Pending'],
    ['id' => 203, 'student' => 'Aina Sofea (294812)', 'lecturer' => 'Dr. Syafiqah Roslan', 'course' => 'STIW2044', 'date' => '2026-11-20', 'time' => '11:00 AM', 'type' => 'Face-to-Face', 'status' => 'Completed'],
    ['id' => 204, 'student' => 'Aina Sofea (294812)', 'lecturer' => 'Dr. Amirul Hakim', 'course' => 'STIW3084', 'date' => '2026-11-22', 'time' => '10:00 AM', 'type' => 'Online', 'status' => 'Rejected'],
    ['id' => 301, 'student' => 'Nurul Izzah (294990)', 'lecturer' => 'Dr. Nurul Huda', 'course' => 'STIWK2014', 'date' => '2026-11-28', 'time' => '10:00 AM', 'type' => 'Online', 'status' => 'Pending'],
];

$records = array_filter($dummyRecords, function ($r) use ($statusFilter, $typeFilter) {
    $mStatus = $statusFilter === 'All' || $r['status'] === $statusFilter;
    $mType = $typeFilter === 'All' || $r['type'] === $typeFilter;
    return $mStatus && $mType;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Consultations — Admin Portal</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/admin.css" />
</head>
<body>
  <div class="app-shell">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-wrapper">
      <?php include '../includes/topbar.php'; ?>

      <main class="content">
        <form action="consultations.php" method="GET" class="panel" style="margin-bottom: 20px;">
          <div class="admin-filter-row">
            <div class="form-group">
              <label for="status">Filter by Status</label>
              <select id="status" name="status">
                <?php foreach (['All', 'Pending', 'Approved', 'Completed', 'Rejected'] as $s): ?>
                  <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="type">Filter by Type</label>
              <select id="type" name="type">
                <?php foreach (['All', 'Online', 'Face-to-Face'] as $t): ?>
                  <option value="<?= $t ?>" <?= $typeFilter === $t ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group" style="flex-direction: row; gap: 8px; align-items: flex-end;">
              <button type="submit" class="btn btn-primary">Apply Filter</button>
              <a href="consultations.php" class="btn btn-ghost">Reset</a>
            </div>
          </div>
        </form>

        <section class="panel">
          <div class="panel-header">
            <h3>Consultation Records (<?= count($records) ?>)</h3>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr><th>ID</th><th>Student</th><th>Lecturer</th><th>Course</th><th>Date & Time</th><th>Type</th><th>Status</th></tr>
              </thead>
              <tbody>
                <?php foreach ($records as $r): ?>
                  <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><strong><?= htmlspecialchars($r['student']) ?></strong></td>
                    <td><?= htmlspecialchars($r['lecturer']) ?></td>
                    <td><?= htmlspecialchars($r['course']) ?></td>
                    <td><?= htmlspecialchars($r['date']) ?> • <?= htmlspecialchars($r['time']) ?></td>
                    <td><span class="type-badge <?= $r['type'] === 'Online' ? 'type-online' : 'type-f2f' ?>"><?= htmlspecialchars($r['type']) ?></span></td>
                    <td><span class="status-badge status-<?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </div>
  <script src="../js/ui.js"></script>
</body>
</html>
