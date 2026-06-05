<?php
$pageTitle = 'Hotel Image Management';
$currentPage = 'images.php';
$pageHeading = 'Hotel Image Management';
$pageSubheading = 'Upload and organize hotel gallery images';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';
?>

<div class="admin-card mb-3">
    <div class="admin-card-header">
        <h5>Upload Images</h5>
    </div>
    <div class="admin-card-body">
        <form data-admin-form data-success-msg="Images uploaded successfully" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="admin-form-label">Select Hotel</label>
                <select class="form-select admin-input" required>
                    <option>Grand Luxe Resort</option>
                    <option>Azure Palm Dubai</option>
                    <option>Ocean Pearl Maldives</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="admin-form-label">Choose Files</label>
                <input type="file" class="form-control admin-input" accept="image/*" multiple>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-admin-primary w-100"><i class="fas fa-upload me-1"></i> Upload</button>
            </div>
        </form>
    </div>
</div>

<div class="admin-card mb-3">
    <div class="admin-card-header">
        <h5>Hotel Details</h5>
        <span class="badge-status badge-paid">Featured Property</span>
    </div>
    <div class="admin-card-body">
        <div class="hotel-detail-grid">
            <div>
                <h6>Location</h6>
                <p class="hotel-location">123 Harbor View Boulevard<br>Downtown Bay City, CA 90210</p>
                <div class="hotel-location-meta">
                    <span><i class="fas fa-map-marker-alt"></i> Waterfront district</span>
                    <span><i class="fas fa-route"></i> 2.3 km from airport</span>
                </div>
                <div class="hotel-location-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.0199273133196!2d-122.41941548468333!3d37.77492927975998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c3b6a7e35%3A0xf63f5c3f3e5b2d7d!2sSan%20Francisco%2C%20CA!5e0!3m2!1sen!2sus!4v1716924485234!5m2!1sen!2sus"
                        width="100%"
                        height="230"
                        style="border:0; border-radius: 14px;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div>
                <h6>Ratings</h6>
                <div class="hotel-rating-summary">
                    <strong>4.8</strong>
                    <span>/ 5.0</span>
                </div>
                <div class="hotel-rating-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <small>1,289 reviews</small>
                </div>
                <div class="hotel-rating-bars">
                    <div class="rating-bar">
                        <span>Cleanliness</span>
                        <div class="rating-bar-track"><div class="rating-bar-fill" style="width: 96%;"></div></div>
                    </div>
                    <div class="rating-bar">
                        <span>Service</span>
                        <div class="rating-bar-track"><div class="rating-bar-fill" style="width: 93%;"></div></div>
                    </div>
                    <div class="rating-bar">
                        <span>Location</span>
                        <div class="rating-bar-track"><div class="rating-bar-fill" style="width: 98%;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>Gallery — Grand Luxe Resort</h5>
        <select class="form-select admin-input" style="width: auto;">
            <option>Grand Luxe Resort</option>
            <option>Azure Palm Dubai</option>
        </select>
    </div>
    <div class="admin-card-body">
        <div class="image-grid">
            <?php
            $images = [
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80',
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&q=80',
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&q=80',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=400&q=80',
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=400&q=80',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=400&q=80',
            ];
            foreach ($images as $img): ?>
            <div class="image-grid-item">
                <img src="<?= $img ?>" alt="Hotel gallery">
                <div class="image-grid-overlay">
                    <button type="button" class="btn btn-light btn-sm"><i class="fas fa-star"></i></button>
                    <button type="button" class="btn btn-danger btn-sm" data-admin-delete><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
