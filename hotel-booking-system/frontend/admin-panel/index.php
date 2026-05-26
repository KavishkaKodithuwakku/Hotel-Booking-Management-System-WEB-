<?php
$pageTitle = 'Dashboard';
$currentPage = 'index.php';
$pageHeading = 'Dashboard Overview';
$pageSubheading = 'Real-time overview of your hotel booking platform';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon gold"><i class="fas fa-dollar-sign"></i></div>
            <h3>$328,450</h3>
            <p>Total Revenue</p>
            <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 12.4% vs last month</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon navy"><i class="fas fa-calendar-check"></i></div>
            <h3>1,847</h3>
            <p>Total Bookings</p>
            <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 8.2%</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon green"><i class="fas fa-hotel"></i></div>
            <h3>24</h3>
            <p>Active Hotels</p>
            <span class="stat-trend up"><i class="fas fa-plus"></i> 2 new</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="fas fa-users"></i></div>
            <h3>5,621</h3>
            <p>Registered Users</p>
            <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 15.3%</span>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-chart-line text-warning me-2"></i> Revenue Tracking</h5>
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
                <h5><i class="fas fa-calendar-alt me-2"></i> Weekly Bookings</h5>
            </div>
            <div class="admin-card-body">
                <div class="chart-wrap"><canvas id="bookingsChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

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
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Hotel</th>
                            <th>Check-in</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>BK-2026-4521</td>
                            <td>Sarah Mitchell</td>
                            <td>Grand Luxe Resort</td>
                            <td>Jun 01, 2026</td>
                            <td>$1,047</td>
                            <td><span class="badge-status badge-confirmed">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>BK-2026-4518</td>
                            <td>James Chen</td>
                            <td>Azure Palm Dubai</td>
                            <td>May 28, 2026</td>
                            <td>$2,145</td>
                            <td><span class="badge-status badge-pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>BK-2026-4512</td>
                            <td>Emma Wilson</td>
                            <td>Ocean Pearl Maldives</td>
                            <td>Jun 15, 2026</td>
                            <td>$3,594</td>
                            <td><span class="badge-status badge-confirmed">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>BK-2026-4509</td>
                            <td>Michael Brown</td>
                            <td>Tokyo Imperial Tower</td>
                            <td>May 30, 2026</td>
                            <td>$837</td>
                            <td><span class="badge-status badge-cancelled">Cancelled</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="admin-card-body d-grid gap-2">
                <a href="<?= $pagePath ?>/hotels.php" class="btn btn-admin-outline text-start"><i class="fas fa-hotel me-2"></i> Add Hotel</a>
                <a href="<?= $pagePath ?>/rooms.php" class="btn btn-admin-outline text-start"><i class="fas fa-door-open me-2"></i> Manage Rooms</a>
                <a href="<?= $pagePath ?>/reports.php" class="btn btn-admin-outline text-start"><i class="fas fa-file-alt me-2"></i> Generate Report</a>
                <a href="<?= $pagePath ?>/notifications.php" class="btn btn-admin-outline text-start"><i class="fas fa-bell me-2"></i> Send Notification</a>
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
                    <strong class="text-success">156 rooms</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Occupied</span>
                    <strong class="text-danger">89 rooms</strong>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 64%"></div>
                    <div class="progress-bar bg-danger" style="width: 36%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
