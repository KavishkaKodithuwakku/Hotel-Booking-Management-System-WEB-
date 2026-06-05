<?php

class Auth
{
    private static ?array $user = null;

    public static function attempt(string $email, string $password): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM users WHERE email = ? AND status = "active" LIMIT 1'
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        unset($user['password']);
        return $user;
    }

    public static function createToken(int $userId): string
    {
        $app = require __DIR__ . '/../config/app.php';
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+' . $app['token_ttl_hours'] . ' hours'));

        $pdo = Database::connection();
        $pdo->prepare('DELETE FROM api_tokens WHERE user_id = ?')->execute([$userId]);
        $pdo->prepare(
            'INSERT INTO api_tokens (user_id, token, expires_at) VALUES (?, ?, ?)'
        )->execute([$userId, hash('sha256', $token), $expires]);

        return $token;
    }

    public static function user(): ?array
    {
        if (self::$user !== null) {
            return self::$user;
        }

        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/Bearer\s+(.+)/i', $header, $m)) {
            return null;
        }

        $tokenHash = hash('sha256', trim($m[1]));
        $stmt = Database::connection()->prepare(
            'SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.role, u.status, u.created_at
             FROM api_tokens t
             JOIN users u ON u.id = t.user_id
             WHERE t.token = ? AND t.expires_at > NOW() AND u.status = "active"
             LIMIT 1'
        );
        $stmt->execute([$tokenHash]);
        $user = $stmt->fetch() ?: null;
        self::$user = $user;
        return $user;
    }

    public static function requireUser(): array
    {
        $user = self::user();
        if (!$user) {
            Response::error('Unauthorized', 401);
        }
        return $user;
    }

    public static function requireAdmin(): array
    {
        $user = self::requireUser();
        if (!in_array($user['role'], ['admin', 'super_admin'], true)) {
            Response::error('Forbidden — admin access required', 403);
        }
        return $user;
    }

    public static function revokeToken(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.+)/i', $header, $m)) {
            $tokenHash = hash('sha256', trim($m[1]));
            Database::connection()->prepare('DELETE FROM api_tokens WHERE token = ?')
                ->execute([$tokenHash]);
        }
    }

    public static function formatUser(array $user): array
    {
        return [
            'id' => (int) $user['id'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'email' => $user['email'],
            'phone' => $user['phone'] ?? '',
            'role' => $user['role'],
        ];
    }
}
