<?php

class NotificationController
{
    public static function index(): void
    {
        $pdo  = Database::connection();
        $user = Auth::user();

        if ($user && !in_array($user['role'], ['admin', 'super_admin'], true)) {
            // Customer: their personal + broadcast notifications
            $stmt = $pdo->prepare(
                'SELECT n.*,
                        CASE WHEN nr.id IS NOT NULL THEN 1 ELSE 0 END AS is_read
                 FROM notifications n
                 LEFT JOIN notification_reads nr ON nr.notification_id = n.id AND nr.user_id = ?
                 WHERE (n.user_id = ? OR n.audience IN ("all","all_users"))
                   AND n.audience != "admin"
                 ORDER BY n.created_at DESC
                 LIMIT 50'
            );
            $stmt->execute([$user['id'], $user['id']]);
        } else {
            // Admin: admin-targeted + all broadcast
            $stmt = $pdo->query(
                'SELECT n.*, 0 AS is_read
                 FROM notifications n
                 WHERE n.audience IN ("admin","all","all_users") OR n.user_id IS NULL
                 ORDER BY n.created_at DESC LIMIT 100'
            );
        }

        Response::success(['notifications' => $stmt->fetchAll()]);
    }

    /** POST /notifications/read — mark a notification as read */
    public static function markRead(): void
    {
        $user = Auth::requireUser();
        $data = Helpers::input();
        $pdo  = Database::connection();

        $ids = (array) ($data['ids'] ?? []);
        if (empty($ids)) {
            Response::error('No notification IDs provided', 422);
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $params       = array_merge($ids, [$user['id']]);

        // Mark each as read
        $stmt = $pdo->prepare(
            "SELECT id FROM notifications WHERE id IN ($placeholders)"
        );
        $stmt->execute($ids);
        foreach ($stmt->fetchAll() as $n) {
            $pdo->prepare(
                'INSERT IGNORE INTO notification_reads (notification_id, user_id) VALUES (?, ?)'
            )->execute([$n['id'], $user['id']]);
        }

        Response::success(null, 'Marked as read');
    }

    /** Admin: send a notification */
    public static function store(): void
    {
        $data  = Helpers::input();
        $admin = Auth::requireAdmin();
        $pdo   = Database::connection();

        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (?, ?, ?, ?, ?, "sent", NOW())'
        )->execute([
            $data['user_id']  ?? null,
            $data['audience'] ?? 'all',
            $data['channel']  ?? 'in_app',
            $data['subject']  ?? '',
            $data['message']  ?? '',
        ]);

        ActivityLog::record('notification_sent', 'notification', (int) $pdo->lastInsertId(),
            "Subject: {$data['subject']}", (int) $admin['id'], $admin['role']);

        Response::success(['id' => (int) $pdo->lastInsertId()], 'Notification sent', 201);
    }
}
