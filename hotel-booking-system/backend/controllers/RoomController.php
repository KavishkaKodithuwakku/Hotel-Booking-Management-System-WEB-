<?php

class RoomController
{
    public static function index(): void
    {
        $pdo = Database::connection();
        $hotelId = Helpers::query('hotel_id');
        $sql = 'SELECT r.*, h.name AS hotel_name FROM rooms r JOIN hotels h ON h.id = r.hotel_id WHERE 1=1';
        $params = [];
        if ($hotelId) {
            $sql .= ' AND r.hotel_id = ?';
            $params[] = (int) $hotelId;
        }
        $sql .= ' ORDER BY r.id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        Response::success(['rooms' => $stmt->fetchAll()]);
    }

    public static function store(): void
    {
        $data = Helpers::input();
        $pdo = Database::connection();
        $pdo->prepare(
            'INSERT INTO rooms (hotel_id, room_code, name, room_type, size_sqm, beds, price_per_night, capacity, image, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        )->execute([
            (int) $data['hotel_id'],
            $data['room_code'] ?? null,
            $data['name'],
            $data['room_type'] ?? null,
            $data['size_sqm'] ?? null,
            $data['beds'] ?? null,
            $data['price_per_night'],
            $data['capacity'] ?? 2,
            $data['image'] ?? null,
            $data['status'] ?? 'available',
        ]);
        Response::success(['id' => (int) $pdo->lastInsertId()], 'Room created', 201);
    }

    public static function update(array $params): void
    {
        $data = Helpers::input();
        $pdo = Database::connection();
        $pdo->prepare(
            'UPDATE rooms SET hotel_id=?, room_code=?, name=?, room_type=?, size_sqm=?, beds=?, price_per_night=?, capacity=?, image=?, status=? WHERE id=?'
        )->execute([
            (int) ($data['hotel_id'] ?? 0),
            $data['room_code'] ?? null,
            $data['name'] ?? '',
            $data['room_type'] ?? null,
            $data['size_sqm'] ?? null,
            $data['beds'] ?? null,
            $data['price_per_night'] ?? 0,
            $data['capacity'] ?? 2,
            $data['image'] ?? null,
            $data['status'] ?? 'available',
            (int) $params['id'],
        ]);
        Response::success(null, 'Room updated');
    }

    public static function destroy(array $params): void
    {
        Database::connection()->prepare('DELETE FROM rooms WHERE id = ?')->execute([(int) $params['id']]);
        Response::success(null, 'Room deleted');
    }
}
