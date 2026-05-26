<?php
$pageTitle = 'Report Generation';
$currentPage = 'reports.php';
$pageHeading = 'Report Generation';
$pageSubheading = 'Generate and export business reports';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h5>Generate Report</h5>
            </div>
            <div class="admin-card-body">
                <form data-admin-form data-success-msg="Report queued for generation">
                    <div class="mb-3">
                        <label class="admin-form-label">Report Type</label>
                        <select class="form-select admin-input" required>
                            <option>Booking Summary</option>
                            <option>Revenue Report</option>
                            <option>Occupancy Report</option>
                            <option>Customer Analytics</option>
                            <option>Payment Ledger</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Date Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" class="form-control admin-input" required>
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control admin-input" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Format</label>
                        <select class="form-select admin-input">
                            <option>PDF</option>
                            <option>Excel (XLSX)</option>
                            <option>CSV</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-admin-primary w-100 mb-2">Generate Report</button>
                    <button type="button" class="btn btn-admin-outline w-100" data-export-report>Quick Export (PDF)</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Recent Reports</h5>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>Generated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>May 2026 Revenue</td>
                            <td>Revenue</td>
                            <td>May 1 – May 31</td>
                            <td>May 25, 2026</td>
                            <td>
                                <button class="btn btn-admin-outline btn-admin-sm" data-export-report><i class="fas fa-download"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Q1 Booking Summary</td>
                            <td>Bookings</td>
                            <td>Jan – Mar 2026</td>
                            <td>Apr 02, 2026</td>
                            <td>
                                <button class="btn btn-admin-outline btn-admin-sm" data-export-report><i class="fas fa-download"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Hotel Occupancy Apr</td>
                            <td>Occupancy</td>
                            <td>Apr 2026</td>
                            <td>May 01, 2026</td>
                            <td>
                                <button class="btn btn-admin-outline btn-admin-sm" data-export-report><i class="fas fa-download"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
