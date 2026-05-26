<?php
$pageTitle = 'Register';
$bodyClass = 'page-auth';
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-visual">
        <div class="auth-visual-overlay"></div>
        <div class="auth-visual-content">
            <h2>Join <?= SITE_NAME ?></h2>
            <p>Create your account and unlock access to the world's finest luxury hotels.</p>
            <ul class="auth-features">
                <li><i class="fas fa-gem"></i> Exclusive member rates</li>
                <li><i class="fas fa-gem"></i> Free cancellation on select stays</li>
                <li><i class="fas fa-gem"></i> Loyalty rewards program</li>
            </ul>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="auth-form-container">
            <a href="<?= $pagePath ?>/index.php" class="auth-logo"><i class="fas fa-gem text-gold"></i> <?= SITE_NAME ?></a>
            <div class="glass-card auth-card animate-fade-up">
                <h3 class="mb-1">Create Account</h3>
                <p class="text-muted mb-4">Fill in your details to get started</p>
                <form id="registerForm" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control lux-input" name="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control lux-input" name="lastName" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control lux-input" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control lux-input" name="phone" placeholder="+1 234 567 8900">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control lux-input" name="password" required minlength="8" id="regPassword">
                        <div class="password-strength mt-2" id="passwordStrength"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control lux-input" name="confirmPassword" required>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="termsAgree" required>
                        <label class="form-check-label" for="termsAgree">I agree to the <a href="#" class="link-gold">Terms</a> and <a href="#" class="link-gold">Privacy Policy</a></label>
                    </div>
                    <button type="submit" class="btn btn-lux-primary w-100 mb-3" id="registerSubmitBtn">
                        <span class="btn-text">Create Account</span>
                        <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                </form>
                <p class="text-center mb-0">Already have an account? <a href="<?= $pagePath ?>/login.php" class="link-gold">Sign In</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
