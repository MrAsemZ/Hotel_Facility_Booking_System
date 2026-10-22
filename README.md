# Hotel Facility Booking

A simple PHP + MySQL system for booking hotel facilities (pool, gym, spa, meeting room) by time slot.
Users register, view slots, and reserve. Admins manage reservations, users and slots.

## Technologies
PHP (PDO, sessions), MySQL, HTML, CSS, Chart.js (CDN).

## Installation
1. Install WAMP and start Apache + MySQL.
2. Copy this folder to `www/Hotel_Facility_Booking_System`.
3. Import `schema.sql` in phpMyAdmin (it creates the `hotel_booking` database and sample data).
4. If your MySQL has a password or the folder name differs, edit `includes/config.php` (`BASE` and the PDO line).
5. Open http://localhost/Hotel_Facility_Booking_System

## Demo accounts
| Role  | Email           | Password |
|-------|-----------------|----------|
| Admin | admin@hotel.com | admin123 |
| User  | user@hotel.com  | user123  |

## Business rules (enforced on the server)
- No reservations in the past, no duplicates, capacity is respected.
- Cancelled/rejected reservations free their place.
- Slots with pending/confirmed reservations cannot be deleted.
- Only admins confirm/reject; users cancel only their own reservations.
- Deactivated accounts cannot log in.

## Manual test checklist
Register/login · invalid inputs · user opening /admin gets 403 · cancel another user's reservation fails ·
past slot rejected · duplicate rejected · full slot rejected · status change visible to user ·
deactivated user blocked · slot with active reservation cannot be deleted.
