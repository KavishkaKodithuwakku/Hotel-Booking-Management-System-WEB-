<?php

class UserController
{
    public static function index(): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->query(
            'SELECT id, first_name, last_name, email, phone, role, status, created_at FROM users ORDER BY id'
        );
        Response::success(['users' => $stmt->fetchAll()]);
    }

    public static function store(): void
    {
        $data = Helpers::input();
        $pdo = Database::connection();
        $pdo->prepare(
            'INSERT INTO users (first_name, last_name, email, password, phone, role, status) VALUES (?, ?, ?, ?, ?, ?, "active")'
        )->execute([
            $data['first_name'] ?? $data['firstName'] ?? '',
            $data['last_name'] ?? $data['lastName'] ?? '',
            $data['email'],
            password_hash($data['password'] ?? 'user123', PASSWORD_DEFAULT),
            $data['phone'] ?? null,
            $data['role'] ?? 'customer',
        ]);
        Response::success(['id' => (int) $pdo->lastInsertId()], 'User created', 201);
    }

    public static function update(array $params): void
    {
        $data = Helpers::input();
        $pdo  = Database::connection();
        $id   = (int) $params['id'];

        // Accept both snake_case and camelCase keys
        $map = [
            'first_name'  => $data['first_name']  ?? $data['firstName']  ?? null,
            'last_name'   => $data['last_name']   ?? $data['lastName']   ?? null,
            'email'       => $data['email']       ?? null,
            'phone'       => $data['phone']       ?? null,
            'role'        => $data['role']        ?? null,
            'status'      => $data['status']      ?? null,
            'loyalty_tier'=> $data['loyalty_tier'] ?? null,
        ];

        $fields = [];
        $values = [];
        foreach ($map as $col => $val) {
            if ($val !== null) {
                $fields[] = "$col = ?";
                $values[] = $val;
            }
        }
        if (isset($data['password']) && $data['password']) {
            $fields[] = 'password = ?';
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if ($fields) {
            $values[] = $id;
            $pdo->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($values);
        }

        ActivityLog::record('user_updated', 'user', $id);
        Response::success(null, 'User updated');
    }

    public static function destroy(array $params): void
    {
        Database::connection()->prepare('UPDATE users SET status = "inactive" WHERE id = ?')
            ->execute([(int) $params['id']]);
        Response::success(null, 'User deactivated');
    }

    public static function updateProfile(): void
    {
        $user = Auth::requireUser();
        $data = Helpers::input();
        $pdo = Database::connection();
        $pdo->prepare(
            'UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?'
        )->execute([
            $data['firstName'] ?? $data['first_name'] ?? $user['first_name'],
            $data['lastName'] ?? $data['last_name'] ?? $user['last_name'],
            $data['phone'] ?? $user['phone'],
            $user['id'],
        ]);
        Response::success(null, 'Profile updated');
    }
}
