<?php

class AvailabilityController
{
    public static function check(): void
    {
        $data = Helpers::input();
        $roomId = (int) ($data['room_id'] ?? Helpers::query('room_id'));
        $hotelId = (int) ($data['hotel_id'] ?? Helpers::query('hotel_id'));
        $checkIn = $data['check_in'] ?? $data['checkIn'] ?? '';
        $checkOut = $data['check_out'] ?? $data['checkOut'] ?? '';

        if (!$checkIn || !$checkOut) {
            Response::error('Check-in and check-out dates required', 422);
        }

        $pdo = Database::connection();

        if ($roomId) {
            $available = BookingController::isRoomAvailable($roomId, $checkIn, $checkOut);
            $roomStmt = $pdo->prepare('SELECT name, price_per_night FROM rooms WHERE id = ?');
            $roomStmt->execute([$roomId]);
            $room = $roomStmt->fetch();
            $nights = Helpers::nights($checkIn, $checkOut);
            Response::success([
                'available' => $available,
                'roomsLeft' => $available ? 1 : 0,
                'message' => $available ? 'Rooms available for your selected dates' : 'Sorry, this room is not available.',
                'nights' => $nights,
                'total' => $available ? $nights * (float) ($room['price_per_night'] ?? 0) : 0,
                'pricePerNight' => (float) ($room['price_per_night'] ?? 0),
                'room' => $room['name'] ?? '',
            ]);
            return;
        }

        if ($hotelId) {
            $stmt = $pdo->prepare(
                'SELECT r.id, r.name, r.price_per_night FROM rooms r WHERE r.hotel_id = ? AND r.status = "available"'
            );
            $stmt->execute([$hotelId]);
            $rooms = $stmt->fetchAll();
            $availableRooms = [];
            foreach ($rooms as $room) {
                if (BookingController::isRoomAvailable((int) $room['id'], $checkIn, $checkOut)) {
                    $availableRooms[] = $room;
                }
            }
            Response::success([
                'available' => count($availableRooms) > 0,
                'rooms' => $availableRooms,
                'roomsLeft' => count($availableRooms),
                'message' => count($availableRooms) ? 'Rooms available for your selected dates' : 'No rooms available for these dates.',
                'pricePerNight' => count($availableRooms) ? (float) $availableRooms[0]['price_per_night'] : 0,
            ]);
            return;
        }

        Response::error('room_id or hotel_id required', 422);
    }

    public static function calendar(): void
    {
        $hotelId = (int) Helpers::query('hotel_id', 1);
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT r.id, r.room_code, r.name, ra.avail_date, ra.status
             FROM rooms r
             LEFT JOIN room_availability ra ON ra.room_id = r.id
             WHERE r.hotel_id = ?
             ORDER BY r.id, ra.avail_date'
        );
        $stmt->execute([$hotelId]);
        Response::success(['availability' => $stmt->fetchAll()]);
    }

    public static function update(): void
    {
        $data = Helpers::input();
        $pdo = Database::connection();
        $pdo->prepare(
            'INSERT INTO room_availability (room_id, avail_date, status) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE status = VALUES(status)'
        )->execute([
            (int) $data['room_id'],
            $data['avail_date'] ?? $data['from_date'],
            $data['status'] ?? 'blocked',
        ]);
        Response::success(null, 'Availability updated');
    }
}
