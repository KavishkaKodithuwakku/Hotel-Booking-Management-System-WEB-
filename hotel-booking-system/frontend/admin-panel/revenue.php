<?php
$pageTitle = 'Revenue Tracking';
$currentPage = 'revenue.php';
$pageHeading = 'Revenue Tracking';
$pageSubheading = 'Detailed revenue analytics and trends';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <h3>$62,100</h3>
            <p>This Month</p>
            <span class="stat-trend up">+18% vs last month</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>$54,400</h3>
            <p>Last Month</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>$328,450</h3>
            <p>Year to Date</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>$142</h3>
            <p>Avg. Booking Value</p>
        </div>
    </div>
</div>

<div class="admin-card mb-4">
    <div class="admin-card-header">
        <h5>Revenue Trend</h5>
        <select class="form-select admin-input" style="width: auto;">
            <option>Last 6 Months</option>
            <option>Last 12 Months</option>
            <option>This Year</option>
        </select>
    </div>
    <div class="admin-card-body">
        <div class="chart-wrap" style="height: 320px;"><canvas id="revenueChart"></canvas></div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>Revenue by Hotel</h5>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Bookings</th>
                    <th>Revenue</th>
                    <th>% of Total</th>
                    <th>Trend</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Grand Luxe Resort</td>
                    <td>412</td>
                    <td>$98,240</td>
                    <td>29.9%</td>
                    <td class="text-success"><i class="fas fa-arrow-up"></i> 12%</td>
                </tr>
                <tr>
                    <td>Azure Palm Dubai</td>
                    <td>289</td>
                    <td>$86,450</td>
                    <td>26.3%</td>
                    <td class="text-success"><i class="fas fa-arrow-up"></i> 8%</td>
                </tr>
                <tr>
                    <td>Ocean Pearl Maldives</td>
                    <td>156</td>
                    <td>$72,180</td>
                    <td>22.0%</td>
                    <td class="text-success"><i class="fas fa-arrow-up"></i> 15%</td>
                </tr>
                <tr>
                    <td>Tokyo Imperial Tower</td>
                    <td>198</td>
                    <td>$45,890</td>
                    <td>14.0%</td>
                    <td class="text-danger"><i class="fas fa-arrow-down"></i> 3%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
