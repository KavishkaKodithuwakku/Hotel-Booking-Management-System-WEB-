<?php
$pageTitle = 'Payment Management';
$currentPage = 'payments.php';
$pageHeading = 'Payment Management';
$pageSubheading = 'Track transactions, refunds, and payment status';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon green"><i class="fas fa-check-circle"></i></div>
            <h3>$284,120</h3>
            <p>Completed Payments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon gold"><i class="fas fa-clock"></i></div>
            <h3>$12,450</h3>
            <p>Pending Payments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon navy"><i class="fas fa-undo"></i></div>
            <h3>$8,320</h3>
            <p>Refunded</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>Payment Transactions</h5>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Booking</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>TXN-88421</td>
                    <td>BK-2026-4521</td>
                    <td>Sarah Mitchell</td>
                    <td><i class="fab fa-cc-visa"></i> Visa •••• 4242</td>
                    <td>$1,047.00</td>
                    <td>May 20, 2026</td>
                    <td><span class="badge-status badge-paid">Paid</span></td>
                    <td><button class="btn btn-admin-outline btn-admin-sm">Receipt</button></td>
                </tr>
                <tr>
                    <td>TXN-88418</td>
                    <td>BK-2026-4518</td>
                    <td>James Chen</td>
                    <td><i class="fab fa-cc-mastercard"></i> MC •••• 5555</td>
                    <td>$2,145.00</td>
                    <td>May 19, 2026</td>
                    <td><span class="badge-status badge-pending">Pending</span></td>
                    <td><button class="btn btn-admin-outline btn-admin-sm">Approve</button></td>
                </tr>
                <tr>
                    <td>TXN-88409</td>
                    <td>BK-2026-4509</td>
                    <td>Michael Brown</td>
                    <td>PayPal</td>
                    <td>$837.00</td>
                    <td>May 18, 2026</td>
                    <td><span class="badge-status badge-cancelled">Refunded</span></td>
                    <td><button class="btn btn-admin-outline btn-admin-sm">Details</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
