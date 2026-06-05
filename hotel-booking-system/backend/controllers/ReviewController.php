<?php

class ReviewController
{
    /** POST /reviews — customer submits a review (status=pending for admin to moderate) */
    public static function store(): void
    {
        $user = Auth::requireUser();
        $data = Helpers::input();

        $v = new Validator();
        $v->required($data, ['hotel_id', 'rating']);
        if ($v->fails()) {
            Response::error('Validation failed', 422, $v->errors());
        }

        $pdo = Database::connection();
        $pdo->prepare(
            'INSERT INTO reviews (hotel_id, user_id, rating, comment, status) VALUES (?, ?, ?, ?, "pending")'
        )->execute([
            (int) $data['hotel_id'],
            $user['id'],
            min(5, max(1, (int) ($data['rating'] ?? 5))),
            trim($data['comment'] ?? $data['review'] ?? ''),
        ]);

        $reviewId = (int) $pdo->lastInsertId();

        // Notify admin about new review
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (NULL, "admin", "in_app", "New review submitted", ?, "sent", NOW())'
        )->execute([
            "Customer #" . $user['id'] . " submitted a review for Hotel #" . $data['hotel_id'],
        ]);

        ActivityLog::record('review_submitted', 'review', $reviewId,
            'Customer submitted review for hotel #' . $data['hotel_id']);

        Response::success(['id' => $reviewId], 'Review submitted — pending approval', 201);
    }

    /** GET /admin/reviews — list all reviews with customer + hotel info */
    public static function adminIndex(): void
    {
        $pdo = Database::connection();
        $status = Helpers::query('status');

        $sql = 'SELECT r.*, h.name AS hotel_name, h.image AS hotel_image,
                       u.first_name, u.last_name, u.email AS user_email
                FROM reviews r
                JOIN hotels h ON h.id = r.hotel_id
                JOIN users u ON u.id = r.user_id';

        $params = [];
        if ($status) {
            $sql .= ' WHERE r.status = ?';
            $params[] = $status;
        }
        $sql .= ' ORDER BY r.created_at DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $reviews = array_map([self::class, 'format'], $stmt->fetchAll());

        $counts = $pdo->query(
            'SELECT status, COUNT(*) AS cnt FROM reviews GROUP BY status'
        )->fetchAll();
        $summary = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
        foreach ($counts as $c) {
            $summary[$c['status']] = (int) $c['cnt'];
        }

        Response::success(['reviews' => $reviews, 'summary' => $summary]);
    }

    /** PUT /admin/reviews/{id} — approve / reject / add reply */
    public static function adminUpdate(array $params): void
    {
        $data = Helpers::input();
        $pdo  = Database::connection();
        $id   = (int) $params['id'];

        $stmt = $pdo->prepare('SELECT * FROM reviews WHERE id = ?');
        $stmt->execute([$id]);
        $review = $stmt->fetch();
        if (!$review) {
            Response::error('Review not found', 404);
        }

        $newStatus = $data['status'] ?? $review['status'];
        $reply     = $data['admin_reply'] ?? null;

        $pdo->prepare(
            'UPDATE reviews SET status = ?,
             admin_reply = COALESCE(?, admin_reply),
             replied_at  = IF(? IS NOT NULL, NOW(), replied_at)
             WHERE id = ?'
        )->execute([$newStatus, $reply, $reply, $id]);

        // Recalculate hotel rating on approve/reject
        if (in_array($newStatus, ['approved', 'rejected'], true)) {
            $pdo->prepare(
                'UPDATE hotels SET
                 reviews_count = (SELECT COUNT(*) FROM reviews WHERE hotel_id = ? AND status = "approved"),
                 rating = COALESCE((SELECT AVG(rating) FROM reviews WHERE hotel_id = ? AND status = "approved"), 0)
                 WHERE id = ?'
            )->execute([$review['hotel_id'], $review['hotel_id'], $review['hotel_id']]);
        }

        // Notify the customer
        if ($newStatus === 'approved') {
            $pdo->prepare(
                'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
                 VALUES (?, "specific_user", "in_app", "Your review was approved", ?, "sent", NOW())'
            )->execute([
                $review['user_id'],
                'Your review has been approved and is now visible to guests.',
            ]);
        } elseif ($newStatus === 'rejected') {
            $pdo->prepare(
                'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
                 VALUES (?, "specific_user", "in_app", "Review update", ?, "sent", NOW())'
            )->execute([
                $review['user_id'],
                'Your review was not approved. Please ensure it follows our community guidelines.',
            ]);
        }

        ActivityLog::record('review_updated', 'review', $id,
            "Status set to $newStatus" . ($reply ? '; admin reply added' : ''));

        Response::success(null, 'Review updated');
    }

    /** DELETE /admin/reviews/{id} */
    public static function adminDestroy(array $params): void
    {
        $pdo = Database::connection();
        $id  = (int) $params['id'];

        $stmt = $pdo->prepare('SELECT hotel_id FROM reviews WHERE id = ?');
        $stmt->execute([$id]);
        $r = $stmt->fetch();

        $pdo->prepare('DELETE FROM reviews WHERE id = ?')->execute([$id]);

        if ($r) {
            $pdo->prepare(
                'UPDATE hotels SET
                 reviews_count = (SELECT COUNT(*) FROM reviews WHERE hotel_id = ? AND status = "approved"),
                 rating = COALESCE((SELECT AVG(rating) FROM reviews WHERE hotel_id = ? AND status = "approved"), 0)
                 WHERE id = ?'
            )->execute([$r['hotel_id'], $r['hotel_id'], $r['hotel_id']]);
        }

        ActivityLog::record('review_deleted', 'review', $id);
        Response::success(null, 'Review deleted');
    }

    public static function format(array $r): array
    {
        return [
            'id'          => (int) $r['id'],
            'hotel_id'    => (int) $r['hotel_id'],
            'hotel_name'  => $r['hotel_name'] ?? '',
            'hotel_image' => $r['hotel_image'] ?? '',
            'user_id'     => (int) $r['user_id'],
            'customer'    => ($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''),
            'email'       => $r['user_email'] ?? '',
            'rating'      => (int) $r['rating'],
            'comment'     => $r['comment'] ?? '',
            'status'      => $r['status'],
            'admin_reply' => $r['admin_reply'] ?? null,
            'replied_at'  => $r['replied_at'] ?? null,
            'created_at'  => $r['created_at'],
        ];
    }
}
