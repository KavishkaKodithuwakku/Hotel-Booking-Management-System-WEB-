# LuxeStay — Hotel Booking Management System

Full-stack hotel booking system with separated **frontend** (user + admin panels) and **backend** (PHP REST API + MySQL).

## Tech Stack

| Layer | Technologies |
|-------|----------------|
| Frontend | HTML5, PHP, Bootstrap 5, JavaScript, Leaflet Maps |
| Backend | PHP 8+, PDO, MySQL |
| Server | XAMPP (Apache + MySQL) |

## Project Structure

```
hotel-booking-system/
├── frontend/
│   ├── index.php           # Panel selector
│   ├── user-panel/         # Customer website
│   └── admin-panel/        # Admin console
├── backend/
│   ├── api/                # REST API entry point
│   ├── config/             # Database & app config
│   ├── controllers/        # API logic
│   ├── core/               # Router, Auth, Database
│   ├── database/           # schema.sql, seed.sql
│   ├── uploads/            # Hotel image uploads
│   └── install.php         # One-time DB installer
└── index.php               # Redirects to frontend
```

## Quick Start (XAMPP)

1. Start **Apache** and **MySQL** in XAMPP.
2. Open installer:
   ```
   http://localhost/Hotel%20Booking%20Management%20system/hotel-booking-system/backend/install.php
   ```
3. Open the app:
   ```
   http://localhost/Hotel%20Booking%20Management%20system/hotel-booking-system/
   ```

## Default Login

| Panel | Email | Password |
|-------|-------|------------|
| Admin | admin@luxestay.com | admin123 |
| User | user@luxestay.com | user123 |

## API Base URL

```
http://localhost/Hotel%20Booking%20Management%20system/hotel-booking-system/backend/api/
```

See [backend/README.md](backend/README.md) for full API documentation.

## Features

### User Panel
- Register / Login (API)
- Browse & search hotels
- **Interactive map** with hotel locations
- Hotel details, availability check, booking
- Profile & booking history
- Contact form

### Admin Panel
- Admin login (API)
- Dashboard with live stats
- Hotels, Rooms, Bookings, Users, Payments
- Customers, Availability, Images, Notifications
- Reports & Revenue tracking

### Backend API
- JWT-style bearer token authentication
- Role-based access (customer / admin)
- MySQL database with full schema
- RESTful endpoints for all modules

## Configuration

Edit `backend/config/database.php` for MySQL credentials:

```php
'host' => 'localhost',
'dbname' => 'luxestay_db',
'username' => 'root',
'password' => '',
```

## Security Note

Delete `backend/install.php` after installation in production.
