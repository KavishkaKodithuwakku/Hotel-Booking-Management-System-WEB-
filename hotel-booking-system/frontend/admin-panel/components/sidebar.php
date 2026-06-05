<?php
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF'] ?? 'index.php');

$navItems = [
    ['file' => 'index.php', 'icon' => 'fa-chart-line', 'label' => 'Dashboard'],
    ['file' => 'hotels.php', 'icon' => 'fa-hotel', 'label' => 'Hotels'],
    ['file' => 'rooms.php', 'icon' => 'fa-door-open', 'label' => 'Rooms'],
    ['file' => 'availability.php', 'icon' => 'fa-calendar-check', 'label' => 'Availability'],
    ['file' => 'bookings.php', 'icon' => 'fa-calendar-alt', 'label' => 'Bookings'],
    ['file' => 'customers.php', 'icon' => 'fa-user-tie', 'label' => 'Customers'],
    ['file' => 'users.php', 'icon' => 'fa-users', 'label' => 'Users'],
    ['file' => 'payments.php', 'icon' => 'fa-credit-card', 'label' => 'Payments'],
    ['file' => 'revenue.php', 'icon' => 'fa-coins', 'label' => 'Revenue'],
    ['file' => 'reports.php', 'icon' => 'fa-file-alt', 'label' => 'Reports'],
    ['file' => 'images.php', 'icon' => 'fa-images', 'label' => 'Hotel Images'],
    ['file' => 'reviews.php', 'icon' => 'fa-star', 'label' => 'Reviews'],
    ['file' => 'support.php', 'icon' => 'fa-headset', 'label' => 'Support'],
    ['file' => 'notifications.php', 'icon' => 'fa-bell', 'label' => 'Notifications'],
];
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand">
        <a href="<?= $pagePath ?>/index.php">
            <i class="fas fa-gem"></i>
            <span><?= ADMIN_SITE_NAME ?></span>
        </a>
    </div>
    <nav class="admin-nav">
        <?php foreach ($navItems as $item): ?>
        <a href="<?= $pagePath ?>/<?= $item['file'] ?>"
           class="admin-nav-link <?= $currentPage === $item['file'] ? 'active' : '' ?>">
            <i class="fas <?= $item['icon'] ?>"></i>
            <span><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>
    <div class="admin-sidebar-footer">
        <a href="<?= $userPanelPath ?>/index.php" class="admin-nav-link" target="_blank">
            <i class="fas fa-external-link-alt"></i>
            <span>View User Site</span>
        </a>
        <a href="<?= $pagePath ?>/login.php" class="admin-nav-link text-danger-soft">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
