<?php
$pageTitle = 'Hotel Details';
$bodyClass = 'page-hotel-details';
$hotelId = $_GET['id'] ?? '1';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/navbar.php';
?>

<section class="section-padding pt-4">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb lux-breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $pagePath ?>/index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= $pagePath ?>/hotels.php">Hotels</a></li>
                <li class="breadcrumb-item active" id="hotelBreadcrumbName">Hotel Details</li>
            </ol>
        </nav>

        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="gallery-main lux-card mb-3" id="galleryMain">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80" alt="Hotel" id="galleryMainImg">
                    <button class="gallery-nav gallery-prev" type="button" id="galleryPrev"><i class="fas fa-chevron-left"></i></button>
                    <button class="gallery-nav gallery-next" type="button" id="galleryNext"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="gallery-thumbs" id="galleryThumbs">
                    <?php
                    $galleryImages = [
                        'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80',
                        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&q=80',
                        'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=400&q=80',
                        'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400&q=80',
                    ];
                    foreach ($galleryImages as $i => $img):
                    ?>
                    <button type="button" class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>" data-image="<?= $img ?>">
                        <img src="<?= $img ?>" alt="Thumbnail">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="hotel-info-card lux-card sticky-top hotel-sticky-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="hotel-stars-badge" id="hotelStars">5 <i class="fas fa-star"></i></span>
                        <span class="rating-badge" id="hotelRatingBadge">4.9</span>
                    </div>
                    <h1 class="hotel-detail-title" id="hotelDetailName">Grand Luxe Resort</h1>
                    <p class="text-muted" id="hotelDetailLocation"><i class="fas fa-map-marker-alt text-gold me-1"></i> Paris, France</p>
                    <p class="hotel-description" id="hotelDetailDesc">Experience unparalleled luxury in the heart of Paris. Elegant rooms, Michelin-star dining, and world-class spa facilities await.</p>
                    <div class="price-block my-4">
                        <span class="price-from">From</span>
                        <span class="price-amount-lg" id="hotelDetailPrice">$349</span>
                        <span class="price-night">/ night</span>
                    </div>
                    <form id="availabilityForm" class="availability-form">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small">Check-in</label>
                                <input type="date" class="form-control lux-input" name="checkIn" id="availCheckIn" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Check-out</label>
                                <input type="date" class="form-control lux-input" name="checkOut" id="availCheckOut" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Guests</label>
                            <select class="form-select lux-input" name="guests">
                                <option value="1">1 Guest</option>
                                <option value="2" selected>2 Guests</option>
                                <option value="3">3 Guests</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-lux-outline w-100 mb-2" id="checkAvailabilityBtn">
                            <i class="fas fa-calendar-check me-2"></i>Check Availability
                        </button>
                        <div id="availabilityResult" class="availability-result d-none"></div>
                        <a href="<?= $pagePath ?>/booking.php?hotel=<?= htmlspecialchars($hotelId) ?>" class="btn btn-lux-primary w-100 btn-lg" id="bookNowBtn">
                            <i class="fas fa-concierge-bell me-2"></i>Book Now
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Facilities -->
        <div class="lux-card p-4 mb-5">
            <h3 class="section-subtitle mb-4"><i class="fas fa-concierge-bell text-gold me-2"></i>Hotel Facilities</h3>
            <div class="row g-3 facilities-grid">
                <?php
                $facilities = [
                    ['icon' => 'fa-swimming-pool', 'name' => 'Infinity Pool'],
                    ['icon' => 'fa-spa', 'name' => 'Luxury Spa'],
                    ['icon' => 'fa-utensils', 'name' => 'Fine Dining'],
                    ['icon' => 'fa-dumbbell', 'name' => 'Fitness Center'],
                    ['icon' => 'fa-wifi', 'name' => 'High-Speed WiFi'],
                    ['icon' => 'fa-car', 'name' => 'Valet Parking'],
                    ['icon' => 'fa-glass-martini-alt', 'name' => 'Rooftop Bar'],
                    ['icon' => 'fa-bell', 'name' => '24/7 Concierge'],
                ];
                foreach ($facilities as $f):
                ?>
                <div class="col-6 col-md-3">
                    <div class="facility-item">
                        <i class="fas <?= $f['icon'] ?> text-gold"></i>
                        <span><?= $f['name'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Rooms -->
        <h3 class="section-subtitle mb-4">Available <span class="text-gold">Rooms</span></h3>
        <div class="row g-4 mb-5" id="roomsList">
            <?php
            $rooms = [
                ['name' => 'Deluxe King Room', 'size' => '45 m²', 'beds' => '1 King Bed', 'price' => 349, 'image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80', 'amenities' => ['WiFi', 'Mini Bar', 'City View']],
                ['name' => 'Executive Suite', 'size' => '72 m²', 'beds' => '1 King + Living', 'price' => 549, 'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80', 'amenities' => ['WiFi', 'Butler', 'Eiffel View']],
                ['name' => 'Presidential Suite', 'size' => '120 m²', 'beds' => '2 King Beds', 'price' => 1299, 'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&q=80', 'amenities' => ['WiFi', 'Private Terrace', 'Jacuzzi']],
            ];
            foreach ($rooms as $room):
            ?>
            <div class="col-md-4">
                <div class="room-card lux-card h-100">
                    <div class="room-card-image">
                        <img src="<?= $room['image'] ?>" alt="<?= $room['name'] ?>">
                    </div>
                    <div class="room-card-body p-4">
                        <h5><?= $room['name'] ?></h5>
                        <p class="text-muted small mb-2"><i class="fas fa-ruler-combined me-1"></i><?= $room['size'] ?> · <?= $room['beds'] ?></p>
                        <div class="room-amenities mb-3">
                            <?php foreach ($room['amenities'] as $a): ?>
                            <span class="amenity-tag"><?= $a ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="hotel-price">
                                <span class="price-amount">$<?= $room['price'] ?></span>
                                <span class="price-night">/ night</span>
                            </div>
                            <a href="<?= $pagePath ?>/booking.php?room=<?= urlencode($room['name']) ?>" class="btn btn-lux-primary btn-sm">Select</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Reviews -->
        <div class="lux-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h3 class="section-subtitle mb-0">Guest <span class="text-gold">Reviews</span></h3>
                <button class="btn btn-lux-outline btn-sm" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                    <i class="fas fa-pen me-1"></i>Write a Review
                </button>
            </div>
            <div class="review-summary mb-4">
                <div class="review-score-big">4.9</div>
                <div>
                    <div class="review-stars mb-1">
                        <i class="fas fa-star text-gold"></i><i class="fas fa-star text-gold"></i><i class="fas fa-star text-gold"></i>
                        <i class="fas fa-star text-gold"></i><i class="fas fa-star text-gold"></i>
                    </div>
                    <span class="text-muted">Based on 412 reviews</span>
                </div>
            </div>
            <div class="review-list">
                <?php
                $hotelReviews = [
                    ['user' => 'Michael R.', 'date' => 'March 2026', 'rating' => 5, 'text' => 'Stunning property with impeccable service. The suite view was breathtaking.'],
                    ['user' => 'Lisa K.', 'date' => 'February 2026', 'rating' => 5, 'text' => 'Every detail was perfect. Will definitely return through LuxeStay.'],
                ];
                foreach ($hotelReviews as $hr):
                ?>
                <div class="review-item">
                    <div class="d-flex justify-content-between">
                        <strong><?= $hr['user'] ?></strong>
                        <small class="text-muted"><?= $hr['date'] ?></small>
                    </div>
                    <div class="review-stars small my-1">
                        <?php for ($i = 0; $i < $hr['rating']; $i++): ?><i class="fas fa-star text-gold"></i><?php endfor; ?>
                    </div>
                    <p class="mb-0"><?= $hr['text'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Write Review Modal -->
<div class="modal fade lux-modal" id="writeReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content lux-card">
            <div class="modal-header">
                <h5 class="modal-title">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="star-rating-input" id="starRatingInput">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <button type="button" class="star-btn" data-rating="<?= $i ?>"><i class="far fa-star"></i></button>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="reviewRating" value="5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Review</label>
                        <textarea class="form-control lux-input" name="review" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-lux-primary">Submit Review</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
