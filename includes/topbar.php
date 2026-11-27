<?php
/**
 * Role-Aware Shared Topbar (includes/topbar.php)
 */
$basePath = $basePath ?? '../';
$role = $role ?? 'student';
$pageTitle = $pageTitle ?? 'Dashboard';
$pageSubtitle = $pageSubtitle ?? '';
$topbarCta = $topbarCta ?? null; // ['label' => '...', 'href' => '...']

$notifications = $notifications ?? [
    ['message' => 'System ready for PHP + MySQL integration.', 'time' => 'Just now', 'unread' => true],
    ['message' => 'Consultation schedule updated for Semester A261.', 'time' => '2 hours ago', 'unread' => false]
];
$unreadCount = count(array_filter($notifications, fn($n) => !empty($n['unread'])));
?>
<header class="topbar">
  <div class="topbar-left">
    <button type="button" id="menuToggle" class="icon-btn mobile-only" aria-label="Toggle navigation">☰</button>
    <div>
      <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
      <p class="page-subtitle"><?= htmlspecialchars($pageSubtitle) ?></p>
    </div>
  </div>

  <div class="topbar-right">
    <?php if ($topbarCta): ?>
      <a href="<?= htmlspecialchars($topbarCta['href']) ?>" class="btn btn-primary"><?= htmlspecialchars($topbarCta['label']) ?></a>
    <?php endif; ?>

    <div class="notif-wrapper">
      <button type="button" id="notifBtn" class="icon-btn" aria-label="Notifications" aria-expanded="false">
        <span aria-hidden="true">🔔</span>
        <?php if ($unreadCount > 0): ?>
          <span class="notif-badge"><?= $unreadCount ?></span>
        <?php endif; ?>
      </button>

      <div id="notifDropdown" class="notif-dropdown hidden" role="menu">
        <div class="notif-header">
          <span>Notifications</span>
          <span class="text-muted" style="font-size: 0.78rem;"><?= $unreadCount ?> unread</span>
        </div>
        <div class="notif-list">
          <?php foreach ($notifications as $notif): ?>
            <div class="notif-item <?= !empty($notif['unread']) ? 'unread' : '' ?>">
              <?= htmlspecialchars($notif['message']) ?>
              <span class="notif-time"><?= htmlspecialchars($notif['time']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</header>
