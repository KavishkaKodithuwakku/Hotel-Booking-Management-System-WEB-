<?php
$pageTitle = 'Booking Management';
$currentPage = 'bookings.php';
$pageHeading = 'Booking Management';
$pageSubheading = 'View and manage all customer reservations';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="admin-card mb-3">
    <div class="admin-card-body py-3">
        <form class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="admin-form-label">Status</label>
                <select class="form-select admin-input">
                    <option>All Statuses</option>
                    <option>Confirmed</option>
                    <option>Pending</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="admin-form-label">Hotel</label>
                <select class="form-select admin-input">
                    <option>All Hotels</option>
                    <option>Grand Luxe Resort</option>
                    <option>Azure Palm Dubai</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="admin-form-label">Date From</label>
                <input type="date" class="form-control admin-input">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-admin-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>All Bookings</h5>
        <button class="btn btn-admin-outline btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal">Manual Booking</button>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Hotel</th>
                    <th>Room</th>
                    <th>Check-in / Out</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>BK-2026-4521</td>
                    <td>Sarah Mitchell</td>
                    <td>Grand Luxe Resort</td>
                    <td>Deluxe Suite</td>
                    <td>Jun 01 – Jun 04</td>
                    <td>$1,047</td>
                    <td><span class="badge-status badge-confirmed">Confirmed</span></td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-admin-outline btn-admin-sm" data-admin-delete><i class="fas fa-times"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>BK-2026-4518</td>
                    <td>James Chen</td>
                    <td>Azure Palm Dubai</td>
                    <td>Royal Villa</td>
                    <td>May 28 – Jun 02</td>
                    <td>$2,145</td>
                    <td><span class="badge-status badge-pending">Pending</span></td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-check"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>BK-2026-4512</td>
                    <td>Emma Wilson</td>
                    <td>Ocean Pearl Maldives</td>
                    <td>Overwater Bungalow</td>
                    <td>Jun 15 – Jun 20</td>
                    <td>$3,594</td>
                    <td><span class="badge-status badge-confirmed">Confirmed</span></td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-admin-outline btn-admin-sm" data-admin-delete><i class="fas fa-times"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Manual Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-admin-form data-success-msg="Booking created">
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="admin-form-label">Customer</label>
                        <select class="form-select admin-input" required>
                            <option>Sarah Mitchell</option>
                            <option>James Chen</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Hotel & Room</label>
                        <select class="form-select admin-input" required>
                            <option>Grand Luxe — Deluxe Suite</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Check-in</label>
                        <input type="date" class="form-control admin-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Check-out</label>
                        <input type="date" class="form-control admin-input" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-primary">Create Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
