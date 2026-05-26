# LuxeStay — Hotel Booking Management System (Frontend)

Premium luxury hotel booking **frontend only**. No backend, database, or server-side logic.

## Tech Stack

- HTML5 / PHP (includes & page structure)
- CSS3 (custom + Bootstrap 5)
- JavaScript (ES6+)
- AJAX with dummy JSON mock APIs
- Chart.js (admin dashboard)
- Font Awesome 6 · Google Fonts (Poppins)

## Project Structure

```
hotel-booking-system/
├── index.php                    # Redirects to frontend/
├── README.md
└── frontend/
    ├── index.php                # Panel selector (User / Admin)
    ├── assets/                  # Shared user panel assets (CSS, JS, mock data)
    ├── user-panel/              # Customer-facing website
    │   ├── includes/
    │   ├── components/
    │   ├── index.php
    │   ├── login.php
    │   ├── hotels.php
    │   └── ...
    └── admin-panel/             # Admin management console
        ├── includes/
        ├── components/
        ├── assets/              # Admin-specific CSS, JS, data
        ├── login.php
        ├── index.php            # Dashboard
        ├── hotels.php
        ├── rooms.php
        ├── bookings.php
        ├── users.php
        ├── payments.php
        ├── reports.php
        ├── revenue.php
        ├── availability.php
        ├── customers.php
        ├── images.php
        └── notifications.php
```

## Run Locally (XAMPP)

1. Place project in `htdocs` (e.g. `Hotel Booking Management system/hotel-booking-system/`)
2. Start Apache in XAMPP
3. Open:
   - **Panel selector:** `http://localhost/Hotel%20Booking%20Management%20system/hotel-booking-system/`
   - **User panel:** `.../hotel-booking-system/frontend/user-panel/`
   - **Admin panel:** `.../hotel-booking-system/frontend/admin-panel/login.php`

## User Panel Pages

| Page | Description |
|------|-------------|
| `index.php` | Home — hero slider, search, featured hotels |
| `login.php` | User login + forgot password |
| `register.php` | User registration |
| `hotels.php` | Hotel grid, search, filters (AJAX) |
| `hotel-details.php` | Gallery, rooms, facilities, reviews |
| `booking.php` | Guest form, payment UI, confirmation |
| `payment.php` | Standalone payment step |
| `profile.php` | Profile & recent bookings |
| `booking-history.php` | Booking cards & cancellation |
| `contact.php` | Support form & FAQ |

## Admin Panel Features

| Feature | Page |
|---------|------|
| Admin Login | `login.php` |
| Dashboard Overview | `index.php` |
| Hotel Management | `hotels.php` |
| Room Management | `rooms.php` |
| Booking Management | `bookings.php` |
| User Management | `users.php` |
| Payment Management | `payments.php` |
| Report Generation | `reports.php` |
| Revenue Tracking | `revenue.php` |
| Room Availability | `availability.php` |
| Customer Management | `customers.php` |
| Hotel Image Management | `images.php` |
| Notification Management | `notifications.php` |

**Admin demo login:** use any email except `fail@admin.com` to sign in.

## AJAX Demo (Mock JSON)

User panel mock data in `frontend/assets/data/`:

- `hotels.json` — hotel search & filter
- `login-response.json` — login form
- `register-response.json` — registration
- `booking-response.json` — booking submit
- `availability-response.json` — room availability

Handled in `frontend/assets/js/ajax.js`. Use email `fail@demo.com` on user login to test error toast.

## Color Theme

- Dark Navy: `#0a1628`, `#1a2744`
- White / Off-white: `#ffffff`, `#f8f9fc`
- Gold accents: `#c9a227`, `#e8c547`
