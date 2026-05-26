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
