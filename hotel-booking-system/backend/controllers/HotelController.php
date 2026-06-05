<?php

class HotelController
{
    public static function index(): void
    {
        $pdo = Database::connection();
        $sql = 'SELECT * FROM hotels WHERE status = "active"';
        $params = [];

        $q = Helpers::query('q');
        $destination = Helpers::query('destination');
        $stars = Helpers::query('stars');
        $maxPrice = Helpers::query('maxPrice');

        if ($q) {
            $sql .= ' AND (name LIKE ? OR location LIKE ?)';
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        if ($destination) {
            $sql .= ' AND destination = ?';
            $params[] = $destination;
        }
        if ($stars) {
            $sql .= ' AND stars >= ?';
            $params[] = (int) $stars;
        }
        if ($maxPrice) {
            $sql .= ' AND price_from <= ?';
            $params[] = (float) $maxPrice;
        }

        $sort = Helpers::query('sort', 'recommended');
        switch ($sort) {
            case 'price-low': $sql .= ' ORDER BY price_from ASC'; break;
            case 'price-high': $sql .= ' ORDER BY price_from DESC'; break;
            case 'rating': $sql .= ' ORDER BY rating DESC'; break;
            default: $sql .= ' ORDER BY rating DESC, reviews_count DESC';
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $hotels = array_map(fn($r) => Helpers::formatHotel($r), $stmt->fetchAll());

        Response::success(['count' => count($hotels), 'hotels' => $hotels]);
    }

    public static function adminIndex(): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->query('SELECT h.*, (SELECT COUNT(*) FROM rooms r WHERE r.hotel_id = h.id) AS room_count FROM hotels h ORDER BY h.id');
        $hotels = $stmt->fetchAll();
        Response::success(['hotels' => $hotels]);
    }

    public static function show(array $params): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM hotels WHERE id = ?');
        $stmt->execute([(int) $params['id']]);
        $row = $stmt->fetch();
        if (!$row) {
            Response::error('Hotel not found', 404);
        }
        Response::success(['hotel' => Helpers::formatHotel($row, true)]);
    }

    public static function store(): void
    {
        $data = Helpers::input();
        $v = new Validator();
        $v->required($data, ['name', 'location', 'price_from']);
        if ($v->fails()) {
            Response::error('Validation failed', 422, $v->errors());
        }

        $pdo = Database::connection();
        $pdo->prepare(
            'INSERT INTO hotels (name, location, address, lat, lng, destination, image, description, price_from, stars, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        )->execute([
            $data['name'],
            $data['location'],
            $data['address'] ?? null,
            $data['lat'] ?? null,
            $data['lng'] ?? null,
            $data['destination'] ?? null,
            $data['image'] ?? null,
            $data['description'] ?? null,
            $data['price_from'],
            $data['stars'] ?? 5,
            $data['status'] ?? 'active',
        ]);

        $id = (int) $pdo->lastInsertId();
        self::syncAmenities($id, $data['amenities'] ?? []);
        Response::success(['id' => $id], 'Hotel created', 201);
    }

    public static function update(array $params): void
    {
        $id = (int) $params['id'];
        $data = Helpers::input();
        $pdo = Database::connection();

        $pdo->prepare(
            'UPDATE hotels SET name=?, location=?, address=?, lat=?, lng=?, destination=?, image=?, description=?, price_from=?, stars=?, status=? WHERE id=?'
        )->execute([
            $data['name'] ?? '',
            $data['location'] ?? '',
            $data['address'] ?? null,
            $data['lat'] ?? null,
            $data['lng'] ?? null,
            $data['destination'] ?? null,
            $data['image'] ?? null,
            $data['description'] ?? null,
            $data['price_from'] ?? 0,
            $data['stars'] ?? 5,
            $data['status'] ?? 'active',
            $id,
        ]);

        if (isset($data['amenities'])) {
            self::syncAmenities($id, $data['amenities']);
        }
        Response::success(['id' => $id], 'Hotel updated');
    }

    public static function destroy(array $params): void
    {
        $pdo = Database::connection();
        $pdo->prepare('DELETE FROM hotels WHERE id = ?')->execute([(int) $params['id']]);
        Response::success(null, 'Hotel deleted');
    }

    private static function syncAmenities(int $hotelId, array $amenities): void
    {
        $pdo = Database::connection();
        $pdo->prepare('DELETE FROM hotel_amenities WHERE hotel_id = ?')->execute([$hotelId]);
        $stmt = $pdo->prepare('INSERT INTO hotel_amenities (hotel_id, amenity) VALUES (?, ?)');
        foreach ($amenities as $a) {
            if ($a) {
                $stmt->execute([$hotelId, $a]);
            }
        }
    }
}
