# LuxeStay Backend API

PHP REST API with MySQL for the Hotel Booking Management System.

## Requirements

- PHP 8.0+
- MySQL 5.7+ / MariaDB (XAMPP)
- Apache with `mod_rewrite`

## Installation

1. Start **Apache** and **MySQL** in XAMPP.
2. Edit `config/database.php` if your MySQL credentials differ.
3. Open in browser:
   ```
   http://localhost/Hotel%20Booking%20Management%20system/hotel-booking-system/backend/install.php
   ```
4. Delete `install.php` after successful setup.

## Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@luxestay.com | admin123 |
| User | user@luxestay.com | user123 |

## API Base URL

```
http://localhost/Hotel%20Booking%20Management%20system/hotel-booking-system/backend/api/
```

## Authentication

Send JWT-like bearer token from login:

```
Authorization: Bearer <token>
```

## Endpoints Overview

### Public
- `GET /` — API health
- `POST /auth/register` — User registration
- `POST /auth/login` — User login
- `POST /auth/admin/login` — Admin login
- `GET /hotels` — List hotels (`?q=&destination=&stars=&maxPrice=&sort=`)
- `GET /hotels/{id}` — Hotel details + rooms + reviews
- `POST /availability/check` — Check room availability
- `POST /contact` — Contact form

### User (Bearer token)
- `GET /auth/me` — Current user
- `POST /auth/logout`
- `GET /bookings` — My bookings
- `POST /bookings` — Create booking
- `DELETE /bookings/{id}` — Cancel booking
- `POST /payments/process` — Complete payment
- `PUT /profile` — Update profile
- `POST /reviews` — Submit review

### Admin (Bearer token + admin role)
- `GET /admin/dashboard` — Stats & charts
- `GET /admin/revenue` — Revenue analytics
- `GET|POST|PUT|DELETE /admin/hotels` — Hotel CRUD
- `GET|POST|PUT|DELETE /admin/rooms` — Room CRUD
- `GET|PUT|DELETE /admin/bookings` — Booking management
- `GET|POST|PUT|DELETE /admin/users` — User management
- `GET|PUT /admin/payments` — Payments
- `GET|PUT /admin/customers` — Customers
- `GET|PUT /admin/availability` — Room availability
- `GET|POST /admin/hotels/{id}/images` — Image upload
- `GET|POST /admin/notifications` — Notifications
- `GET|POST /admin/reports/generate` — Reports

## Folder Structure

```
backend/
├── api/              # API entry (index.php)
├── config/           # database.php, app.php
├── controllers/      # API controllers
├── core/             # Database, Auth, Router, Response
├── database/         # schema.sql, seed.sql
├── uploads/hotels/   # Uploaded images
├── bootstrap.php
├── routes.php
└── install.php
```
