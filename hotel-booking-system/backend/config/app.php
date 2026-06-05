<?php
/**
 * Application configuration.
 */
return [
    'name' => 'LuxeStay API',
    'env' => 'local',
    'debug' => true,
    'timezone' => 'UTC',
    'jwt_secret' => 'luxestay-change-this-secret-in-production-2026',
    'token_ttl_hours' => 72,
    'upload_path' => __DIR__ . '/../uploads/hotels',
    'upload_url' => '/hotel-booking-system/backend/uploads/hotels',
    'cors_origins' => ['*'],
];
