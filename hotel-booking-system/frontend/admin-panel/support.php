<?php
$pageTitle    = 'Support Messages';
$currentPage  = 'support.php';
$pageHeading  = 'Support & Contact Messages';
$pageSubheading = 'Read and reply to customer enquiries';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon gold"><i class="fas fa-envelope"></i></div>
            <h3 id="countNew">–</h3>
            <p>New Messages</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon navy"><i class="fas fa-envelope-open"></i></div>
            <h3 id="countRead">–</h3>
            <p>Read</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-card-icon green"><i class="fas fa-reply"></i></div>
            <h3 id="countReplied">–</h3>
            <p>Replied</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-headset text-gold me-2"></i>All Messages</h5>
        <select class="form-select admin-input" style="width:auto" id="supportStatusFilter">
            <option value="">All Statuses</option>
            <option value="new">New</option>
            <option value="read">Read</option>
            <option value="replied">Replied</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="supportTableBody">
                <tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Message detail + reply modal -->
<div class="modal fade" id="supportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="admin-form-label">From</label>
                        <p id="sm_from" class="fw-semibold mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Email</label>
                        <p id="sm_email" class="mb-0"></p>
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Subject</label>
                        <p id="sm_subject" class="mb-0 fw-semibold"></p>
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Message</label>
                        <div class="p-3 bg-light rounded" id="sm_message"></div>
                    </div>
                </div>
                <div id="existingReplyWrap" class="d-none mb-3">
                    <label class="admin-form-label">Previous Reply</label>
                    <div class="p-3 bg-light rounded text-muted fst-italic" id="sm_existing_reply"></div>
                </div>
                <div>
                    <label class="admin-form-label">Reply Message</label>
                    <textarea class="form-control admin-input" id="sm_reply" rows="4" placeholder="Type your reply to the customer…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-admin-outline" id="sm_mark_read">Mark as Read</button>
                <button type="button" class="btn btn-admin-primary" id="sm_send_reply">
                    <i class="fas fa-paper-plane me-1"></i>Send Reply
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let currentId = null;

    function badgeClass(s){ return s==='new'?'badge-pending':s==='replied'?'badge-confirmed':'badge-paid'; }

    function loadMessages(status=''){
        const tbody = document.getElementById('supportTableBody');
        tbody.innerHTML='<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr>';
        const qs = status ? '?status='+status : '';
        window.LuxeAdminApi.get('/admin/support'+qs).then(function(data){
            const s = data.summary||{};
            document.getElementById('countNew').textContent     = s.new||0;
            document.getElementById('countRead').textContent    = s.read||0;
            document.getElementById('countReplied').textContent = s.replied||0;

            if(!data.messages||!data.messages.length){
                tbody.innerHTML='<tr><td colspan="5" class="text-center py-4 text-muted">No messages found.</td></tr>';
                return;
            }
            tbody.innerHTML = data.messages.map(function(m){
                return `<tr>
                    <td><strong>${m.name}</strong><br><small class="text-muted">${m.email}</small></td>
                    <td>${m.subject||'–'}</td>
                    <td>${new Date(m.created_at).toLocaleDateString()}</td>
                    <td><span class="badge-status ${badgeClass(m.status)}">${m.status}</span></td>
                    <td>
                        <button class="btn btn-admin-primary btn-admin-sm btn-view-msg" data-msg='${JSON.stringify(m).replace(/'/g,"&apos;")}'>
                            <i class="fas fa-reply me-1"></i>Reply
                        </button>
                    </td>
                </tr>`;
            }).join('');

            tbody.querySelectorAll('.btn-view-msg').forEach(function(btn){
                btn.addEventListener('click', function(){
                    const m = JSON.parse(btn.getAttribute('data-msg').replace(/&apos;/g,"'"));
                    openModal(m);
                });
            });
        }).catch(function(e){ tbody.innerHTML='<tr><td colspan="5" class="text-center text-danger py-4">'+e.message+'</td></tr>'; });
    }

    function openModal(m){
        currentId = m.id;
        document.getElementById('sm_from').textContent       = m.name;
        document.getElementById('sm_email').textContent      = m.email;
        document.getElementById('sm_subject').textContent    = m.subject||'General Inquiry';
        document.getElementById('sm_message').textContent    = m.message;
        document.getElementById('sm_reply').value            = '';

        const prev = document.getElementById('existingReplyWrap');
        if(m.reply_message){
            prev.classList.remove('d-none');
            document.getElementById('sm_existing_reply').textContent = m.reply_message;
        } else {
            prev.classList.add('d-none');
        }

        // Mark as read when opened
        if(m.status === 'new'){
            window.LuxeAdminApi.put('/admin/support/'+m.id, {status:'read'}).catch(function(){});
        }

        new bootstrap.Modal(document.getElementById('supportModal')).show();
    }

    document.getElementById('sm_send_reply').addEventListener('click', function(){
        const reply = document.getElementById('sm_reply').value.trim();
        if(!reply){ AdminUI.showToast('Please write a reply first','error'); return; }
        window.LuxeAdminApi.put('/admin/support/'+currentId, {reply_message: reply, status:'replied'}).then(function(){
            AdminUI.showToast('Reply sent');
            bootstrap.Modal.getInstance(document.getElementById('supportModal'))?.hide();
            loadMessages(document.getElementById('supportStatusFilter').value);
        }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
    });

    document.getElementById('sm_mark_read').addEventListener('click', function(){
        window.LuxeAdminApi.put('/admin/support/'+currentId, {status:'read'}).then(function(){
            AdminUI.showToast('Marked as read');
            bootstrap.Modal.getInstance(document.getElementById('supportModal'))?.hide();
            loadMessages(document.getElementById('supportStatusFilter').value);
        }).catch(function(e){ AdminUI.showToast(e.message,'error'); });
    });

    document.getElementById('supportStatusFilter').addEventListener('change', function(){
        loadMessages(this.value);
    });

    document.addEventListener('DOMContentLoaded', function(){ loadMessages(); });
})();
</script>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
