<?php
$pageTitle = 'Login';
$bodyClass = 'page-auth';
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-visual">
        <div class="auth-visual-overlay"></div>
        <div class="auth-visual-content">
            <h2>Welcome Back</h2>
            <p>Sign in to access exclusive rates, manage bookings, and unlock member benefits.</p>
            <ul class="auth-features">
                <li><i class="fas fa-check-circle"></i> Member-only discounts</li>
                <li><i class="fas fa-check-circle"></i> Priority support</li>
                <li><i class="fas fa-check-circle"></i> Easy booking management</li>
            </ul>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="auth-form-container">
            <a href="<?= $pagePath ?>/index.php" class="auth-logo"><i class="fas fa-gem text-gold"></i> <?= SITE_NAME ?></a>
            <div class="glass-card auth-card animate-fade-up">
                <h3 class="mb-1">Sign In</h3>
                <p class="text-muted mb-4">Enter your credentials to continue</p>
                <form id="loginForm" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-group-lux">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="form-control lux-input" name="email" placeholder="you@example.com" required>
                        </div>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group-lux">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control lux-input" name="password" placeholder="••••••••" required minlength="6">
                            <button type="button" class="btn-toggle-password" tabindex="-1"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <a href="#" class="link-gold small" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-lux-primary w-100 mb-3" id="loginSubmitBtn">
                        <span class="btn-text">Sign In</span>
                        <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                </form>
                <p class="text-center mb-0">Don't have an account? <a href="<?= $pagePath ?>/register.php" class="link-gold">Register</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade lux-modal" id="forgotPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header border-0">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Enter your email and we'll send you a reset link.</p>
                <form id="forgotPasswordForm">
                    <div class="mb-3">
                        <input type="email" class="form-control lux-input" name="email" placeholder="Email address" required>
                    </div>
                    <button type="submit" class="btn btn-lux-primary w-100">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
