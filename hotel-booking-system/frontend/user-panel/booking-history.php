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
                    <input type="text" class="form-control lux-input" id="bookingSearchInput" placeholder="Search by hotel or booking ID...">
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

        <div id="bookingHistoryList" class="d-flex flex-column gap-4">
            <?php
            $bookings = [
                ['id' => 'BK-2026-0847', 'hotel' => 'Grand Luxe Resort', 'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80', 'checkIn' => '2026-06-15', 'checkOut' => '2026-06-20', 'status' => 'confirmed', 'total' => 1920, 'guests' => 2],
                ['id' => 'BK-2026-0721', 'hotel' => 'Azure Palm Dubai', 'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&q=80', 'checkIn' => '2026-05-01', 'checkOut' => '2026-05-05', 'status' => 'completed', 'total' => 2145, 'guests' => 2],
                ['id' => 'BK-2026-0612', 'hotel' => 'Ocean Pearl Maldives', 'image' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=400&q=80', 'checkIn' => '2026-04-10', 'checkOut' => '2026-04-17', 'status' => 'cancelled', 'total' => 4193, 'guests' => 4],
                ['id' => 'BK-2026-0503', 'hotel' => 'Tokyo Imperial Tower', 'image' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=400&q=80', 'checkIn' => '2026-03-20', 'checkOut' => '2026-03-23', 'status' => 'completed', 'total' => 987, 'guests' => 1],
            ];
            foreach ($bookings as $booking):
                include __DIR__ . '/components/booking-card.php';
            endforeach;
            ?>
        </div>
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
                <p class="text-muted small">Cancellation policies may apply. This is a demo action.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-lux-outline" data-bs-dismiss="modal">Keep Booking</button>
                <button type="button" class="btn btn-lux-danger" id="confirmCancelBtn">Confirm Cancellation</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
