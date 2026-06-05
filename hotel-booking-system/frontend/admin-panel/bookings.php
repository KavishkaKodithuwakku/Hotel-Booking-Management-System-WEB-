<?php
$pageTitle    = 'Booking Management';
$currentPage  = 'bookings.php';
$pageHeading  = 'Booking Management';
$pageSubheading = 'All customer reservations — live from database';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="admin-card mb-3">
    <div class="admin-card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="admin-form-label">Status</label>
                <select class="form-select admin-input" id="bkStatusFilter">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="admin-form-label">Hotel ID</label>
                <input type="number" class="form-control admin-input" id="bkHotelFilter" placeholder="Hotel ID (optional)">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-admin-primary w-100 mt-3" id="bkFilterBtn">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>All Bookings</h5>
        <span class="text-muted small" id="bkCount"></span>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Customer</th>
                    <th>Hotel / Room</th>
                    <th>Dates</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookingsTableBody">
                <tr><td colspan="8" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Status update modal -->
<div class="modal fade" id="bkStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Booking Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Booking: <strong id="bkModalRef"></strong></p>
                <label class="admin-form-label">New Status</label>
                <select class="form-select admin-input" id="bkNewStatus">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-admin-primary" id="bkSaveStatus">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let currentBkId = null;

    function badgeClass(s){
        return s==='confirmed'?'badge-confirmed':s==='pending'?'badge-pending':s==='cancelled'?'badge-cancelled':'badge-completed';
    }
    function payBadge(s){
        return s==='paid'?'badge-confirmed':s==='pending'?'badge-pending':'badge-cancelled';
    }

    function loadBookings(status, hotelId){
        const tbody = document.getElementById('bookingsTableBody');
        tbody.innerHTML='<tr><td colspan="8" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>';
        let qs = '';
        if(status) qs += '?status='+status;
        if(hotelId) qs += (qs?'&':'?')+'hotel_id='+hotelId;
        window.LuxeAdminApi.get('/admin/bookings'+qs).then(function(data){
            const bks = data.bookings||[];
            document.getElementById('bkCount').textContent = bks.length + ' booking(s)';
            if(!bks.length){
                tbody.innerHTML='<tr><td colspan="8" class="text-center py-4 text-muted">No bookings found.</td></tr>';
                return;
            }
            tbody.innerHTML = bks.map(function(b){
                return `<tr>
                    <td><strong>${b.booking_ref}</strong><br><small class="text-muted">${new Date(b.created_at).toLocaleDateString()}</small></td>
                    <td>${b.guest}<br><small class="text-muted">${b.email}</small></td>
                    <td>${b.hotel}<br><small class="text-muted">${b.room}</small></td>
                    <td>${b.check_in}<br><small class="text-muted">→ ${b.check_out}</small></td>
                    <td>$${b.total_amount.toLocaleString()}</td>
                    <td><span class="badge-status ${payBadge(b.payment_status)}">${b.payment_status}</span></td>
                    <td><span class="badge-status ${badgeClass(b.status)}">${b.status}</span></td>
                    <td>
                        <button class="btn btn-admin-primary btn-admin-sm btn-bk-status"
                                data-id="${b.id}" data-ref="${b.booking_ref}" data-status="${b.status}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-admin-outline btn-admin-sm text-danger btn-bk-cancel"
                                data-id="${b.id}" data-ref="${b.booking_ref}"
                                ${b.status==='cancelled'?'disabled':''}>
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            tbody.querySelectorAll('.btn-bk-status').forEach(function(btn){
                btn.addEventListener('click', function(){
                    currentBkId = btn.dataset.id;
                    document.getElementById('bkModalRef').textContent = btn.dataset.ref;
                    document.getElementById('bkNewStatus').value = btn.dataset.status;
                    new bootstrap.Modal(document.getElementById('bkStatusModal')).show();
                });
            });
            tbody.querySelectorAll('.btn-bk-cancel').forEach(function(btn){
                btn.addEventListener('click', function(){
                    if(!confirm('Cancel booking '+btn.dataset.ref+'?')) return;
                    window.LuxeAdminApi.delete('/admin/bookings/'+btn.dataset.id).then(function(){
                        AdminUI.showToast('Booking cancelled');
                        loadBookings(
                            document.getElementById('bkStatusFilter').value,
                            document.getElementById('bkHotelFilter').value
                        );
                    }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
                });
            });
        }).catch(function(e){ tbody.innerHTML='<tr><td colspan="8" class="text-center text-danger py-4">'+e.message+'</td></tr>'; });
    }

    document.getElementById('bkSaveStatus').addEventListener('click', function(){
        const newStatus = document.getElementById('bkNewStatus').value;
        window.LuxeAdminApi.put('/admin/bookings/'+currentBkId, {status:newStatus}).then(function(){
            AdminUI.showToast('Status updated to '+newStatus);
            bootstrap.Modal.getInstance(document.getElementById('bkStatusModal'))?.hide();
            loadBookings(
                document.getElementById('bkStatusFilter').value,
                document.getElementById('bkHotelFilter').value
            );
        }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
    });

    document.getElementById('bkFilterBtn').addEventListener('click', function(){
        loadBookings(document.getElementById('bkStatusFilter').value, document.getElementById('bkHotelFilter').value);
    });

    document.addEventListener('DOMContentLoaded', function(){ loadBookings(); });
})();
</script>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
