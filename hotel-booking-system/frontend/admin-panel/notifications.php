<?php
$pageTitle = 'Notification Management';
$currentPage = 'notifications.php';
$pageHeading = 'Notification Management';
$pageSubheading = 'Send alerts, emails, and in-app notifications';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Send Notification</h5>
            </div>
            <div class="admin-card-body">
                <form data-admin-form data-success-msg="Notification sent to recipients">
                    <div class="mb-3">
                        <label class="admin-form-label">Audience</label>
                        <select class="form-select admin-input" required>
                            <option>All Users</option>
                            <option>All Customers</option>
                            <option>Recent Bookers</option>
                            <option>Specific User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Channel</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chEmail" checked>
                                <label class="form-check-label" for="chEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chPush" checked>
                                <label class="form-check-label" for="chPush">In-App</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chSms">
                                <label class="form-check-label" for="chSms">SMS</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Subject</label>
                        <input type="text" class="form-control admin-input" placeholder="Summer promotion — 20% off" required>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Message</label>
                        <textarea class="form-control admin-input" rows="4" required placeholder="Write your notification message..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-admin-primary w-100">
                        <i class="fas fa-paper-plane me-1"></i> Send Notification
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Notification History</h5>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Audience</th>
                            <th>Channel</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>May 25, 2026</td>
                            <td>Summer Sale — 20% Off</td>
                            <td>All Users</td>
                            <td>Email, In-App</td>
                            <td><span class="badge-status badge-confirmed">Sent</span></td>
                        </tr>
                        <tr>
                            <td>May 20, 2026</td>
                            <td>Booking Confirmation Reminder</td>
                            <td>Recent Bookers</td>
                            <td>Email</td>
                            <td><span class="badge-status badge-confirmed">Sent</span></td>
                        </tr>
                        <tr>
                            <td>May 15, 2026</td>
                            <td>System Maintenance Notice</td>
                            <td>All Users</td>
                            <td>In-App</td>
                            <td><span class="badge-status badge-pending">Scheduled</span></td>
                        </tr>
                        <tr>
                            <td>May 10, 2026</td>
                            <td>Welcome to LuxeStay</td>
                            <td>New Registrations</td>
                            <td>Email</td>
                            <td><span class="badge-status badge-confirmed">Sent</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-card mt-3">
            <div class="admin-card-header">
                <h5>System Alerts</h5>
            </div>
            <div class="admin-card-body">
                <div class="alert alert-warning mb-2 py-2"><i class="fas fa-exclamation-triangle me-2"></i>3 bookings pending approval</div>
                <div class="alert alert-info mb-2 py-2"><i class="fas fa-info-circle me-2"></i>Grand Luxe Resort — low availability for Jun 01–05</div>
                <div class="alert alert-success mb-0 py-2"><i class="fas fa-check-circle me-2"></i>Payment gateway connected successfully</div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
