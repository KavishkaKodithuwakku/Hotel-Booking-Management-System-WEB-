<?php
$pageTitle    = 'Dashboard';
$currentPage  = 'index.php';
$pageHeading  = 'Dashboard Overview';
$pageSubheading = 'Real-time overview of your hotel booking platform';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon gold"><i class="fas fa-dollar-sign"></i></div>
            <h3 data-stat-revenue>$–</h3>
            <p>Total Revenue</p>
            <span class="stat-trend up" id="revenueChangeLine"><i class="fas fa-spinner fa-spin fa-xs"></i></span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon navy"><i class="fas fa-calendar-check"></i></div>
            <h3 data-stat-bookings>–</h3>
            <p>Total Bookings</p>
            <span class="stat-trend" id="dashPendingBookings" title="Pending">– pending</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon green"><i class="fas fa-hotel"></i></div>
            <h3 data-stat-hotels>–</h3>
            <p>Active Hotels</p>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="fas fa-users"></i></div>
            <h3 data-stat-users>–</h3>
            <p>Registered Users</p>
            <span class="stat-trend up"><i class="fas fa-user-plus me-1"></i><span id="dashNewUsers">–</span> today</span>
        </div>
    </div>
</div>

<!-- Alert row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <a href="<?= $pagePath ?>/payments.php" class="stat-card text-center d-block text-decoration-none">
            <div class="stat-card-icon gold"><i class="fas fa-credit-card"></i></div>
            <h4 id="dashPendingPayments">–</h4>
            <p class="mb-0">Pending Payments</p>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= $pagePath ?>/reviews.php" class="stat-card text-center d-block text-decoration-none">
            <div class="stat-card-icon navy"><i class="fas fa-star"></i></div>
            <h4 id="dashPendingReviews">–</h4>
            <p class="mb-0">Pending Reviews</p>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= $pagePath ?>/support.php" class="stat-card text-center d-block text-decoration-none">
            <div class="stat-card-icon green"><i class="fas fa-headset"></i></div>
            <h4 id="dashNewMessages">–</h4>
            <p class="mb-0">New Support Msgs</p>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= $pagePath ?>/availability.php" class="stat-card text-center d-block text-decoration-none">
            <div class="stat-card-icon blue"><i class="fas fa-bed"></i></div>
            <h4 id="dashOccupancyRate">–</h4>
            <p class="mb-0">Occupancy Rate</p>
        </a>
    </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-chart-line text-warning me-2"></i>Revenue Tracking</h5>
                <a href="<?= $pagePath ?>/revenue.php" class="btn btn-admin-outline btn-sm">View Details</a>
            </div>
            <div class="admin-card-body">
                <div class="chart-wrap"><canvas id="revenueChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h5><i class="fas fa-calendar-alt me-2"></i>Weekly Bookings</h5>
            </div>
            <div class="admin-card-body">
                <div class="chart-wrap"><canvas id="bookingsChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

<!-- Recent bookings + quick actions -->
<div class="row g-3">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Recent Bookings</h5>
                <a href="<?= $pagePath ?>/bookings.php" class="btn btn-admin-primary btn-sm">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>Booking ID</th><th>Guest</th><th>Hotel</th><th>Check-in</th><th>Amount</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentBookingsBody">
                        <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header"><h5>Quick Actions</h5></div>
            <div class="admin-card-body d-grid gap-2">
                <a href="<?= $pagePath ?>/hotels.php" class="btn btn-admin-outline text-start"><i class="fas fa-hotel me-2"></i>Add Hotel</a>
                <a href="<?= $pagePath ?>/rooms.php" class="btn btn-admin-outline text-start"><i class="fas fa-door-open me-2"></i>Manage Rooms</a>
                <a href="<?= $pagePath ?>/reports.php" class="btn btn-admin-outline text-start"><i class="fas fa-file-alt me-2"></i>Generate Report</a>
                <a href="<?= $pagePath ?>/notifications.php" class="btn btn-admin-outline text-start"><i class="fas fa-bell me-2"></i>Send Notification</a>
                <a href="<?= $pagePath ?>/reviews.php" class="btn btn-admin-outline text-start"><i class="fas fa-star me-2"></i>Moderate Reviews</a>
                <a href="<?= $pagePath ?>/support.php" class="btn btn-admin-outline text-start"><i class="fas fa-headset me-2"></i>Support Messages</a>
            </div>
        </div>
        <div class="admin-card mt-3">
            <div class="admin-card-header">
                <h5>Room Availability</h5>
                <a href="<?= $pagePath ?>/availability.php" class="small">View all</a>
            </div>
            <div class="admin-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Available</span>
                    <strong class="text-success" data-stat-available>– rooms</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Occupied</span>
                    <strong class="text-danger" data-stat-occupied>– rooms</strong>
                </div>
                <div class="progress mt-2" style="height:8px;" id="occupancyBar">
                    <div class="progress-bar bg-success" id="availableBar" style="width:50%"></div>
                    <div class="progress-bar bg-danger" id="occupiedBar" style="width:50%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    if(!window.LuxeAdminApi) return;
    window.LuxeAdminApi.loadDashboard().then(function(data){
        const s = data.stats||{};
        // Revenue change line
        const cl = document.getElementById('revenueChangeLine');
        if(cl && s.revenueChange !== undefined){
            const sign = s.revenueChange >= 0 ? '+' : '';
            cl.innerHTML = '<i class="fas fa-arrow-'+(s.revenueChange>=0?'up':'down')+'"></i> '+sign+s.revenueChange+'% vs last month';
            cl.classList.toggle('up', s.revenueChange>=0);
            cl.classList.toggle('down', s.revenueChange<0);
        }
        // Room progress bar
        const total = (s.availableRooms||0) + (s.occupiedRooms||0);
        if(total>0){
            document.getElementById('availableBar').style.width = Math.round(s.availableRooms/total*100)+'%';
            document.getElementById('occupiedBar').style.width  = Math.round(s.occupiedRooms/total*100)+'%';
        }
    }).catch(function(){});
});
</script>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
