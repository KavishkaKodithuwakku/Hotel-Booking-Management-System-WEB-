<?php
/**
 * Reusable booking history card.
 */
$booking = $booking ?? [
    'id' => 'BK-1001',
    'hotel' => 'Grand Luxe Resort',
    'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80',
    'checkIn' => '2026-06-15',
    'checkOut' => '2026-06-20',
    'status' => 'confirmed',
    'total' => 1495,
    'guests' => 2,
];
$statusClass = match(strtolower($booking['status'])) {
    'confirmed' => 'badge-confirmed',
    'pending' => 'badge-pending',
    'cancelled' => 'badge-cancelled',
    'completed' => 'badge-completed',
    default => 'badge-pending',
};
?>
<div class="booking-card lux-card" data-booking-id="<?= htmlspecialchars($booking['id']) ?>">
    <div class="row g-0 align-items-center">
        <div class="col-md-3">
            <div class="booking-card-image">
                <img src="<?= htmlspecialchars($booking['image']) ?>" alt="<?= htmlspecialchars($booking['hotel']) ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="booking-card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="mb-0"><?= htmlspecialchars($booking['hotel']) ?></h5>
                    <span class="status-badge <?= $statusClass ?>"><?= ucfirst($booking['status']) ?></span>
                </div>
                <p class="text-muted small mb-1"><i class="fas fa-hashtag me-1"></i><?= htmlspecialchars($booking['id']) ?></p>
                <p class="mb-1"><i class="fas fa-calendar text-gold me-2"></i><?= htmlspecialchars($booking['checkIn']) ?> → <?= htmlspecialchars($booking['checkOut']) ?></p>
                <p class="mb-0"><i class="fas fa-users text-gold me-2"></i><?= (int)$booking['guests'] ?> Guests</p>
            </div>
        </div>
        <div class="col-md-3 text-md-end p-3">
            <p class="booking-total mb-2">$<?= number_format($booking['total']) ?></p>
            <div class="d-flex flex-column gap-2">
                <a href="<?= $pagePath ?>/hotel-details.php" class="btn btn-lux-outline btn-sm">View Hotel</a>
                <?php if ($booking['status'] !== 'cancelled'): ?>
                <button type="button" class="btn btn-lux-danger btn-sm btn-cancel-booking" data-id="<?= htmlspecialchars($booking['id']) ?>">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
