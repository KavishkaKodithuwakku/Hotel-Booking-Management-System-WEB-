<footer class="lux-footer">
    <div class="footer-overlay"></div>
    <div class="container position-relative">
        <div class="row g-4 py-5">
            <div class="col-lg-4 col-md-6">
                <h4 class="footer-brand"><i class="fas fa-gem text-gold me-2"></i><?= SITE_NAME ?></h4>
                <p class="footer-text">Experience world-class luxury stays curated for discerning travelers. Your journey to exceptional hospitality begins here.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Explore</h6>
                <ul class="footer-links">
                    <li><a href="<?= $pagePath ?>/index.php">Home</a></li>
                    <li><a href="<?= $pagePath ?>/hotels.php">Hotels</a></li>
                    <li><a href="<?= $pagePath ?>/booking-history.php">My Bookings</a></li>
                    <li><a href="<?= $pagePath ?>/contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Account</h6>
                <ul class="footer-links">
                    <li><a href="<?= $pagePath ?>/login.php">Login</a></li>
                    <li><a href="<?= $pagePath ?>/register.php">Register</a></li>
                    <li><a href="<?= $pagePath ?>/profile.php">Profile</a></li>
                    <li><a href="<?= $pagePath ?>/booking.php">Book Now</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading">Newsletter</h6>
                <p class="footer-text small">Subscribe for exclusive offers and luxury travel inspiration.</p>
                <form class="newsletter-form" id="newsletterForm">
                    <div class="input-group">
                        <input type="email" class="form-control lux-input" placeholder="Your email" required>
                        <button class="btn btn-lux-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
                <p class="mt-3 mb-0 small"><i class="fas fa-phone text-gold me-2"></i>+1 (800) LUXE-STAY</p>
            </div>
        </div>
        <div class="footer-bottom text-center py-3">
            <p class="mb-0 small">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved. | <a href="#">Privacy</a> | <a href="#">Terms</a></p>
        </div>
    </div>
</footer>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.LUXE_CONFIG = { baseUrl: '<?= $pagePath ?>', assetPath: '<?= $assetPath ?>', apiBaseUrl: '<?= $apiBaseUrl ?>' };</script>
<script src="<?= $assetPath ?>/js/api-client.js"></script>
<script src="<?= $assetPath ?>/js/main.js"></script>
<script src="<?= $assetPath ?>/js/ajax.js"></script>
<?php if (!empty($extraJs)): foreach ($extraJs as $js): ?>
<script src="<?= htmlspecialchars($js) ?>"></script>
<?php endforeach; endif; ?>
</body>
</html>
