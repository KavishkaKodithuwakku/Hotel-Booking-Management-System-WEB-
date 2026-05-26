<?php
$pageTitle = 'Room Management';
$currentPage = 'rooms.php';
$pageHeading = 'Room Management';
$pageSubheading = 'Manage room types, pricing, and capacity';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/components/layout-start.php';

$rooms = [
    ['id' => 'R101', 'hotel' => 'Grand Luxe Resort', 'type' => 'Deluxe Suite', 'price' => 349, 'capacity' => 2, 'status' => 'available'],
    ['id' => 'R102', 'hotel' => 'Grand Luxe Resort', 'type' => 'Executive King', 'price' => 289, 'capacity' => 2, 'status' => 'occupied'],
    ['id' => 'R201', 'hotel' => 'Azure Palm Dubai', 'type' => 'Royal Villa', 'price' => 899, 'capacity' => 4, 'status' => 'available'],
    ['id' => 'R301', 'hotel' => 'Ocean Pearl Maldives', 'type' => 'Overwater Bungalow', 'price' => 599, 'capacity' => 2, 'status' => 'occupied'],
];
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>All Rooms</h5>
        <button type="button" class="btn btn-admin-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roomModal">
            <i class="fas fa-plus me-1"></i> Add Room
        </button>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Room ID</th>
                    <th>Hotel</th>
                    <th>Type</th>
                    <th>Price/Night</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['hotel']) ?></td>
                    <td><?= htmlspecialchars($r['type']) ?></td>
                    <td>$<?= $r['price'] ?></td>
                    <td><?= $r['capacity'] ?> guests</td>
                    <td>
                        <span class="badge-status <?= $r['status'] === 'available' ? 'badge-available' : 'badge-occupied' ?>">
                            <?= ucfirst($r['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-admin-outline btn-admin-sm" data-bs-toggle="modal" data-bs-target="#roomModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-admin-outline btn-admin-sm" data-admin-delete><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="roomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add / Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-admin-form data-success-msg="Room saved successfully">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="admin-form-label">Hotel</label>
                        <select class="form-select admin-input" required>
                            <option>Grand Luxe Resort</option>
                            <option>Azure Palm Dubai</option>
                            <option>Ocean Pearl Maldives</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="admin-form-label">Room Type</label>
                        <input type="text" class="form-control admin-input" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="admin-form-label">Price per Night ($)</label>
                            <input type="number" class="form-control admin-input" required min="1">
                        </div>
                        <div class="col-6">
                            <label class="admin-form-label">Capacity</label>
                            <input type="number" class="form-control admin-input" required min="1" max="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-admin-primary">Save Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/components/layout-end.php';
require_once __DIR__ . '/includes/footer.php';
