<?php
/**
 * Role-Aware Shared Sidebar (includes/sidebar.php)
 * Expects: $role ('student' | 'lecturer' | 'admin'), $activePage, $basePath ('../')
 */
$basePath = $basePath ?? '../';
$role = $role ?? 'student';
$activePage = $activePage ?? 'dashboard';

$roleMeta = [
    'student' => [
        'portal' => 'Student Portal',
        'default_name' => 'Aina Sofea',
        'default_initials' => 'AS',
        'default_id_label' => 'Matric: 294812',
        'accent' => 'role-student',
        'links' => [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => '⊞', 'href' => 'student/dashboard.php', 'pill' => null],
            ['id' => 'slots', 'label' => 'Available Slots', 'icon' => '◷', 'href' => 'student/slots.php', 'pill' => $navPill1 ?? 4],
            ['id' => 'consultations', 'label' => 'My Consultations', 'icon' => '☰', 'href' => 'student/consultations.php', 'pill' => $navPill2 ?? 1],
        ]
    ],
    'lecturer' => [
        'portal' => 'Lecturer Portal',
        'default_name' => 'Dr. Nurul Huda',
        'default_initials' => 'NH',
        'default_id_label' => 'Staff ID: LEC101',
        'accent' => 'role-lecturer',
        'links' => [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => '⊞', 'href' => 'lecturer/dashboard.php', 'pill' => null],
            ['id' => 'slots', 'label' => 'Manage Slots', 'icon' => '◷', 'href' => 'lecturer/slots.php', 'pill' => $navPill1 ?? 5],
            ['id' => 'requests', 'label' => 'Booking Requests', 'icon' => '✓', 'href' => 'lecturer/requests.php', 'pill' => $navPill2 ?? 2],
        ]
    ],
    'admin' => [
        'portal' => 'Admin Portal',
        'default_name' => 'System Administrator',
        'default_initials' => 'AD',
        'default_id_label' => 'Admin ID: ADM001',
        'accent' => 'role-admin',
        'links' => [
            ['id' => 'dashboard', 'label' => 'System Overview', 'icon' => '⊞', 'href' => 'admin/dashboard.php', 'pill' => null],
            ['id' => 'users', 'label' => 'Manage Users', 'icon' => '👥', 'href' => 'admin/users.php', 'pill' => $navPill1 ?? 8],
            ['id' => 'consultations', 'label' => 'All Consultations', 'icon' => '☰', 'href' => 'admin/consultations.php', 'pill' => $navPill2 ?? 6],
        ]
    ]
];

// $cfg = $roleMeta[$role] ?? $roleMeta['student'];
// $displayName =  $_SESSION['student_name'] ?? $cfg['default_name'];
// $displayInitials = $_SESSION['student_initials'] ?? $cfg['default_initials'];
// $displayIdLabel = $_SESSION['studentMatricNo'] ?? $cfg['default_id_label'];

$cfg = $roleMeta[$role] ?? $roleMeta['student'];

if ($role === 'lecturer') {
    $displayName     = $_SESSION['lecturer_name'] ?? $cfg['default_name'];
    $displayInitials = $_SESSION['lecturer_initials'] ?? $cfg['default_initials'];
    $displayIdLabel  = isset($_SESSION['staff_id']) 
                        ? 'Staff ID: ' . $_SESSION['staff_id'] 
                        : $cfg['default_id_label'];
} elseif ($role === 'admin') {
    $displayName     = $_SESSION['admin_name'] ?? $cfg['default_name'];
    $displayInitials = $_SESSION['admin_initials'] ?? $cfg['default_initials'];
    $displayIdLabel  = isset($_SESSION['admin_id']) 
                        ? 'Admin ID: ' . $_SESSION['admin_id'] 
                        : $cfg['default_id_label'];
} else {
    $displayName     = $_SESSION['student_name'] ?? $cfg['default_name'];
    $displayInitials = $_SESSION['student_initials'] ?? $cfg['default_initials'];
    $displayIdLabel  = isset($_SESSION['matric_no']) 
                        ? 'Matric: ' . $_SESSION['matric_no'] 
                        : $cfg['default_id_label'];
}

?>
<aside class="sidebar <?= $cfg['accent'] ?>" id="sidebar">
  <div class="sidebar-header">
    <div class="logo-mark">SC</div>
    <div>
      <div class="logo-title">ConsultBook</div>
      <div class="logo-subtitle"><?= htmlspecialchars($cfg['portal']) ?></div>
    </div>
  </div>

  <nav class="sidebar-nav" aria-label="Primary navigation">
    <?php foreach ($cfg['links'] as $link): ?>
      <a href="<?= $basePath . $link['href'] ?>" class="nav-item <?= $activePage === $link['id'] ? 'active' : '' ?>">
        <span aria-hidden="true"><?= $link['icon'] ?></span>
        <span><?= htmlspecialchars($link['label']) ?></span>
        <?php if ($link['pill'] !== null): ?>
          <span class="nav-pill"><?= (int) $link['pill'] ?></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <!-- Quick Role Switcher for Easy Testing During Development -->
  <div class="role-switcher">
    <div class="role-switcher-label">Dummy Role Switch</div>
    <div class="role-switcher-links">
      <a href="<?= $basePath ?>student/dashboard.php" class="<?= $role === 'student' ? 'active' : '' ?>">Student</a>
      <a href="<?= $basePath ?>lecturer/dashboard.php" class="<?= $role === 'lecturer' ? 'active' : '' ?>">Lecturer</a>
      <a href="<?= $basePath ?>admin/dashboard.php" class="<?= $role === 'admin' ? 'active' : '' ?>">Admin</a>
    </div>
  </div>

  <div class="sidebar-footer">
    <div class="student-card">
      <div class="avatar"><?= htmlspecialchars($displayInitials) ?></div>
      <div>
        <div class="student-name"><?= htmlspecialchars($displayName) ?></div>
        <div class="student-id"><?= htmlspecialchars($displayIdLabel) ?></div>
      </div>
    </div>
    <form action="<?= $basePath ?>actions/logout.php" method="POST">
      <button type="submit" class="btn btn-ghost btn-full">Sign Out</button>
    </form>
  </div>
</aside>
