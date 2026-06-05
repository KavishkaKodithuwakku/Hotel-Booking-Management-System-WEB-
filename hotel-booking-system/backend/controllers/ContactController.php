<?php

class ContactController
{
    /** POST /contact — public contact form */
    public static function store(): void
    {
        $data = Helpers::input();
        $v    = new Validator();
        $v->required($data, ['name', 'email', 'message']);
        $v->email($data['email'] ?? '');
        if ($v->fails()) {
            Response::error('Validation failed', 422, $v->errors());
        }

        $pdo = Database::connection();
        $pdo->prepare(
            'INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)'
        )->execute([
            $data['name'],
            $data['email'],
            $data['subject'] ?? 'General Inquiry',
            $data['message'],
        ]);
        $msgId = (int) $pdo->lastInsertId();

        // Admin in-app notification
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (NULL, "admin", "in_app", "New support message", ?, "sent", NOW())'
        )->execute(["New contact from {$data['name']} <{$data['email']}>: {$data['subject']}"]);

        ActivityLog::record('contact_message_sent', 'contact_message', $msgId,
            "From: {$data['email']}");

        Response::success(null, 'Message sent successfully');
    }

    /** GET /admin/support — list all messages (admin) */
    public static function adminIndex(): void
    {
        $pdo    = Database::connection();
        $status = Helpers::query('status');
        $sql    = 'SELECT * FROM contact_messages';
        $params = [];
        if ($status) {
            $sql    .= ' WHERE status = ?';
            $params[] = $status;
        }
        $sql .= ' ORDER BY created_at DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $counts = $pdo->query(
            'SELECT status, COUNT(*) AS cnt FROM contact_messages GROUP BY status'
        )->fetchAll();
        $summary = ['new' => 0, 'read' => 0, 'replied' => 0];
        foreach ($counts as $c) {
            $summary[$c['status']] = (int) $c['cnt'];
        }

        Response::success(['messages' => $stmt->fetchAll(), 'summary' => $summary]);
    }

    /** PUT /admin/support/{id} — mark read / send reply */
    public static function adminReply(array $params): void
    {
        $data  = Helpers::input();
        $admin = Auth::requireAdmin();
        $pdo   = Database::connection();
        $id    = (int) $params['id'];

        $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id = ?');
        $stmt->execute([$id]);
        $msg = $stmt->fetch();
        if (!$msg) {
            Response::error('Message not found', 404);
        }

        $newStatus = $data['status'] ?? ($data['reply_message'] ? 'replied' : 'read');
        $pdo->prepare(
            'UPDATE contact_messages
             SET status = ?, reply_message = COALESCE(?, reply_message),
                 replied_by = ?, replied_at = IF(? IS NOT NULL, NOW(), replied_at)
             WHERE id = ?'
        )->execute([
            $newStatus,
            $data['reply_message'] ?? null,
            $admin['id'],
            $data['reply_message'] ?? null,
            $id,
        ]);

        ActivityLog::record('support_reply', 'contact_message', $id,
            "Status: $newStatus", (int) $admin['id'], $admin['role']);

        Response::success(null, 'Message updated');
    }
}
