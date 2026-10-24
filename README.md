# Hotel Facility Booking System

A PHP (OOP + includes) and MySQL website for browsing and booking hotel facilities by time slot.

## Technologies
PHP 8 (PDO, sessions, classes), MySQL, HTML, CSS, Chart.js (CDN).

## Installation (WAMP)
1. Copy this folder to `C:\wamp64\www\Hotel_Facility_Booking_System`.
2. Start WAMP, open `http://localhost/phpmyadmin`.
3. Go to the **Import** tab, choose `schema.sql`, click **Import** (it creates the `hotel_booking` database with sample data).
4. Open `http://localhost/Hotel_Facility_Booking_System`.
5. If MySQL has a password or the folder name differs, edit `includes/config.php`.

## Demo accounts
| Role  | Email           | Password |
|-------|-----------------|----------|
| Admin | admin@hotel.com | admin123 |
| User  | user@hotel.com  | user123  |

## Structure
```
index.php        Home page
classes/         Database, Model, Auth, User, Facility, TimeSlot, Reservation
includes/        config, helpers, autoload, header, footer, shared pieces
pages/           public + user pages
admin/           admin dashboard and management pages
assets/          css/ and images/ (replace the placeholder .svg with your own photos)
schema.sql       database
```

## Business rules (enforced in the classes)
- No reservations in the past, no duplicates, capacity is respected.
- Cancelled/rejected reservations free their place.
- Slots with pending/confirmed reservations cannot be deleted.
- Only admins confirm/reject; users cancel only their own reservations.
- Deactivated accounts cannot log in.
