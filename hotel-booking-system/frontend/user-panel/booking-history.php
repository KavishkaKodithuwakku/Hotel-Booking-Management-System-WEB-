<?php
$pageTitle = 'Booking History';
$bodyClass = 'page-booking-history';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/navbar.php';
?>

<section class="page-hero page-hero-sm">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <h1 class="page-hero-title">Booking <span class="text-gold">History</span></h1>
        <p class="page-hero-subtitle">Manage and track all your reservations</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="booking-filters lux-card p-3 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control lux-input" id="bookingSearchInput" placeholder="Search by hotel or booking ID…">
                </div>
                <div class="col-md-3">
                    <select class="form-select lux-input" id="bookingStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select lux-input" id="bookingSort">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading / empty states -->
        <div id="bookingHistoryLoading" class="text-center py-5 text-muted">
            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
            <p>Loading your bookings…</p>
        </div>
        <div id="bookingHistoryEmpty" class="text-center py-5 d-none">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No bookings found</h4>
            <a href="<?= $pagePath ?>/hotels.php" class="btn btn-lux-primary mt-2">Browse Hotels</a>
        </div>
        <div id="bookingHistoryList" class="d-flex flex-column gap-4"></div>
    </div>
</section>

<!-- Cancel Booking Modal -->
<div class="modal fade lux-modal" id="cancelBookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lux-card">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel booking <strong id="cancelBookingId"></strong>?</p>
                <p class="text-muted small">Cancellation policies may apply.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-lux-outline" data-bs-dismiss="modal">Keep Booking</button>
                <button type="button" class="btn btn-lux-danger" id="confirmCancelBtn">Confirm Cancellation</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let cancelTarget = null;
    let allBookings  = [];

    function statusClass(s){
        return s==='confirmed'?'badge-confirmed':s==='pending'?'badge-pending':s==='cancelled'?'badge-cancelled':'badge-completed';
    }

    function renderCard(b){
        const hotelImg = b.hotel_image ||
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80';
        return `<div class="booking-card lux-card" data-booking-id="${b.booking_ref}" data-hotel="${b.hotel.toLowerCase()}" data-status="${b.status}">
            <div class="row g-0 align-items-center">
                <div class="col-md-3">
                    <div class="booking-card-image">
                        <img src="${hotelImg}" alt="${b.hotel}" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80'">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="booking-card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0">${b.hotel}</h5>
                            <span class="status-badge ${statusClass(b.status)}">${b.status.charAt(0).toUpperCase()+b.status.slice(1)}</span>
                        </div>
                        <p class="text-muted small mb-1"><i class="fas fa-hashtag me-1"></i>${b.booking_ref}</p>
                        <p class="mb-1"><i class="fas fa-calendar text-gold me-2"></i>${b.check_in} → ${b.check_out}</p>
                        <p class="mb-1"><i class="fas fa-door-open text-gold me-2"></i>${b.room}</p>
                        <p class="mb-0"><i class="fas fa-users text-gold me-2"></i>${b.guests} Guest${b.guests>1?'s':''}</p>
                    </div>
                </div>
                <div class="col-md-3 text-md-end p-3">
                    <p class="booking-total mb-1">$${Number(b.total_amount).toLocaleString()}</p>
                    <small class="text-muted d-block mb-2">Payment: ${b.payment_status}</small>
                    <div class="d-flex flex-column gap-2">
                        ${b.payment_status==='pending'?`<a href="payment.php?booking_id=${b.id}" class="btn btn-lux-primary btn-sm"><i class="fas fa-credit-card me-1"></i>Pay Now</a>`:''}
                        ${b.status!=='cancelled'?`<button type="button" class="btn btn-lux-danger btn-sm btn-cancel-booking" data-id="${b.id}" data-ref="${b.booking_ref}"><i class="fas fa-times me-1"></i>Cancel</button>`:''}
                    </div>
                </div>
            </div>
        </div>`;
    }

    function applyFilters(){
        const search = document.getElementById('bookingSearchInput').value.toLowerCase();
        const status = document.getElementById('bookingStatusFilter').value;
        const sort   = document.getElementById('bookingSort').value;

        let filtered = allBookings.filter(function(b){
            const matchSearch = !search || b.hotel.toLowerCase().includes(search) || b.booking_ref.toLowerCase().includes(search);
            const matchStatus = !status || b.status === status;
            return matchSearch && matchStatus;
        });

        if(sort === 'oldest') filtered = filtered.reverse();

        const list  = document.getElementById('bookingHistoryList');
        const empty = document.getElementById('bookingHistoryEmpty');
        if(!filtered.length){
            list.innerHTML = '';
            empty.classList.remove('d-none');
        } else {
            empty.classList.add('d-none');
            list.innerHTML = filtered.map(renderCard).join('');
            list.querySelectorAll('.btn-cancel-booking').forEach(function(btn){
                btn.addEventListener('click', function(){
                    cancelTarget = btn.dataset.id;
                    document.getElementById('cancelBookingId').textContent = btn.dataset.ref;
                    new bootstrap.Modal(document.getElementById('cancelBookingModal')).show();
                });
            });
        }
    }

    function loadBookings(){
        if(!window.LuxeApi || !window.LuxeApi.getToken()){
            document.getElementById('bookingHistoryLoading').classList.add('d-none');
            document.getElementById('bookingHistoryEmpty').classList.remove('d-none');
            return;
        }
        window.LuxeApi.get('/bookings').then(function(data){
            document.getElementById('bookingHistoryLoading').classList.add('d-none');
            allBookings = data.bookings || [];
            if(!allBookings.length){
                document.getElementById('bookingHistoryEmpty').classList.remove('d-none');
                return;
            }
            applyFilters();
        }).catch(function(){
            document.getElementById('bookingHistoryLoading').innerHTML =
                '<p class="text-danger">Could not load bookings. Please log in first.</p>';
        });
    }

    document.getElementById('confirmCancelBtn').addEventListener('click', function(){
        if(!cancelTarget) return;
        window.LuxeApi.delete('/bookings/'+cancelTarget).then(function(){
            window.showToast && window.showToast('Booking cancelled', 'success');
            bootstrap.Modal.getInstance(document.getElementById('cancelBookingModal'))?.hide();
            loadBookings();
        }).catch(function(e){ window.showToast && window.showToast(e.message, 'error'); });
    });

    ['bookingSearchInput','bookingStatusFilter','bookingSort'].forEach(function(id){
        document.getElementById(id).addEventListener('input', applyFilters);
        document.getElementById(id).addEventListener('change', applyFilters);
    });

    document.addEventListener('DOMContentLoaded', loadBookings);
})();
</script>

<?php require_once __DIR__ . '/components/footer.php'; ?>
