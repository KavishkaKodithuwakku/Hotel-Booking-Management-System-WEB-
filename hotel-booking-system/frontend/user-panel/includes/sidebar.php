<?php
/**
 * Filter / account sidebar — used on hotels and profile sections.
 */
$sidebarType = $sidebarType ?? 'filter';
$activeFilter = $activeFilter ?? '';
?>
<aside class="lux-sidebar" id="luxSidebar">
    <?php if ($sidebarType === 'filter'): ?>
    <div class="sidebar-block">
        <h5 class="sidebar-title"><i class="fas fa-sliders-h me-2"></i>Filters</h5>
        <form id="hotelFilterForm" class="filter-form">
            <div class="mb-3">
                <label class="form-label">Destination</label>
                <select class="form-select lux-input" name="destination" id="filterDestination">
                    <option value="">All destinations</option>
                    <option value="paris">Paris</option>
                    <option value="dubai">Dubai</option>
                    <option value="maldives">Maldives</option>
                    <option value="tokyo">Tokyo</option>
                    <option value="new-york">New York</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Star Rating</label>
                <div class="star-filter">
                    <?php for ($i = 5; $i >= 3; $i--): ?>
                    <label class="star-option">
                        <input type="radio" name="stars" value="<?= $i ?>" <?= $i === 5 ? '' : '' ?>>
                        <span><?= $i ?>+ <i class="fas fa-star text-gold"></i></span>
                    </label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Price Range</label>
                <input type="range" class="form-range lux-range" id="priceRange" name="maxPrice" min="100" max="2000" value="2000" step="50">
                <div class="d-flex justify-content-between small text-muted">
                    <span>$100</span>
                    <span id="priceRangeLabel">Up to $2000</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Amenities</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="amenities[]" value="pool" id="amenityPool">
                        <label class="form-check-label" for="amenityPool">Swimming Pool</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="amenities[]" value="spa" id="amenitySpa">
                        <label class="form-check-label" for="amenitySpa">Spa</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="amenityWifi">
                        <label class="form-check-label" for="amenityWifi">Free WiFi</label>
                    </div>
            </div>
            <button type="submit" class="btn btn-lux-primary w-100">
                <i class="fas fa-filter me-2"></i>Apply Filters
            </button>
            <button type="reset" class="btn btn-lux-outline w-100 mt-2" id="resetFilters">Reset</button>
        </form>
    </div>
    <?php elseif ($sidebarType === 'profile'): ?>
    <nav class="profile-nav">
        <a href="<?= $pagePath ?>/profile.php" class="profile-nav-link active"><i class="fas fa-user"></i> My Profile</a>
        <a href="<?= $pagePath ?>/booking-history.php" class="profile-nav-link"><i class="fas fa-history"></i> Booking History</a>
        <a href="<?= $pagePath ?>/booking.php" class="profile-nav-link"><i class="fas fa-calendar-plus"></i> New Booking</a>
        <a href="<?= $pagePath ?>/contact.php" class="profile-nav-link"><i class="fas fa-headset"></i> Support</a>
    </nav>
    <?php endif; ?>
</aside>
