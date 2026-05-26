<?php
$pageTitle = 'Room Availability';
$currentPage = 'availability.php';
$pageHeading = 'Room Availability Management';
$pageSubheading = 'Monitor and update room availability across hotels';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <h3 class="text-success">156</h3>
            <p>Available Rooms</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <h3 class="text-danger">89</h3>
            <p>Occupied Rooms</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <h3>64%</h3>
            <p>Availability Rate</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>Availability Calendar — Grand Luxe Resort</h5>
        <div class="d-flex gap-2">
            <select class="form-select admin-input" style="width: auto;">
                <option>Grand Luxe Resort</option>
                <option>Azure Palm Dubai</option>
                <option>Ocean Pearl Maldives</option>
            </select>
            <button type="button" class="btn btn-admin-primary btn-sm" data-bs-toggle="modal" data-bs-target="#availabilityModal">Update Availability</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>May 26</th>
                    <th>May 27</th>
                    <th>May 28</th>
                    <th>May 29</th>
                    <th>May 30</th>
                    <th>May 31</th>
                    <th>Jun 01</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Deluxe Suite 101</td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                </tr>
                <tr>
                    <td>Executive King 102</td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                </tr>
                <tr>
                    <td>Presidential 201</td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-available">Open</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                    <td><span class="badge-status badge-occupied">Booked</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Room Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-admin-form data-success-msg="Availability updated">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="admin-form-label">Room</label>
                        <select class="form-select admin-input" required>
                            <option>Deluxe Suite 101</option>
                            <option>Executive King 102</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="admin-form-label">From Date</label>
                            <input type="date" class="form-control admin-input" required>
                        </div>
                        <div class="col-6">
                            <label class="admin-form-label">To Date</label>
                            <input type="date" class="form-control admin-input" required>
                        </div>
                    </div>
                    <div class="mb-0 mt-3">
                        <label class="admin-form-label">Status</label>
                        <select class="form-select admin-input">
                            <option>Available</option>
                            <option>Blocked / Maintenance</option>
                            <option>Booked</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
