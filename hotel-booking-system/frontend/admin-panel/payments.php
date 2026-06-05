<?php
$pageTitle    = 'Payment Management';
$currentPage  = 'payments.php';
$pageHeading  = 'Payment Management';
$pageSubheading = 'All transactions — live from database';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon green"><i class="fas fa-check-circle"></i></div>
            <h3 id="payCompleted">$–</h3>
            <p>Completed Revenue</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon gold"><i class="fas fa-clock"></i></div>
            <h3 id="payPending">$–</h3>
            <p>Pending Payments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon navy"><i class="fas fa-undo-alt"></i></div>
            <h3 id="payRefunded">$–</h3>
            <p>Refunded</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-credit-card text-gold me-2"></i>All Payments</h5>
        <span class="text-muted small" id="payCount"></span>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Booking Ref</th>
                    <th>Customer</th>
                    <th>Hotel</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Paid At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="paymentsTableBody">
                <tr><td colspan="9" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Update status modal -->
<div class="modal fade" id="payStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Payment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Transaction: <strong id="payModalTxn"></strong></p>
                <label class="admin-form-label">New Status</label>
                <select class="form-select admin-input" id="payNewStatus">
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="refunded">Refunded</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-admin-primary" id="paySaveStatus">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let currentPayId = null;

    function badgeClass(s){
        return s==='paid'?'badge-confirmed':s==='pending'?'badge-pending':s==='refunded'?'badge-cancelled':'badge-cancelled';
    }

    function loadPayments(){
        const tbody = document.getElementById('paymentsTableBody');
        tbody.innerHTML='<tr><td colspan="9" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>';
        window.LuxeAdminApi.get('/admin/payments').then(function(data){
            const pays = data.payments||[];
            const s    = data.stats||{};
            document.getElementById('payCount').textContent     = pays.length + ' transaction(s)';
            document.getElementById('payCompleted').textContent = '$'+Number(s.completed||0).toLocaleString();
            document.getElementById('payPending').textContent   = '$'+Number(s.pending||0).toLocaleString();
            document.getElementById('payRefunded').textContent  = '$'+Number(s.refunded||0).toLocaleString();

            if(!pays.length){
                tbody.innerHTML='<tr><td colspan="9" class="text-center py-4 text-muted">No payments found.</td></tr>';
                return;
            }
            tbody.innerHTML = pays.map(function(p){
                return `<tr>
                    <td><code>${p.transaction_id}</code></td>
                    <td>${p.booking_ref}</td>
                    <td>${p.customer}<br><small class="text-muted">${p.email}</small></td>
                    <td>${p.hotel_name}</td>
                    <td><strong>$${Number(p.amount).toLocaleString()}</strong></td>
                    <td>${p.payment_method}${p.card_last4?' ****'+p.card_last4:''}</td>
                    <td><span class="badge-status ${badgeClass(p.status)}">${p.status}</span></td>
                    <td>${p.paid_at ? new Date(p.paid_at).toLocaleDateString() : '–'}</td>
                    <td>
                        <button class="btn btn-admin-primary btn-admin-sm btn-pay-edit"
                                data-id="${p.id}" data-txn="${p.transaction_id}" data-status="${p.status}">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            tbody.querySelectorAll('.btn-pay-edit').forEach(function(btn){
                btn.addEventListener('click', function(){
                    currentPayId = btn.dataset.id;
                    document.getElementById('payModalTxn').textContent = btn.dataset.txn;
                    document.getElementById('payNewStatus').value = btn.dataset.status;
                    new bootstrap.Modal(document.getElementById('payStatusModal')).show();
                });
            });
        }).catch(function(e){ tbody.innerHTML='<tr><td colspan="9" class="text-center text-danger py-4">'+e.message+'</td></tr>'; });
    }

    document.getElementById('paySaveStatus').addEventListener('click', function(){
        const s = document.getElementById('payNewStatus').value;
        window.LuxeAdminApi.put('/admin/payments/'+currentPayId, {status:s}).then(function(){
            AdminUI.showToast('Payment status updated');
            bootstrap.Modal.getInstance(document.getElementById('payStatusModal'))?.hide();
            loadPayments();
        }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
    });

    document.addEventListener('DOMContentLoaded', loadPayments);
})();
</script>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
