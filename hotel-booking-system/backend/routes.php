<?php

$router = new Router();

// Health
$router->get('/', function () {
    Response::success([
        'api'     => 'LuxeStay Hotel Booking API',
        'version' => '2.0',
        'status'  => 'running',
    ]);
});

// ─── Public ───────────────────────────────────────────────
$router->post('/auth/register',       [AuthController::class, 'register']);
$router->post('/auth/login',          [AuthController::class, 'login']);
$router->post('/auth/admin/login',    [AuthController::class, 'adminLogin']);
$router->post('/auth/logout',         [AuthController::class, 'logout']);
$router->get('/auth/me',              [AuthController::class, 'me']);

$router->get('/hotels',               [HotelController::class, 'index']);
$router->get('/hotels/{id}',          [HotelController::class, 'show']);

$router->post('/availability/check',  [AvailabilityController::class, 'check']);
$router->get('/availability/check',   [AvailabilityController::class, 'check']);

$router->post('/contact',             [ContactController::class, 'store']);

// ─── Authenticated user ───────────────────────────────────
$router->get('/bookings', function () {
    Auth::requireUser();
    BookingController::index();
});
$router->post('/bookings', function () {
    Auth::requireUser();
    BookingController::store();
});
$router->delete('/bookings/{id}', function ($p) {
    Auth::requireUser();
    BookingController::destroy($p);
});

$router->post('/payments/process', function () {
    Auth::requireUser();
    PaymentController::process();
});

$router->put('/profile', function () {
    Auth::requireUser();
    UserController::updateProfile();
});

$router->post('/reviews', function () {
    Auth::requireUser();
    ReviewController::store();
});

$router->get('/notifications', function () {
    NotificationController::index();
});
$router->post('/notifications/read', function () {
    Auth::requireUser();
    NotificationController::markRead();
});

// ─── Admin ────────────────────────────────────────────────
// Dashboard
$router->get('/admin/dashboard',      [DashboardController::class, 'stats'],       true);
$router->get('/admin/revenue',        [DashboardController::class, 'revenue'],     true);
$router->get('/admin/activity-log',   [DashboardController::class, 'activityLog'], true);

// Hotels
$router->get('/admin/hotels',             [HotelController::class, 'adminIndex'], true);
$router->post('/admin/hotels',            [HotelController::class, 'store'],      true);
$router->put('/admin/hotels/{id}',        [HotelController::class, 'update'],     true);
$router->delete('/admin/hotels/{id}',     [HotelController::class, 'destroy'],    true);

// Rooms
$router->get('/admin/rooms',              [RoomController::class, 'index'],   true);
$router->post('/admin/rooms',             [RoomController::class, 'store'],   true);
$router->put('/admin/rooms/{id}',         [RoomController::class, 'update'],  true);
$router->delete('/admin/rooms/{id}',      [RoomController::class, 'destroy'], true);

// Bookings
$router->get('/admin/bookings',           [BookingController::class, 'index'],   true);
$router->put('/admin/bookings/{id}',      [BookingController::class, 'update'],  true);
$router->delete('/admin/bookings/{id}',   [BookingController::class, 'destroy'], true);

// Users
$router->get('/admin/users',              [UserController::class, 'index'],   true);
$router->post('/admin/users',             [UserController::class, 'store'],   true);
$router->put('/admin/users/{id}',         [UserController::class, 'update'],  true);
$router->delete('/admin/users/{id}',      [UserController::class, 'destroy'], true);

// Payments
$router->get('/admin/payments',           [PaymentController::class, 'index'],  true);
$router->put('/admin/payments/{id}',      [PaymentController::class, 'update'], true);

// Customers
$router->get('/admin/customers',          [CustomerController::class, 'index'],  true);
$router->get('/admin/customers/{id}',     [CustomerController::class, 'show'],   true);
$router->put('/admin/customers/{id}',     [CustomerController::class, 'update'], true);

// Availability
$router->get('/admin/availability',       [AvailabilityController::class, 'calendar'], true);
$router->put('/admin/availability',       [AvailabilityController::class, 'update'],   true);

// Images
$router->get('/admin/hotels/{id}/images',    [ImageController::class, 'index'],   true);
$router->post('/admin/hotels/{id}/images',   [ImageController::class, 'store'],   true);
$router->delete('/admin/images/{id}',        [ImageController::class, 'destroy'], true);

// Reviews (admin management)
$router->get('/admin/reviews',            [ReviewController::class, 'adminIndex'],   true);
$router->put('/admin/reviews/{id}',       [ReviewController::class, 'adminUpdate'],  true);
$router->delete('/admin/reviews/{id}',    [ReviewController::class, 'adminDestroy'], true);

// Support / Contact messages
$router->get('/admin/support',            [ContactController::class, 'adminIndex'], true);
$router->put('/admin/support/{id}',       [ContactController::class, 'adminReply'], true);

// Notifications
$router->get('/admin/notifications',      [NotificationController::class, 'index'], true);
$router->post('/admin/notifications',     [NotificationController::class, 'store'], true);

// Reports
$router->get('/admin/reports',            [ReportController::class, 'index'],    true);
$router->post('/admin/reports/generate',  [ReportController::class, 'generate'], true);

// ─── Dispatch ─────────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = $_SERVER['REQUEST_URI']    ?? '/';

try {
    $router->dispatch($method, $uri);
} catch (PDOException $e) {
    $app = require __DIR__ . '/config/app.php';
    Response::error($app['debug'] ? $e->getMessage() : 'Database error', 500);
} catch (Throwable $e) {
    $app = require __DIR__ . '/config/app.php';
    Response::error($app['debug'] ? $e->getMessage() : 'Internal server error', 500);
}
