<?php
$pageTitle = 'Admin Login';
$bodyClass = 'page-admin-auth';
$isAuthPage = true;
require_once __DIR__ . '/includes/header.php';
?>

<div class="admin-auth-card">
    <a href="<?= $pagePath ?>/login.php" class="auth-logo">
        <i class="fas fa-gem"></i> <?= ADMIN_SITE_NAME ?>
    </a>
    <h3 class="text-center mb-1">Sign In</h3>
    <p class="text-center text-muted mb-4">Access the hotel management console</p>
    <form id="adminLoginForm" novalidate>
        <div class="mb-3">
            <label class="admin-form-label">Admin Email</label>
            <input type="email" class="form-control admin-input" name="email" placeholder="admin@luxestay.com" required>
            <div class="invalid-feedback">Enter a valid email.</div>
        </div>
        <div class="mb-3">
            <label class="admin-form-label">Password</label>
            <input type="password" class="form-control admin-input" name="password" placeholder="••••••••" required minlength="6">
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="rememberAdmin">
            <label class="form-check-label" for="rememberAdmin">Remember me</label>
        </div>
        <button type="submit" class="btn btn-admin-primary w-100 mb-3">Sign In to Dashboard</button>
        <p class="text-center small text-muted mb-0">
            Demo: use any email except <code>fail@admin.com</code>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
