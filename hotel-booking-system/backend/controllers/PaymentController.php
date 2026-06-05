<?php

class PaymentController
{
    public static function index(): void
    {
        $pdo  = Database::connection();
        $stmt = $pdo->query(
            'SELECT p.*, b.booking_ref, b.check_in, b.check_out,
                    u.first_name, u.last_name, u.email,
                    h.name AS hotel_name
             FROM payments p
             JOIN bookings b ON b.id = p.booking_id
             JOIN users    u ON u.id = p.user_id
             JOIN hotels   h ON h.id = b.hotel_id
             ORDER BY p.created_at DESC'
        );
        $payments = array_map([self::class, 'format'], $stmt->fetchAll());

        $stats = $pdo->query(
            'SELECT
               SUM(CASE WHEN status = "paid"     THEN amount ELSE 0 END) AS completed,
               SUM(CASE WHEN status = "pending"  THEN amount ELSE 0 END) AS pending,
               SUM(CASE WHEN status = "refunded" THEN amount ELSE 0 END) AS refunded,
               COUNT(*) AS total_count
             FROM payments'
        )->fetch();

        Response::success(['payments' => $payments, 'stats' => $stats]);
    }

    /** PUT /admin/payments/{id} — admin updates payment status */
    public static function update(array $params): void
    {
        $data   = Helpers::input();
        $pdo    = Database::connection();
        $id     = (int) $params['id'];
        $status = $data['status'] ?? 'paid';
        $paidAt = $status === 'paid' ? date('Y-m-d H:i:s') : null;

        $pdo->prepare('UPDATE payments SET status = ?, paid_at = ? WHERE id = ?')
            ->execute([$status, $paidAt, $id]);

        // Sync booking status
        $stmt = $pdo->prepare('SELECT * FROM payments WHERE id = ?');
        $stmt->execute([$id]);
        $pay = $stmt->fetch();
        if ($pay) {
            $bookingStatus = match ($status) {
                'paid'     => 'confirmed',
                'refunded' => 'cancelled',
                default    => null,
            };
            if ($bookingStatus) {
                $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?')
                    ->execute([$bookingStatus, $pay['booking_id']]);

                // Notify the customer
                $bStmt = $pdo->prepare('SELECT * FROM bookings WHERE id = ?');
                $bStmt->execute([$pay['booking_id']]);
                $bk = $bStmt->fetch();
                if ($bk) {
                    $msg = $status === 'paid'
                        ? "Your payment for booking {$bk['booking_ref']} was successful. Booking confirmed!"
                        : "Your payment for booking {$bk['booking_ref']} has been refunded.";
                    $pdo->prepare(
                        'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
                         VALUES (?, "specific_user", "in_app", "Payment Update", ?, "sent", NOW())'
                    )->execute([$bk['user_id'], $msg]);
                }
            }
        }

        ActivityLog::record('payment_updated', 'payment', $id, "Status set to $status");
        Response::success(null, 'Payment updated');
    }

    /** POST /payments/process — customer completes payment */
    public static function process(): void
    {
        $user = Auth::requireUser();
        $data = Helpers::input();
        $pdo  = Database::connection();

        $bookingId = (int) ($data['booking_id'] ?? 0);

        $stmt = $pdo->prepare('SELECT * FROM payments WHERE booking_id = ? AND user_id = ?');
        $stmt->execute([$bookingId, $user['id']]);
        $payment = $stmt->fetch();
        if (!$payment) {
            Response::error('Payment record not found', 404);
        }

        $card4 = isset($data['card_number'])
            ? substr(preg_replace('/\D/', '', $data['card_number']), -4)
            : ($payment['card_last4'] ?? '0000');

        $pdo->prepare(
            'UPDATE payments SET status = "paid", paid_at = NOW(), card_last4 = ?,
             payment_method = COALESCE(?, payment_method)
             WHERE id = ?'
        )->execute([$card4, $data['payment_method'] ?? null, $payment['id']]);

        $pdo->prepare('UPDATE bookings SET status = "confirmed" WHERE id = ?')->execute([$bookingId]);

        // Admin notification
        $bStmt = $pdo->prepare('SELECT booking_ref FROM bookings WHERE id = ?');
        $bStmt->execute([$bookingId]);
        $bk = $bStmt->fetch();
        $ref = $bk['booking_ref'] ?? "#{$bookingId}";

        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (NULL, "admin", "in_app", "Payment Received", ?, "sent", NOW())'
        )->execute(["Payment received for booking $ref. Amount: \${$payment['amount']}"]);

        // Customer notification
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (?, "specific_user", "in_app", "Payment Successful", ?, "sent", NOW())'
        )->execute([
            $user['id'],
            "Payment successful for booking $ref. Your booking is confirmed!",
        ]);

        ActivityLog::record('payment_processed', 'payment', (int) $payment['id'],
            "Booking $ref, \${$payment['amount']}");

        Response::success(['transaction_id' => $payment['transaction_id']], 'Payment successful');
    }

    public static function format(array $p): array
    {
        return [
            'id'             => (int) $p['id'],
            'transaction_id' => $p['transaction_id'],
            'booking_ref'    => $p['booking_ref'] ?? '',
            'customer'       => ($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? ''),
            'email'          => $p['email'] ?? '',
            'hotel_name'     => $p['hotel_name'] ?? '',
            'amount'         => (float) $p['amount'],
            'payment_method' => $p['payment_method'] ?? '',
            'card_last4'     => $p['card_last4'] ?? '',
            'status'         => $p['status'],
            'paid_at'        => $p['paid_at'] ?? null,
            'created_at'     => $p['created_at'],
        ];
    }
}
