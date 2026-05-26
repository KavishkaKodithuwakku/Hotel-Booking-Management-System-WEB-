<?php
/**
 * Reusable hotel card component.
 * Expects: $hotel array with id, name, location, image, rating, reviews, price, stars, slug
 */
$hotel = $hotel ?? [
    'id' => 1,
    'name' => 'Grand Luxe Resort',
    'location' => 'Paris, France',
    'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
    'rating' => 4.8,
    'reviews' => 324,
    'price' => 299,
    'stars' => 5,
    'slug' => 'grand-luxe-resort',
];
$detailUrl = $pagePath . '/hotel-details.php?id=' . urlencode($hotel['id']);
?>
<article class="hotel-card lux-card" data-hotel-id="<?= (int)$hotel['id'] ?>" data-price="<?= (float)$hotel['price'] ?>" data-stars="<?= (int)$hotel['stars'] ?>">
    <div class="hotel-card-image">
        <img src="<?= htmlspecialchars($hotel['image']) ?>" alt="<?= htmlspecialchars($hotel['name']) ?>" loading="lazy">
        <div class="hotel-card-overlay"></div>
        <span class="hotel-badge"><?= (int)$hotel['stars'] ?> <i class="fas fa-star"></i></span>
        <button type="button" class="btn-favorite" aria-label="Add to favorites"><i class="far fa-heart"></i></button>
    </div>
    <div class="hotel-card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="hotel-card-title"><?= htmlspecialchars($hotel['name']) ?></h5>
                <p class="hotel-card-location"><i class="fas fa-map-marker-alt text-gold me-1"></i><?= htmlspecialchars($hotel['location']) ?></p>
            </div>
            <div class="hotel-rating">
                <span class="rating-value"><?= number_format($hotel['rating'], 1) ?></span>
                <small class="text-muted d-block"><?= (int)$hotel['reviews'] ?> reviews</small>
            </div>
        </div>
        <div class="hotel-card-footer">
            <div class="hotel-price">
                <span class="price-from">From</span>
                <span class="price-amount">$<?= number_format($hotel['price']) ?></span>
                <span class="price-night">/ night</span>
            </div>
            <a href="<?= $detailUrl ?>" class="btn btn-lux-primary btn-sm">View Details</a>
        </div>
    </div>
</article>
