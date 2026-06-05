<?php

class CustomerController
{
    public static function index(): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->query(
            'SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.loyalty_tier, u.created_at,
                    COUNT(b.id) AS total_bookings,
                    COALESCE(SUM(CASE WHEN p.status = "paid" THEN p.amount ELSE 0 END), 0) AS lifetime_value
             FROM users u
             LEFT JOIN bookings b ON b.user_id = u.id
             LEFT JOIN payments p ON p.booking_id = b.id
             WHERE u.role = "customer"
             GROUP BY u.id ORDER BY lifetime_value DESC'
        );
        $customers = array_map(function ($c) {
            return [
                'id' => (int) $c['id'],
                'customer_id' => 'CUS-' . str_pad($c['id'], 3, '0', STR_PAD_LEFT),
                'name' => $c['first_name'] . ' ' . $c['last_name'],
                'email' => $c['email'],
                'phone' => $c['phone'],
                'loyalty_tier' => $c['loyalty_tier'],
                'total_bookings' => (int) $c['total_bookings'],
                'lifetime_value' => (float) $c['lifetime_value'],
                'joined' => $c['created_at'],
            ];
        }, $stmt->fetchAll());
        Response::success(['customers' => $customers]);
    }

    public static function show(array $params): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT * FROM users WHERE id = ? AND role = "customer"'
        );
        $stmt->execute([(int) $params['id']]);
        $user = $stmt->fetch();
        if (!$user) {
            Response::error('Customer not found', 404);
        }
        unset($user['password']);

        $bookings = $pdo->prepare(
            'SELECT b.*, h.name AS hotel_name FROM bookings b JOIN hotels h ON h.id = b.hotel_id WHERE b.user_id = ?'
        );
        $bookings->execute([(int) $params['id']]);

        Response::success([
            'customer' => $user,
            'bookings' => $bookings->fetchAll(),
        ]);
    }

    public static function update(array $params): void
    {
        $data = Helpers::input();
        $pdo = Database::connection();
        $pdo->prepare(
            'UPDATE users SET first_name=?, last_name=?, email=?, phone=?, loyalty_tier=? WHERE id=? AND role="customer"'
        )->execute([
            $data['first_name'] ?? $data['firstName'] ?? '',
            $data['last_name'] ?? $data['lastName'] ?? '',
            $data['email'] ?? '',
            $data['phone'] ?? '',
            $data['loyalty_tier'] ?? $data['loyaltyTier'] ?? 'Silver',
            (int) $params['id'],
        ]);
        Response::success(null, 'Customer updated');
    }
}
