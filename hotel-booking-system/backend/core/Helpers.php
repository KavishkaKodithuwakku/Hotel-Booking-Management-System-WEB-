<?php

class Helpers
{
    public static function input(): array
    {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        return is_array($json) ? $json : ($_POST ?: []);
    }

    public static function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public static function formatHotel(array $row, bool $detail = false): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT amenity FROM hotel_amenities WHERE hotel_id = ?');
        $stmt->execute([$row['id']]);
        $amenities = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $hotel = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'location' => $row['location'],
            'address' => $row['address'],
            'lat' => $row['lat'] !== null ? (float) $row['lat'] : null,
            'lng' => $row['lng'] !== null ? (float) $row['lng'] : null,
            'destination' => $row['destination'],
            'image' => $row['image'],
            'rating' => (float) $row['rating'],
            'reviews' => (int) $row['reviews_count'],
            'price' => (float) $row['price_from'],
            'stars' => (int) $row['stars'],
            'amenities' => $amenities,
            'status' => $row['status'],
        ];

        if ($detail) {
            $hotel['description'] = $row['description'];
            $rStmt = $pdo->prepare('SELECT * FROM rooms WHERE hotel_id = ? ORDER BY price_per_night');
            $rStmt->execute([$row['id']]);
            $rooms = [];
            while ($r = $rStmt->fetch()) {
                $aStmt = $pdo->prepare('SELECT amenity FROM room_amenities WHERE room_id = ?');
                $aStmt->execute([$r['id']]);
                $rooms[] = [
                    'id' => (int) $r['id'],
                    'name' => $r['name'],
                    'type' => $r['room_type'],
                    'size' => $r['size_sqm'],
                    'beds' => $r['beds'],
                    'price' => (float) $r['price_per_night'],
                    'capacity' => (int) $r['capacity'],
                    'image' => $r['image'],
                    'status' => $r['status'],
                    'amenities' => $aStmt->fetchAll(PDO::FETCH_COLUMN),
                ];
            }
            $hotel['rooms'] = $rooms;

            $revStmt = $pdo->prepare(
                'SELECT r.rating, r.comment, r.created_at, u.first_name, u.last_name
                 FROM reviews r JOIN users u ON u.id = r.user_id
                 WHERE r.hotel_id = ? AND r.status = "approved" ORDER BY r.created_at DESC LIMIT 10'
            );
            $revStmt->execute([$row['id']]);
            $hotel['reviewsList'] = array_map(function ($rev) {
                return [
                    'user' => $rev['first_name'] . ' ' . substr($rev['last_name'], 0, 1) . '.',
                    'date' => date('F Y', strtotime($rev['created_at'])),
                    'rating' => (int) $rev['rating'],
                    'text' => $rev['comment'],
                ];
            }, $revStmt->fetchAll());
        }

        return $hotel;
    }

    public static function bookingRef(): string
    {
        return 'BK-' . date('Y') . '-' . random_int(1000, 9999);
    }

    public static function transactionId(): string
    {
        return 'TXN-' . random_int(10000, 99999);
    }

    public static function nights(string $checkIn, string $checkOut): int
    {
        $a = new DateTime($checkIn);
        $b = new DateTime($checkOut);
        return max(1, (int) $a->diff($b)->days);
    }
}
