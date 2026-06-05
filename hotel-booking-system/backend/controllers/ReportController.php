<?php

class ReportController
{
    public static function index(): void
    {
        $pdo = Database::connection();
        $reports = [
            ['name' => 'May 2026 Revenue', 'type' => 'Revenue', 'period' => 'May 1 – May 31', 'generated' => date('Y-m-d')],
            ['name' => 'Q1 Booking Summary', 'type' => 'Bookings', 'period' => 'Jan – Mar 2026', 'generated' => '2026-04-02'],
        ];
        Response::success(['reports' => $reports]);
    }

    public static function generate(): void
    {
        $data = Helpers::input();
        $type = $data['report_type'] ?? $data['type'] ?? 'bookings';
        $pdo = Database::connection();

        switch ($type) {
            case 'revenue':
                $rows = $pdo->query(
                    'SELECT p.transaction_id, b.booking_ref, p.amount, p.status, p.paid_at
                     FROM payments p JOIN bookings b ON b.id = p.booking_id WHERE p.status = "paid"'
                )->fetchAll();
                break;
            case 'occupancy':
                $rows = $pdo->query(
                    'SELECT h.name, COUNT(r.id) AS rooms,
                            SUM(CASE WHEN r.status = "occupied" THEN 1 ELSE 0 END) AS occupied
                     FROM hotels h LEFT JOIN rooms r ON r.hotel_id = h.id GROUP BY h.id'
                )->fetchAll();
                break;
            default:
                $rows = $pdo->query(
                    'SELECT b.booking_ref, h.name AS hotel, b.check_in, b.check_out, b.total_amount, b.status
                     FROM bookings b JOIN hotels h ON h.id = b.hotel_id'
                )->fetchAll();
        }

        Response::success([
            'report_type' => $type,
            'generated_at' => date('c'),
            'row_count' => count($rows),
            'data' => $rows,
            'download_url' => null,
            'message' => 'Report generated successfully',
        ]);
    }
}
