<?php
$pageTitle = 'User Management';
$currentPage = 'users.php';
$pageHeading = 'User Management';
$pageSubheading = 'Manage registered platform users and roles';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>Platform Users</h5>
        <button type="button" class="btn btn-admin-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal">
            <i class="fas fa-user-plus me-1"></i> Add User
        </button>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>U1001</td>
                    <td>Sarah Mitchell</td>
                    <td>sarah@email.com</td>
                    <td>Customer</td>
                    <td>Jan 12, 2026</td>
                    <td><span class="badge-status badge-confirmed">Active</span></td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm" data-bs-toggle="modal" data-bs-target="#userModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-admin-outline btn-admin-sm" data-admin-delete><i class="fas fa-ban"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>U1002</td>
                    <td>James Chen</td>
                    <td>james@email.com</td>
                    <td>Customer</td>
                    <td>Feb 03, 2026</td>
                    <td><span class="badge-status badge-confirmed">Active</span></td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-admin-outline btn-admin-sm" data-admin-delete><i class="fas fa-ban"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>A001</td>
                    <td>Admin User</td>
                    <td>admin@luxestay.com</td>
                    <td>Super Admin</td>
                    <td>Dec 01, 2025</td>
                    <td><span class="badge-status badge-paid">Admin</span></td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add / Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-admin-form data-success-msg="User saved">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="admin-form-label">Full Name</label>
                        <input type="text" class="form-control admin-input" required>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Email</label>
                        <input type="email" class="form-control admin-input" required>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Role</label>
                        <select class="form-select admin-input">
                            <option>Customer</option>
                            <option>Staff</option>
                            <option>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
