<?php
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
?>
<nav class="navbar navbar-expand-lg lux-navbar fixed-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand lux-brand" href="<?= $pagePath ?>/index.php">
            <i class="fas fa-gem text-gold me-2"></i><?= SITE_NAME ?>
        </a>
        <button class="navbar-toggler lux-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="<?= $pagePath ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'hotels.php' ? 'active' : '' ?>" href="<?= $pagePath ?>/hotels.php">Hotels</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'booking-history.php' ? 'active' : '' ?>" href="<?= $pagePath ?>/booking-history.php">My Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>" href="<?= $pagePath ?>/contact.php">Contact</a>
                </li>
            </ul>
            <div class="navbar-actions d-flex align-items-center gap-2">
                <a href="<?= $pagePath ?>/login.php" class="btn btn-lux-ghost btn-sm">Login</a>
                <a href="<?= $pagePath ?>/register.php" class="btn btn-lux-primary btn-sm">Register</a>
                <a href="<?= $pagePath ?>/profile.php" class="btn btn-lux-outline btn-sm d-none d-md-inline-flex" title="Profile">
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="navbar-spacer"></div>
