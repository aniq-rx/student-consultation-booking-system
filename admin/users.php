<?php
session_start();
$basePath = '../';
$role = 'admin';
$activePage = 'users';
$pageTitle = 'Manage Users';
$pageSubtitle = 'Register new students or lecturers and manage existing accounts';

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$roleFilter = $_GET['role'] ?? 'All';

// TODO: Replace dummy users with MySQL SELECT * FROM users
$dummyUsers = [
    ['user_id' => 1, 'code' => '294812', 'name' => 'Aina Sofea', 'email' => 'aina.sofea@student.uum.edu.my', 'role' => 'student', 'department' => 'Software Engineering', 'status' => 'Active'],
    ['user_id' => 2, 'code' => '295104', 'name' => 'Muhammad Danish', 'email' => 'm.danish@student.uum.edu.my', 'role' => 'student', 'department' => 'Software Engineering', 'status' => 'Active'],
    ['user_id' => 3, 'code' => 'LEC101', 'name' => 'Dr. Nurul Huda', 'email' => 'nurulhuda@uum.edu.my', 'role' => 'lecturer', 'department' => 'Software Engineering', 'status' => 'Active'],
    ['user_id' => 4, 'code' => 'LEC102', 'name' => 'Assoc. Prof. Dr. Hafizuddin', 'email' => 'hafizuddin@uum.edu.my', 'role' => 'lecturer', 'department' => 'Database Systems', 'status' => 'Active'],
    ['user_id' => 5, 'code' => 'ADM001', 'name' => 'System Administrator', 'email' => 'admin@uum.edu.my', 'role' => 'admin', 'department' => 'IT Services', 'status' => 'Active'],
];

$users = $roleFilter === 'All'
    ? $dummyUsers
    : array_filter($dummyUsers, fn($u) => $u['role'] === strtolower($roleFilter));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Users — Admin Portal</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/admin.css" />
</head>
<body>
  <div class="app-shell">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-wrapper">
      <?php include '../includes/topbar.php'; ?>

      <main class="content">
        <?php if ($flashSuccess !== ''): ?><div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div><?php endif; ?>
        <?php if ($flashError !== ''): ?><div class="alert alert-error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>

        <!-- Add New User Form -->
        <section class="panel" style="margin-bottom: 24px;">
          <div class="panel-header">
            <h3>Add New User Account</h3>
          </div>
          <form action="../actions/admin_add_user.php" method="POST" class="admin-form-grid">
            <div class="form-group">
              <label for="matric_or_staff_id">Matric / Staff ID <span class="required">*</span></label>
              <input type="text" id="matric_or_staff_id" name="matric_or_staff_id" placeholder="e.g. 295300 or LEC108" required />
            </div>
            <div class="form-group">
              <label for="full_name">Full Name <span class="required">*</span></label>
              <input type="text" id="full_name" name="full_name" placeholder="e.g. Dr. Ahmad Faiz" required />
            </div>
            <div class="form-group">
              <label for="email">University Email <span class="required">*</span></label>
              <input type="email" id="email" name="email" placeholder="name@uum.edu.my" required />
            </div>
            <div class="form-group">
              <label for="user_role">Role <span class="required">*</span></label>
              <select id="user_role" name="user_role">
                <option value="student">Student</option>
                <option value="lecturer">Lecturer</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label for="department">Department / Program</label>
              <input type="text" id="department" name="department" placeholder="e.g. Software Engineering" />
            </div>
            <div class="form-group" style="justify-content: flex-end;">
              <button type="submit" class="btn btn-primary">+ Register User</button>
            </div>
          </form>
        </section>

        <!-- Filter & Users Table -->
        <section class="panel">
          <div class="panel-header">
            <h3>Registered Users</h3>
            <div class="role-filter-links">
              <?php foreach (['All', 'Student', 'Lecturer', 'Admin'] as $r): ?>
                <a href="users.php?role=<?= urlencode($r) ?>" class="btn btn-sm <?= $roleFilter === $r ? 'btn-primary' : 'btn-ghost' ?>"><?= $r ?></a>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr><th>ID</th><th>Name & Email</th><th>Role</th><th>Department</th><th>Status</th><th>Action</th></tr>
              </thead>
              <tbody>
                <?php foreach ($users as $u): ?>
                  <tr>
                    <td><strong><?= htmlspecialchars($u['code']) ?></strong></td>
                    <td><?= htmlspecialchars($u['name']) ?><br /><small class="text-muted"><?= htmlspecialchars($u['email']) ?></small></td>
                    <td><span class="type-badge <?= $u['role'] === 'lecturer' ? 'type-online' : 'type-f2f' ?>"><?= ucfirst(htmlspecialchars($u['role'])) ?></span></td>
                    <td><?= htmlspecialchars($u['department']) ?></td>
                    <td><span class="status-badge status-approved"><?= htmlspecialchars($u['status']) ?></span></td>
                    <td>
                      <?php if ($u['role'] !== 'admin'): ?>
                        <form action="../actions/admin_delete_user.php" method="POST" onsubmit="return confirm('Delete this user?');">
                          <input type="hidden" name="user_id" value="<?= (int) $u['user_id'] ?>" />
                          <button type="submit" class="btn btn-ghost btn-sm">Remove</button>
                        </form>
                      <?php else: ?>
                        <span class="text-muted" style="font-size: 0.8rem;">Protected</span>
                      <?php endif; ?>
                    </td>
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
