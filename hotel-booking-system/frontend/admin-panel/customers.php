<?php
$pageTitle = 'Customer Management';
$currentPage = 'customers.php';
$pageHeading = 'Customer Management';
$pageSubheading = 'Manage guest profiles, loyalty, and booking history';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>All Customers</h5>
        <button type="button" class="btn btn-admin-primary btn-sm" data-bs-toggle="modal" data-bs-target="#customerModal">
            <i class="fas fa-plus me-1"></i> Add Customer
        </button>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Bookings</th>
                    <th>Lifetime Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CUS-001</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="admin-avatar" style="width:32px;height:32px;font-size:0.65rem">SM</div>
                            <strong>Sarah Mitchell</strong>
                        </div>
                    </td>
                    <td>sarah@email.com</td>
                    <td>+1 555-0101</td>
                    <td>12</td>
                    <td>$8,420</td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm" data-bs-toggle="modal" data-bs-target="#customerModal"><i class="fas fa-eye"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>CUS-002</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="admin-avatar" style="width:32px;height:32px;font-size:0.65rem">JC</div>
                            <strong>James Chen</strong>
                        </div>
                    </td>
                    <td>james@email.com</td>
                    <td>+1 555-0102</td>
                    <td>8</td>
                    <td>$5,890</td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-eye"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>CUS-003</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="admin-avatar" style="width:32px;height:32px;font-size:0.65rem">EW</div>
                            <strong>Emma Wilson</strong>
                        </div>
                    </td>
                    <td>emma@email.com</td>
                    <td>+44 7700 900123</td>
                    <td>5</td>
                    <td>$12,340</td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-eye"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-admin-form data-success-msg="Customer saved">
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="admin-form-label">Full Name</label>
                        <input type="text" class="form-control admin-input" value="Sarah Mitchell" required>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Email</label>
                        <input type="email" class="form-control admin-input" value="sarah@email.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Phone</label>
                        <input type="tel" class="form-control admin-input" value="+1 555-0101">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Loyalty Tier</label>
                        <select class="form-select admin-input">
                            <option>Gold Member</option>
                            <option>Silver</option>
                            <option>Platinum</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Notes</label>
                        <textarea class="form-control admin-input" rows="2">Prefers high-floor rooms, late checkout.</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-admin-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
