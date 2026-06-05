<?php
$pageTitle    = 'User Management';
$currentPage  = 'users.php';
$pageHeading  = 'User Management';
$pageSubheading = 'All registered users — live from database';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-users text-gold me-2"></i>All Users</h5>
        <button class="btn btn-admin-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus me-1"></i>Add User
        </button>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <input type="hidden" id="editUserId">
                <div class="col-md-6">
                    <label class="admin-form-label">First Name</label>
                    <input type="text" class="form-control admin-input" id="editFirstName">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Last Name</label>
                    <input type="text" class="form-control admin-input" id="editLastName">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Email</label>
                    <input type="email" class="form-control admin-input" id="editEmail">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Phone</label>
                    <input type="text" class="form-control admin-input" id="editPhone">
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Role</label>
                    <select class="form-select admin-input" id="editRole">
                        <option value="customer">Customer</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Status</label>
                    <select class="form-select admin-input" id="editStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="banned">Banned</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="admin-form-label">Loyalty Tier</label>
                    <select class="form-select admin-input" id="editLoyalty">
                        <option>Silver</option><option>Gold</option><option>Platinum</option><option>Diamond</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-admin-primary" id="saveUserEdit">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Add User modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="admin-form-label">First Name</label>
                        <input type="text" name="firstName" class="form-control admin-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Last Name</label>
                        <input type="text" name="lastName" class="form-control admin-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Email</label>
                        <input type="email" name="email" class="form-control admin-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Phone</label>
                        <input type="text" name="phone" class="form-control admin-input">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Password</label>
                        <input type="password" name="password" class="form-control admin-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Role</label>
                        <select name="role" class="form-select admin-input">
                            <option value="customer">Customer</option>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    function badgeClass(s){ return s==='active'?'badge-confirmed':s==='inactive'?'badge-pending':'badge-cancelled'; }
    function roleBadge(r){ return r==='admin'||r==='super_admin'?'badge-confirmed':r==='staff'?'badge-pending':''; }

    function loadUsers(){
        const tbody = document.getElementById('usersTableBody');
        tbody.innerHTML='<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>';
        window.LuxeAdminApi.get('/admin/users').then(function(data){
            const users = data.users||[];
            if(!users.length){
                tbody.innerHTML='<tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>';
                return;
            }
            tbody.innerHTML = users.map(function(u){
                return `<tr>
                    <td>${u.first_name||''} ${u.last_name||''}</td>
                    <td>${u.email||''}</td>
                    <td><span class="badge-status ${roleBadge(u.role)}">${u.role}</span></td>
                    <td><span class="badge-status ${badgeClass(u.status)}">${u.status}</span></td>
                    <td>${new Date(u.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-admin-primary btn-admin-sm btn-edit-user"
                                data-user='${JSON.stringify(u).replace(/'/g,"&apos;")}'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-admin-outline btn-admin-sm text-danger btn-del-user" data-id="${u.id}">
                            <i class="fas fa-user-slash"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            tbody.querySelectorAll('.btn-edit-user').forEach(function(btn){
                btn.addEventListener('click', function(){
                    const u = JSON.parse(btn.getAttribute('data-user').replace(/&apos;/g,"'"));
                    document.getElementById('editUserId').value    = u.id;
                    document.getElementById('editFirstName').value = u.first_name||'';
                    document.getElementById('editLastName').value  = u.last_name||'';
                    document.getElementById('editEmail').value     = u.email||'';
                    document.getElementById('editPhone').value     = u.phone||'';
                    document.getElementById('editRole').value      = u.role||'customer';
                    document.getElementById('editStatus').value    = u.status||'active';
                    document.getElementById('editLoyalty').value   = u.loyalty_tier||'Silver';
                    new bootstrap.Modal(document.getElementById('editUserModal')).show();
                });
            });
            tbody.querySelectorAll('.btn-del-user').forEach(function(btn){
                btn.addEventListener('click', function(){
                    if(!confirm('Deactivate this user?')) return;
                    window.LuxeAdminApi.delete('/admin/users/'+btn.dataset.id).then(function(){
                        AdminUI.showToast('User deactivated');
                        loadUsers();
                    }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
                });
            });
        }).catch(function(e){ tbody.innerHTML='<tr><td colspan="6" class="text-center text-danger py-4">'+e.message+'</td></tr>'; });
    }

    document.getElementById('saveUserEdit').addEventListener('click', function(){
        const id = document.getElementById('editUserId').value;
        window.LuxeAdminApi.put('/admin/users/'+id, {
            firstName:   document.getElementById('editFirstName').value,
            lastName:    document.getElementById('editLastName').value,
            email:       document.getElementById('editEmail').value,
            phone:       document.getElementById('editPhone').value,
            role:        document.getElementById('editRole').value,
            status:      document.getElementById('editStatus').value,
            loyalty_tier:document.getElementById('editLoyalty').value,
        }).then(function(){
            AdminUI.showToast('User updated');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal'))?.hide();
            loadUsers();
        }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
    });

    document.getElementById('addUserForm').addEventListener('submit', function(e){
        e.preventDefault();
        const fd = new FormData(this);
        const body = {};
        fd.forEach(function(v,k){ body[k]=v; });
        window.LuxeAdminApi.post('/admin/users', body).then(function(){
            AdminUI.showToast('User created');
            bootstrap.Modal.getInstance(document.getElementById('addUserModal'))?.hide();
            e.target.reset();
            loadUsers();
        }).catch(function(err){ AdminUI.showToast(err.message,'error'); });
    });

    document.addEventListener('DOMContentLoaded', loadUsers);
})();
</script>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
