<?php
$pageTitle = 'My Profile';
$bodyClass = 'page-profile';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/navbar.php';
?>

<section class="page-hero page-hero-sm">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <h1 class="page-hero-title">My <span class="text-gold">Profile</span></h1>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <?php $sidebarType = 'profile'; require __DIR__ . '/includes/sidebar.php'; ?>
            </div>
            <div class="col-lg-9">
                <div class="profile-header-card lux-card p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="profile-avatar-lg">
                                <span>JD</span>
                                <button type="button" class="avatar-edit" title="Change photo"><i class="fas fa-camera"></i></button>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-1">John Doe</h3>
                            <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i>john.doe@example.com</p>
                            <span class="member-badge"><i class="fas fa-crown text-gold me-1"></i> Gold Member</span>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-lux-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card lux-card text-center p-4">
                            <i class="fas fa-suitcase-rolling fa-2x text-gold mb-2"></i>
                            <h3 class="mb-0">12</h3>
                            <span class="text-muted">Total Bookings</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card lux-card text-center p-4">
                            <i class="fas fa-star fa-2x text-gold mb-2"></i>
                            <h3 class="mb-0">8</h3>
                            <span class="text-muted">Reviews Written</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card lux-card text-center p-4">
                            <i class="fas fa-gem fa-2x text-gold mb-2"></i>
                            <h3 class="mb-0">2,450</h3>
                            <span class="text-muted">Loyalty Points</span>
                        </div>
                    </div>
                </div>

                <div class="lux-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Recent Bookings</h4>
                        <a href="<?= $pagePath ?>/booking-history.php" class="link-gold">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table lux-table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Hotel</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>BK-2026-0847</td>
                                    <td>Grand Luxe Resort</td>
                                    <td>Jun 15 – Jun 20</td>
                                    <td><span class="status-badge badge-confirmed">Confirmed</span></td>
                                    <td>$1,920</td>
                                </tr>
                                <tr>
                                    <td>BK-2026-0721</td>
                                    <td>Azure Palm Dubai</td>
                                    <td>May 1 – May 5</td>
                                    <td><span class="status-badge badge-completed">Completed</span></td>
                                    <td>$2,145</td>
                                </tr>
                                <tr>
                                    <td>BK-2026-0612</td>
                                    <td>Ocean Pearl Maldives</td>
                                    <td>Apr 10 – Apr 17</td>
                                    <td><span class="status-badge badge-cancelled">Cancelled</span></td>
                                    <td>$4,193</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Profile Modal -->
<div class="modal fade lux-modal" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content lux-card">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control lux-input" value="John">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control lux-input" value="Doe">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control lux-input" value="john.doe@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control lux-input" value="+1 555 123 4567">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control lux-input" value="123 Luxury Lane, New York">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-lux-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-lux-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
