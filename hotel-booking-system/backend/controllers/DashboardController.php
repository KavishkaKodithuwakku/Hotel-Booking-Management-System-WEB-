<?php

class DashboardController
{
    public static function stats(): void
    {
        $pdo = Database::connection();

        $totalRevenue = (float) $pdo->query(
            'SELECT COALESCE(SUM(amount),0) FROM payments WHERE status = "paid"'
        )->fetchColumn();

        $lastMonthRevenue = (float) $pdo->query(
            'SELECT COALESCE(SUM(amount),0) FROM payments
             WHERE status = "paid" AND MONTH(paid_at) = MONTH(NOW())-1 AND YEAR(paid_at) = YEAR(NOW())'
        )->fetchColumn();

        $thisMonthRevenue = (float) $pdo->query(
            'SELECT COALESCE(SUM(amount),0) FROM payments
             WHERE status = "paid" AND MONTH(paid_at) = MONTH(NOW()) AND YEAR(paid_at) = YEAR(NOW())'
        )->fetchColumn();

        $revenueChange = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $totalBookings     = (int) $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
        $pendingBookings   = (int) $pdo->query('SELECT COUNT(*) FROM bookings WHERE status = "pending"')->fetchColumn();
        $confirmedBookings = (int) $pdo->query('SELECT COUNT(*) FROM bookings WHERE status = "confirmed"')->fetchColumn();

        $activeHotels  = (int) $pdo->query('SELECT COUNT(*) FROM hotels WHERE status = "active"')->fetchColumn();
        $totalUsers    = (int) $pdo->query('SELECT COUNT(*) FROM users WHERE role = "customer"')->fetchColumn();
        $newUsersToday = (int) $pdo->query('SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()')->fetchColumn();

        $availableRooms = (int) $pdo->query('SELECT COUNT(*) FROM rooms WHERE status = "available"')->fetchColumn();
        $occupiedRooms  = (int) $pdo->query('SELECT COUNT(*) FROM rooms WHERE status = "occupied"')->fetchColumn();
        $totalRooms     = $availableRooms + $occupiedRooms;
        $occupancyRate  = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $pendingReviews  = (int) $pdo->query('SELECT COUNT(*) FROM reviews WHERE status = "pending"')->fetchColumn();
        $newMessages     = (int) $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE status = "new"')->fetchColumn();
        $pendingPayments = (int) $pdo->query('SELECT COUNT(*) FROM payments WHERE status = "pending"')->fetchColumn();

        $recent = $pdo->query(
            'SELECT b.booking_ref, b.check_in, b.check_out, b.total_amount, b.status, b.created_at,
                    CONCAT(u.first_name," ",u.last_name) AS guest, h.name AS hotel
             FROM bookings b
             JOIN users  u ON u.id = b.user_id
             JOIN hotels h ON h.id = b.hotel_id
             ORDER BY b.created_at DESC LIMIT 8'
        )->fetchAll();

        $revenueChart = $pdo->query(
            'SELECT DATE_FORMAT(paid_at, "%b %Y") AS month,
                    SUM(amount) AS revenue,
                    COUNT(*) AS transactions
             FROM payments
             WHERE status = "paid" AND paid_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY YEAR(paid_at), MONTH(paid_at)
             ORDER BY paid_at'
        )->fetchAll();

        $bookingsChart = $pdo->query(
            'SELECT DAYNAME(created_at) AS day, DATE(created_at) AS date, COUNT(*) AS count
             FROM bookings
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(created_at), DAYNAME(created_at)
             ORDER BY DATE(created_at)'
        )->fetchAll();

        $topHotels = $pdo->query(
            'SELECT h.name, COUNT(b.id) AS bookings,
                    COALESCE(SUM(p.amount),0) AS revenue
             FROM hotels h
             LEFT JOIN bookings b ON b.hotel_id = h.id
             LEFT JOIN payments p ON p.booking_id = b.id AND p.status = "paid"
             GROUP BY h.id ORDER BY revenue DESC LIMIT 5'
        )->fetchAll();

        Response::success([
            'stats' => [
                'totalRevenue'     => $totalRevenue,
                'thisMonthRevenue' => $thisMonthRevenue,
                'revenueChange'    => $revenueChange,
                'totalBookings'    => $totalBookings,
                'pendingBookings'  => $pendingBookings,
                'confirmedBookings'=> $confirmedBookings,
                'activeHotels'     => $activeHotels,
                'totalUsers'       => $totalUsers,
                'newUsersToday'    => $newUsersToday,
                'availableRooms'   => $availableRooms,
                'occupiedRooms'    => $occupiedRooms,
                'occupancyRate'    => $occupancyRate,
                'pendingReviews'   => $pendingReviews,
                'newMessages'      => $newMessages,
                'pendingPayments'  => $pendingPayments,
            ],
            'recentBookings' => $recent,
            'revenueChart'   => $revenueChart,
            'bookingsChart'  => $bookingsChart,
            'topHotels'      => $topHotels,
        ]);
    }

    public static function revenue(): void
    {
        $pdo = Database::connection();

        $byHotel = $pdo->query(
            'SELECT h.name AS hotel, COUNT(b.id) AS bookings,
                    COALESCE(SUM(p.amount),0) AS revenue
             FROM hotels h
             LEFT JOIN bookings b ON b.hotel_id = h.id
             LEFT JOIN payments p ON p.booking_id = b.id AND p.status = "paid"
             GROUP BY h.id ORDER BY revenue DESC'
        )->fetchAll();

        $monthly = (float) $pdo->query(
            'SELECT COALESCE(SUM(amount),0) FROM payments
             WHERE status="paid" AND MONTH(paid_at)=MONTH(NOW()) AND YEAR(paid_at)=YEAR(NOW())'
        )->fetchColumn();

        $lastMonth = (float) $pdo->query(
            'SELECT COALESCE(SUM(amount),0) FROM payments
             WHERE status="paid" AND MONTH(paid_at)=MONTH(NOW())-1 AND YEAR(paid_at)=YEAR(NOW())'
        )->fetchColumn();

        $yearly = (float) $pdo->query(
            'SELECT COALESCE(SUM(amount),0) FROM payments
             WHERE status="paid" AND YEAR(paid_at)=YEAR(NOW())'
        )->fetchColumn();

        $avgBookingValue = (float) $pdo->query(
            'SELECT COALESCE(AVG(total_amount),0) FROM bookings WHERE status != "cancelled"'
        )->fetchColumn();

        $trend = $pdo->query(
            'SELECT DATE_FORMAT(paid_at, "%b") AS month, SUM(amount) AS revenue
             FROM payments WHERE status="paid" AND paid_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY YEAR(paid_at), MONTH(paid_at) ORDER BY paid_at'
        )->fetchAll();

        Response::success([
            'thisMonth'      => $monthly,
            'lastMonth'      => $lastMonth,
            'yearToDate'     => $yearly,
            'avgBookingValue'=> round($avgBookingValue, 2),
            'byHotel'        => $byHotel,
            'trend'          => $trend,
        ]);
    }

    public static function activityLog(): void
    {
        $pdo  = Database::connection();
        $stmt = $pdo->query(
            'SELECT al.*, CONCAT(u.first_name," ",u.last_name) AS actor_name
             FROM activity_log al
             LEFT JOIN users u ON u.id = al.actor_id
             ORDER BY al.created_at DESC LIMIT 100'
        );
        Response::success(['log' => $stmt->fetchAll()]);
    }
}
