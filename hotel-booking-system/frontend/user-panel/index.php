<?php
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
?>
<?php require_once __DIR__ . '/components/navbar.php'; ?>

<main class="user-home py-5">
    <section class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <p class="text-uppercase text-gold fw-semibold mb-3">Welcome to <?= SITE_NAME ?></p>
                    <h1 class="display-5 fw-bold mb-4">Explore beautiful hotels and book your next luxury stay.</h1>
                    <p class="lead text-muted mb-4">Browse curated properties, manage bookings, and sign in only when you are ready to continue.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= $pagePath ?>/hotels.php" class="btn btn-lux-primary btn-lg">Browse Hotels</a>
                        <a href="<?= $pagePath ?>/login.php" class="btn btn-lux-ghost btn-lg">Login</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <div class="hero-card p-5 bg-white rounded-4 shadow-sm">
                        <h2 class="h4 mb-3">Ready for a getaway?</h2>
                        <p class="text-muted mb-4">View hotels, see room details, and make a reservation all from one place.</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="feature-box p-3 rounded-3 bg-light text-center">
                                    <i class="fas fa-bed fa-2x text-gold mb-2"></i>
                                    <h6 class="mb-1">Rooms</h6>
                                    <p class="small text-muted mb-0">Luxury suites & city views.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="feature-box p-3 rounded-3 bg-light text-center">
                                    <i class="fas fa-calendar-check fa-2x text-gold mb-2"></i>
                                    <h6 class="mb-1">Bookings</h6>
                                    <p class="small text-muted mb-0">Easy booking management.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="feature-box p-3 rounded-3 bg-light text-center">
                                    <i class="fas fa-user-shield fa-2x text-gold mb-2"></i>
                                    <h6 class="mb-1">Support</h6>
                                    <p class="small text-muted mb-0">Dedicated concierge service.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="feature-box p-3 rounded-3 bg-light text-center">
                                    <i class="fas fa-star fa-2x text-gold mb-2"></i>
                                    <h6 class="mb-1">Rewards</h6>
                                    <p class="small text-muted mb-0">Member benefits and offers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="why-us-section py-5">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3">A seamless booking experience</h2>
                    <p class="text-muted mb-4">With our user panel you can browse properties, compare prices, and manage your trips with a clean, modern interface.</p>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-3"><i class="fas fa-check text-gold me-2"></i>Search and compare hotels effortlessly</li>
                        <li class="mb-3"><i class="fas fa-check text-gold me-2"></i>Save your favorite stays and bookings</li>
                        <li class="mb-3"><i class="fas fa-check text-gold me-2"></i>Secure login and account management</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="min-height: 320px; background: linear-gradient(135deg, rgba(201,162,39,0.12), rgba(10,22,40,0.95));">
                        <div class="position-absolute top-50 start-50 translate-middle text-white text-center px-4">
                            <h3 class="h4 mb-3">Stay inspired. Book with confidence.</h3>
                            <p class="mb-0">Your account gives you the flexibility to manage bookings, save trips, and access member-only rates.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/components/footer.php'; ?>
