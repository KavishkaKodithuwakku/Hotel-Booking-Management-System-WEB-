<?php

class BookingController
{
    public static function index(): void
    {
        $pdo  = Database::connection();
        $user = Auth::user();
        $isAdmin = $user && in_array($user['role'], ['admin', 'super_admin'], true);

        if (!$isAdmin && !$user) {
            Response::error('Unauthorized', 401);
        }

        $sql = 'SELECT b.*, h.name AS hotel_name, h.image AS hotel_image,
                       r.name AS room_name,
                       u.first_name, u.last_name, u.email,
                       p.status AS payment_status, p.transaction_id
                FROM bookings b
                JOIN hotels h ON h.id = b.hotel_id
                JOIN rooms  r ON r.id = b.room_id
                JOIN users  u ON u.id = b.user_id
                LEFT JOIN payments p ON p.booking_id = b.id
                WHERE 1=1';
        $params = [];

        if (!$isAdmin) {
            $sql .= ' AND b.user_id = ?';
            $params[] = $user['id'];
        }

        $status  = Helpers::query('status');
        $hotelId = Helpers::query('hotel_id');
        if ($status)  { $sql .= ' AND b.status = ?';   $params[] = $status; }
        if ($hotelId) { $sql .= ' AND b.hotel_id = ?'; $params[] = (int) $hotelId; }

        $sql .= ' ORDER BY b.created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        Response::success(['bookings' => array_map([self::class, 'format'], $stmt->fetchAll())]);
    }

    public static function store(): void
    {
        $user = Auth::requireUser();
        $data = Helpers::input();

        $v = new Validator();
        $v->required($data, ['hotel_id', 'room_id', 'check_in', 'check_out']);
        if ($v->fails()) {
            Response::error('Validation failed', 422, $v->errors());
        }

        $pdo    = Database::connection();
        $roomStmt = $pdo->prepare('SELECT * FROM rooms WHERE id = ? AND hotel_id = ?');
        $roomStmt->execute([(int) $data['room_id'], (int) $data['hotel_id']]);
        $room = $roomStmt->fetch();
        if (!$room) {
            Response::error('Room not found', 404);
        }

        if (!self::isRoomAvailable((int) $data['room_id'], $data['check_in'], $data['check_out'])) {
            Response::error('Room not available for selected dates', 409);
        }

        $nights = Helpers::nights($data['check_in'], $data['check_out']);
        $total  = $nights * (float) $room['price_per_night'];
        $ref    = Helpers::bookingRef();

        $pdo->prepare(
            'INSERT INTO bookings
             (booking_ref, user_id, hotel_id, room_id, check_in, check_out, guests, total_amount, status, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, "pending", ?)'
        )->execute([
            $ref,
            $user['id'],
            (int) $data['hotel_id'],
            (int) $data['room_id'],
            $data['check_in'],
            $data['check_out'],
            (int) ($data['guests'] ?? 1),
            $total,
            $data['notes'] ?? null,
        ]);

        $bookingId = (int) $pdo->lastInsertId();
        self::blockDates((int) $data['room_id'], $data['check_in'], $data['check_out']);

        // Create pending payment record
        $txnId = Helpers::transactionId();
        $pdo->prepare(
            'INSERT INTO payments
             (transaction_id, booking_id, user_id, amount, payment_method, card_last4, status)
             VALUES (?, ?, ?, ?, ?, ?, "pending")'
        )->execute([
            $txnId,
            $bookingId,
            $user['id'],
            $total,
            $data['payment_method'] ?? 'card',
            isset($data['card_number']) ? substr(preg_replace('/\D/', '', $data['card_number']), -4) : null,
        ]);

        // Admin notification
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (NULL, "admin", "in_app", "New Booking Received", ?, "sent", NOW())'
        )->execute(["Booking $ref placed by {$user['email']} for hotel #{$data['hotel_id']}"]);

        // Customer confirmation notification
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (?, "specific_user", "in_app", "Booking Confirmed", ?, "sent", NOW())'
        )->execute([
            $user['id'],
            "Your booking $ref has been received. Check-in: {$data['check_in']}, Check-out: {$data['check_out']}. Total: \$$total",
        ]);

        ActivityLog::record('booking_created', 'booking', $bookingId,
            "Ref $ref, $nights nights, \$$total");

        Response::success([
            'booking' => [
                'id'          => $bookingId,
                'booking_ref' => $ref,
                'checkIn'     => $data['check_in'],
                'checkOut'    => $data['check_out'],
                'nights'      => $nights,
                'total'       => $total,
                'status'      => 'pending',
            ],
            'payment' => ['transaction_id' => $txnId],
        ], 'Booking created', 201);
    }

    public static function update(array $params): void
    {
        $data   = Helpers::input();
        $pdo    = Database::connection();
        $id     = (int) $params['id'];
        $status = $data['status'] ?? 'confirmed';

        $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = ?');
        $stmt->execute([$id]);
        $booking = $stmt->fetch();
        if (!$booking) {
            Response::error('Booking not found', 404);
        }

        $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?')->execute([$status, $id]);

        // Sync payment status
        if (in_array($status, ['confirmed', 'completed'], true)) {
            $pdo->prepare(
                'UPDATE payments SET status = "paid", paid_at = COALESCE(paid_at, NOW()) WHERE booking_id = ?'
            )->execute([$id]);
        } elseif ($status === 'cancelled') {
            $pdo->prepare(
                'DELETE FROM room_availability WHERE room_id = ? AND avail_date >= ? AND avail_date < ?'
            )->execute([$booking['room_id'], $booking['check_in'], $booking['check_out']]);
            $pdo->prepare('UPDATE payments SET status = "refunded" WHERE booking_id = ?')->execute([$id]);
        }

        // Customer notification on status change
        $messages = [
            'confirmed'  => "Your booking {$booking['booking_ref']} has been confirmed!",
            'cancelled'  => "Your booking {$booking['booking_ref']} has been cancelled by the hotel.",
            'completed'  => "We hope you enjoyed your stay! Your booking {$booking['booking_ref']} is now completed.",
        ];
        if (isset($messages[$status])) {
            $pdo->prepare(
                'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
                 VALUES (?, "specific_user", "in_app", "Booking Status Update", ?, "sent", NOW())'
            )->execute([$booking['user_id'], $messages[$status]]);
        }

        ActivityLog::record('booking_status_changed', 'booking', $id, "New status: $status");

        Response::success(null, 'Booking updated');
    }

    public static function destroy(array $params): void
    {
        $pdo     = Database::connection();
        $id      = (int) $params['id'];

        $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = ?');
        $stmt->execute([$id]);
        $booking = $stmt->fetch();
        if (!$booking) {
            Response::error('Booking not found', 404);
        }

        // Only owner or admin can cancel
        $user = Auth::user();
        if ($user && !in_array($user['role'], ['admin', 'super_admin'], true)
            && (int) $booking['user_id'] !== (int) $user['id']
        ) {
            Response::error('Forbidden', 403);
        }

        $pdo->prepare('UPDATE bookings SET status = "cancelled" WHERE id = ?')->execute([$id]);
        $pdo->prepare(
            'DELETE FROM room_availability WHERE room_id = ? AND avail_date >= ? AND avail_date < ?'
        )->execute([$booking['room_id'], $booking['check_in'], $booking['check_out']]);
        $pdo->prepare('UPDATE payments SET status = "refunded" WHERE booking_id = ?')->execute([$id]);

        // Notify admin
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (NULL, "admin", "in_app", "Booking Cancelled", ?, "sent", NOW())'
        )->execute(["Booking {$booking['booking_ref']} was cancelled."]);

        ActivityLog::record('booking_cancelled', 'booking', $id,
            "Ref: {$booking['booking_ref']}");

        Response::success(null, 'Booking cancelled');
    }

    public static function isRoomAvailable(int $roomId, string $checkIn, string $checkOut): bool
    {
        $stmt = Database::connection()->prepare(
            'SELECT COUNT(*) FROM room_availability
             WHERE room_id = ? AND avail_date >= ? AND avail_date < ?
             AND status IN ("booked","blocked","occupied")'
        );
        $stmt->execute([$roomId, $checkIn, $checkOut]);
        return (int) $stmt->fetchColumn() === 0;
    }

    private static function blockDates(int $roomId, string $checkIn, string $checkOut): void
    {
        $pdo   = Database::connection();
        $start = new DateTime($checkIn);
        $end   = new DateTime($checkOut);
        $stmt  = $pdo->prepare(
            'INSERT INTO room_availability (room_id, avail_date, status) VALUES (?, ?, "booked")
             ON DUPLICATE KEY UPDATE status = "booked"'
        );
        while ($start < $end) {
            $stmt->execute([$roomId, $start->format('Y-m-d')]);
            $start->modify('+1 day');
        }
    }

    public static function format(array $b): array
    {
        return [
            'id'             => (int) $b['id'],
            'booking_ref'    => $b['booking_ref'],
            'guest'          => trim(($b['first_name'] ?? '') . ' ' . ($b['last_name'] ?? '')),
            'email'          => $b['email'] ?? '',
            'hotel'          => $b['hotel_name'] ?? '',
            'hotel_image'    => $b['hotel_image'] ?? '',
            'room'           => $b['room_name'] ?? '',
            'check_in'       => $b['check_in'],
            'check_out'      => $b['check_out'],
            'guests'         => (int) $b['guests'],
            'total_amount'   => (float) $b['total_amount'],
            'status'         => $b['status'],
            'payment_status' => $b['payment_status'] ?? 'pending',
            'transaction_id' => $b['transaction_id'] ?? null,
            'notes'          => $b['notes'] ?? '',
            'created_at'     => $b['created_at'],
        ];
    }
}
