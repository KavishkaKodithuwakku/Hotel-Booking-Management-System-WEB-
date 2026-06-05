<?php

class AuthController
{
    public static function register(): void
    {
        $data = Helpers::input();
        $v    = new Validator();
        $v->required($data, ['firstName', 'lastName', 'email', 'password']);
        $v->email($data['email'] ?? '');
        if ($v->fails()) {
            Response::error('Validation failed', 422, $v->errors());
        }

        $pdo   = Database::connection();
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$data['email']]);
        if ($check->fetch()) {
            Response::error('Email already registered', 409);
        }

        $pdo->prepare(
            'INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, "customer")'
        )->execute([
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['phone'] ?? null,
        ]);

        $userId = (int) $pdo->lastInsertId();

        // Welcome notification for the customer
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (?, "specific_user", "in_app", "Welcome to LuxeStay!", ?, "sent", NOW())'
        )->execute([
            $userId,
            "Welcome {$data['firstName']}! Your account has been created. Start exploring luxury hotels.",
        ]);

        // Admin notification about new registration
        $pdo->prepare(
            'INSERT INTO notifications (user_id, audience, channel, subject, message, status, sent_at)
             VALUES (NULL, "admin", "in_app", "New User Registered", ?, "sent", NOW())'
        )->execute(["{$data['firstName']} {$data['lastName']} ({$data['email']}) just registered."]);

        ActivityLog::record('user_registered', 'user', $userId, "Email: {$data['email']}",
            $userId, 'customer');

        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user  = $stmt->fetch();
        $token = Auth::createToken($userId);

        Response::success([
            'token'   => $token,
            'user'    => Auth::formatUser($user),
            'message' => 'Account created successfully',
        ], 'Registered', 201);
    }

    public static function login(): void
    {
        $data = Helpers::input();
        $v    = new Validator();
        $v->required($data, ['email', 'password']);
        if ($v->fails()) {
            Response::error('Validation failed', 422, $v->errors());
        }

        $user = Auth::attempt($data['email'], $data['password']);
        if (!$user) {
            Response::error('Invalid email or password', 401);
        }

        $token = Auth::createToken((int) $user['id']);
        ActivityLog::record('user_login', 'user', (int) $user['id'],
            "Login via user panel", (int) $user['id'], $user['role']);

        Response::success(['token' => $token, 'user' => Auth::formatUser($user)], 'Login successful');
    }

    public static function adminLogin(): void
    {
        $data = Helpers::input();
        $user = Auth::attempt($data['email'] ?? '', $data['password'] ?? '');
        if (!$user || !in_array($user['role'], ['admin', 'super_admin'], true)) {
            Response::error('Invalid admin credentials', 401);
        }

        $token = Auth::createToken((int) $user['id']);
        ActivityLog::record('admin_login', 'user', (int) $user['id'],
            "Admin login", (int) $user['id'], $user['role']);

        Response::success(['token' => $token, 'user' => Auth::formatUser($user)], 'Admin login successful');
    }

    public static function logout(): void
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::record('logout', 'user', (int) $user['id']);
        }
        Auth::revokeToken();
        Response::success(null, 'Logged out');
    }

    public static function me(): void
    {
        $user = Auth::requireUser();
        $pdo  = Database::connection();

        $totalBookings = (int) $pdo->prepare(
            'SELECT COUNT(*) FROM bookings WHERE user_id = ?'
        )->execute([$user['id']]) ? $pdo->query(
            'SELECT COUNT(*) FROM bookings WHERE user_id = ' . (int) $user['id']
        )->fetchColumn() : 0;

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE user_id = ?');
        $stmt->execute([$user['id']]);
        $totalBookings = (int) $stmt->fetchColumn();

        $rStmt = $pdo->prepare('SELECT COUNT(*) FROM reviews WHERE user_id = ?');
        $rStmt->execute([$user['id']]);
        $reviewCount = (int) $rStmt->fetchColumn();

        // Unread notifications
        $nStmt = $pdo->prepare(
            'SELECT COUNT(*) FROM notifications n
             WHERE (n.user_id = ? OR n.audience IN ("all","all_users"))
             AND n.id NOT IN (SELECT notification_id FROM notification_reads WHERE user_id = ?)'
        );
        $nStmt->execute([$user['id'], $user['id']]);
        $unreadNotifs = (int) $nStmt->fetchColumn();

        // Full user row for loyalty_tier
        $uStmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $uStmt->execute([$user['id']]);
        $fullUser = $uStmt->fetch();

        Response::success([
            'user' => array_merge(Auth::formatUser($user), [
                'loyaltyTier'         => $fullUser['loyalty_tier'] ?? 'Silver',
                'totalBookings'       => $totalBookings,
                'reviewCount'         => $reviewCount,
                'unreadNotifications' => $unreadNotifs,
            ]),
        ]);
    }
}
