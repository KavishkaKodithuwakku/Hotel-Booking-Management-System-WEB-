<?php
$pageHeading = $pageHeading ?? $pageTitle ?? 'Dashboard';
$pageSubheading = $pageSubheading ?? '';
?>
<header class="admin-topbar">
    <div class="admin-topbar-left">
        <button type="button" class="btn btn-admin-icon d-lg-none" id="sidebarToggle" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <h1 class="admin-page-title"><?= htmlspecialchars($pageHeading) ?></h1>
            <?php if ($pageSubheading): ?>
            <p class="admin-page-subtitle"><?= htmlspecialchars($pageSubheading) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="admin-topbar-right">
        <div class="admin-search d-none d-md-flex">
            <i class="fas fa-search"></i>
            <input type="search" class="form-control" placeholder="Search..." id="adminGlobalSearch">
        </div>
        <a href="<?= $pagePath ?>/notifications.php" class="btn btn-admin-icon position-relative">
            <i class="fas fa-bell"></i>
            <span class="admin-badge-dot">3</span>
        </a>
        <div class="admin-user-pill">
            <div class="admin-avatar">AD</div>
            <div class="d-none d-sm-block">
                <strong>Admin User</strong>
                <small>Super Admin</small>
            </div>
        </div>
    </div>
</header>
