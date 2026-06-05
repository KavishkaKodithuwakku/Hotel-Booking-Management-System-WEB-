<?php
$pageTitle    = 'Review Management';
$currentPage  = 'reviews.php';
$pageHeading  = 'Review Management';
$pageSubheading = 'Moderate guest reviews and send replies';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<!-- Summary badges -->
<div class="row g-3 mb-4" id="reviewSummaryCards">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon gold"><i class="fas fa-clock"></i></div>
            <h3 id="countPending">–</h3>
            <p>Pending Review</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon green"><i class="fas fa-check-circle"></i></div>
            <h3 id="countApproved">–</h3>
            <p>Approved</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon navy"><i class="fas fa-times-circle"></i></div>
            <h3 id="countRejected">–</h3>
            <p>Rejected</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-star text-warning me-2"></i>All Reviews</h5>
        <div class="d-flex gap-2 flex-wrap">
            <select class="form-select admin-input" style="width:auto" id="reviewStatusFilter">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="reviewsTableBody">
                <tr><td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading reviews…</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Reply / Moderate modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reviewModalBody">
                <div class="mb-3">
                    <label class="admin-form-label">Hotel</label>
                    <p id="rm_hotel" class="fw-semibold mb-1"></p>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Customer</label>
                    <p id="rm_customer" class="mb-1"></p>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Rating</label>
                    <p id="rm_stars" class="mb-1"></p>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Review</label>
                    <p id="rm_comment" class="mb-1 fst-italic text-muted"></p>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Admin Reply (optional)</label>
                    <textarea class="form-control admin-input" id="rm_reply" rows="3" placeholder="Write a reply to this customer…"></textarea>
                </div>
            </div>
            <div class="modal-footer gap-2 flex-wrap">
                <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="rm_reject">Reject</button>
                <button type="button" class="btn btn-success" id="rm_approve">Approve</button>
                <button type="button" class="btn btn-admin-primary" id="rm_save_reply">Save Reply</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let currentId = null;

    function stars(n){ return '<i class="fas fa-star text-warning"></i>'.repeat(n) + '<i class="far fa-star text-warning"></i>'.repeat(5-n); }
    function badgeClass(s){ return s==='approved'?'badge-confirmed':s==='pending'?'badge-pending':'badge-cancelled'; }

    function loadReviews(status=''){
        const tbody = document.getElementById('reviewsTableBody');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>';
        const qs = status ? '?status='+status : '';
        window.LuxeAdminApi.get('/admin/reviews'+qs).then(function(data){
            const s = data.summary||{};
            document.getElementById('countPending').textContent = s.pending||0;
            document.getElementById('countApproved').textContent = s.approved||0;
            document.getElementById('countRejected').textContent = s.rejected||0;

            if(!data.reviews||!data.reviews.length){
                tbody.innerHTML='<tr><td colspan="7" class="text-center py-4 text-muted">No reviews found.</td></tr>';
                return;
            }
            tbody.innerHTML = data.reviews.map(function(r){
                return `<tr data-id="${r.id}">
                    <td><strong>${r.hotel_name}</strong></td>
                    <td>${r.customer}<br><small class="text-muted">${r.email}</small></td>
                    <td>${stars(r.rating)}</td>
                    <td class="text-muted small" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${r.comment||'–'}</td>
                    <td>${new Date(r.created_at).toLocaleDateString()}</td>
                    <td><span class="badge-status ${badgeClass(r.status)}">${r.status}</span></td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm btn-view-review" data-review='${JSON.stringify(r).replace(/'/g,"&apos;")}'>
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-admin-outline btn-admin-sm text-danger btn-delete-review" data-id="${r.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            tbody.querySelectorAll('.btn-view-review').forEach(function(btn){
                btn.addEventListener('click', function(){
                    const r = JSON.parse(btn.getAttribute('data-review').replace(/&apos;/g,"'"));
                    openModal(r);
                });
            });
            tbody.querySelectorAll('.btn-delete-review').forEach(function(btn){
                btn.addEventListener('click', function(){
                    if(!confirm('Delete this review permanently?')) return;
                    window.LuxeAdminApi.delete('/admin/reviews/'+btn.dataset.id).then(function(){
                        AdminUI.showToast('Review deleted');
                        loadReviews(document.getElementById('reviewStatusFilter').value);
                    }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
                });
            });
        }).catch(function(e){ tbody.innerHTML='<tr><td colspan="7" class="text-center text-danger py-4">'+e.message+'</td></tr>'; });
    }

    function openModal(r){
        currentId = r.id;
        document.getElementById('rm_hotel').textContent    = r.hotel_name;
        document.getElementById('rm_customer').textContent = r.customer + ' — ' + r.email;
        document.getElementById('rm_stars').innerHTML      = stars(r.rating) + ' ('+r.rating+'/5)';
        document.getElementById('rm_comment').textContent  = r.comment||'No comment';
        document.getElementById('rm_reply').value          = r.admin_reply||'';
        new bootstrap.Modal(document.getElementById('reviewModal')).show();
    }

    function moderateReview(status, reply){
        const body = {status:status};
        if(reply) body.admin_reply = reply;
        window.LuxeAdminApi.put('/admin/reviews/'+currentId, body).then(function(){
            AdminUI.showToast('Review '+status);
            bootstrap.Modal.getInstance(document.getElementById('reviewModal'))?.hide();
            loadReviews(document.getElementById('reviewStatusFilter').value);
        }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
    }

    document.getElementById('rm_approve').addEventListener('click', function(){
        moderateReview('approved', document.getElementById('rm_reply').value);
    });
    document.getElementById('rm_reject').addEventListener('click', function(){
        moderateReview('rejected', document.getElementById('rm_reply').value);
    });
    document.getElementById('rm_save_reply').addEventListener('click', function(){
        const reply = document.getElementById('rm_reply').value.trim();
        if(!reply){ AdminUI.showToast('Please write a reply first','error'); return; }
        moderateReview('approved', reply);
    });

    document.getElementById('reviewStatusFilter').addEventListener('change', function(){
        loadReviews(this.value);
    });

    document.addEventListener('DOMContentLoaded', function(){ loadReviews(); });
})();
</script>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
