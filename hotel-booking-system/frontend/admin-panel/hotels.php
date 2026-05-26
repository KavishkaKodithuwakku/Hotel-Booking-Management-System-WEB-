<?php
$pageTitle = 'Hotel Management';
$currentPage = 'hotels.php';
$pageHeading = 'Hotel Management';
$pageSubheading = 'Add, edit, and manage hotel listings';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';

$hotels = [
    ['id' => 1, 'name' => 'Grand Luxe Resort', 'location' => 'Paris, France', 'rooms' => 120, 'rating' => 4.9, 'status' => 'active'],
    ['id' => 2, 'name' => 'Azure Palm Dubai', 'location' => 'Dubai, UAE', 'rooms' => 200, 'rating' => 4.8, 'status' => 'active'],
    ['id' => 3, 'name' => 'Ocean Pearl Maldives', 'location' => 'Maldives', 'rooms' => 45, 'rating' => 4.9, 'status' => 'active'],
    ['id' => 4, 'name' => 'Tokyo Imperial Tower', 'location' => 'Tokyo, Japan', 'rooms' => 180, 'rating' => 4.7, 'status' => 'inactive'],
];
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>All Hotels (<?= count($hotels) ?>)</h5>
        <button type="button" class="btn btn-admin-primary btn-sm" data-bs-toggle="modal" data-bs-target="#hotelModal">
            <i class="fas fa-plus me-1"></i> Add Hotel
        </button>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hotel Name</th>
                    <th>Location</th>
                    <th>Rooms</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $h): ?>
                <tr>
                    <td>#<?= $h['id'] ?></td>
                    <td><strong><?= htmlspecialchars($h['name']) ?></strong></td>
                    <td><?= htmlspecialchars($h['location']) ?></td>
                    <td><?= $h['rooms'] ?></td>
                    <td><i class="fas fa-star text-warning"></i> <?= $h['rating'] ?></td>
                    <td>
                        <span class="badge-status <?= $h['status'] === 'active' ? 'badge-confirmed' : 'badge-pending' ?>">
                            <?= ucfirst($h['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm" data-bs-toggle="modal" data-bs-target="#hotelModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-admin-outline btn-admin-sm text-danger" data-admin-delete><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="hotelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add / Edit Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-admin-form data-success-msg="Hotel saved successfully">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="admin-form-label">Hotel Name</label>
                        <input type="text" class="form-control admin-input" required>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Location</label>
                        <input type="text" class="form-control admin-input" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="admin-form-label">Star Rating</label>
                            <select class="form-select admin-input" required>
                                <option>5 Stars</option>
                                <option>4 Stars</option>
                                <option>3 Stars</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="admin-form-label">Status</label>
                            <select class="form-select admin-input">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-0 mt-3">
                        <label class="admin-form-label">Description</label>
                        <textarea class="form-control admin-input" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-primary">Save Hotel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
