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
                <!-- Profile header -->
                <div class="profile-header-card lux-card p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="profile-avatar-lg">
                                <span id="profileInitials">?</span>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-1" id="profileName">Loading…</h3>
                            <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i><span id="profileEmail">–</span></p>
                            <span class="member-badge"><i class="fas fa-crown text-gold me-1"></i> <span id="profileTier">–</span> Member</span>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-lux-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card lux-card text-center p-4">
                            <i class="fas fa-suitcase-rolling fa-2x text-gold mb-2"></i>
                            <h3 class="mb-0" id="statBookings">–</h3>
                            <span class="text-muted">Total Bookings</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card lux-card text-center p-4">
                            <i class="fas fa-star fa-2x text-gold mb-2"></i>
                            <h3 class="mb-0" id="statReviews">–</h3>
                            <span class="text-muted">Reviews Written</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card lux-card text-center p-4">
                            <i class="fas fa-bell fa-2x text-gold mb-2"></i>
                            <h3 class="mb-0" id="statNotifs">–</h3>
                            <span class="text-muted">Notifications</span>
                        </div>
                    </div>
                </div>

                <!-- Recent bookings -->
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
                                    <th>Check-in</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="recentBookingsTable">
                                <tr><td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Loading…
                                </td></tr>
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
                            <input type="text" class="form-control lux-input" id="editFirstName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control lux-input" id="editLastName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control lux-input" id="editPhone">
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

<script>
(function(){
    function loadProfile(){
        if(!window.LuxeApi || !window.LuxeApi.getToken()) return;

        window.LuxeApi.get('/auth/me').then(function(data){
            const u = data.user;
            if(!u) return;
            const full = (u.firstName||'') + ' ' + (u.lastName||'');
            document.getElementById('profileName').textContent     = full.trim() || u.email;
            document.getElementById('profileEmail').textContent    = u.email||'';
            document.getElementById('profileTier').textContent     = u.loyaltyTier||'Silver';
            document.getElementById('statBookings').textContent    = u.totalBookings||0;
            document.getElementById('statReviews').textContent     = u.reviewCount||0;
            document.getElementById('statNotifs').textContent      = u.unreadNotifications||0;

            const initials = ((u.firstName||'?')[0]+(u.lastName||'?')[0]).toUpperCase();
            document.getElementById('profileInitials').textContent = initials;

            // Pre-fill edit form
            document.getElementById('editFirstName').value = u.firstName||'';
            document.getElementById('editLastName').value  = u.lastName||'';
            document.getElementById('editPhone').value     = u.phone||'';
        }).catch(function(){});

        window.LuxeApi.get('/bookings').then(function(data){
            const bks = (data.bookings||[]).slice(0,5);
            const tb  = document.getElementById('recentBookingsTable');
            if(!bks.length){
                tb.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No bookings yet.</td></tr>';
                return;
            }
            const scMap = {confirmed:'badge-confirmed',pending:'badge-pending',cancelled:'badge-cancelled',completed:'badge-completed'};
            tb.innerHTML = bks.map(function(b){
                return `<tr>
                    <td>${b.booking_ref}</td>
                    <td>${b.hotel}</td>
                    <td>${b.check_in}</td>
                    <td><span class="status-badge ${scMap[b.status]||''}">${b.status}</span></td>
                    <td>$${Number(b.total_amount).toLocaleString()}</td>
                </tr>`;
            }).join('');
        }).catch(function(){});
    }

    document.getElementById('editProfileForm').addEventListener('submit', function(e){
        e.preventDefault();
        window.LuxeApi.put('/profile', {
            firstName: document.getElementById('editFirstName').value,
            lastName:  document.getElementById('editLastName').value,
            phone:     document.getElementById('editPhone').value,
        }).then(function(){
            window.showToast && window.showToast('Profile updated','success');
            bootstrap.Modal.getInstance(document.getElementById('editProfileModal'))?.hide();
            loadProfile();
        }).catch(function(err){ window.showToast && window.showToast(err.message,'error'); });
    });

    document.addEventListener('DOMContentLoaded', loadProfile);
})();
</script>

<?php require_once __DIR__ . '/components/footer.php'; ?>
