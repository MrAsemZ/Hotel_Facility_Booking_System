-- WARNING: this recreates the database from scratch (old data is deleted)
DROP DATABASE IF EXISTS hotel_booking;
CREATE DATABASE hotel_booking CHARACTER SET utf8mb4;
USE hotel_booking;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    is_active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(100) NOT NULL DEFAULT 'placeholder.svg',
    opening_hours VARCHAR(100) NOT NULL DEFAULT '',
    guidelines TEXT NULL
);

CREATE TABLE time_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL,
    slot_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    capacity INT NOT NULL,
    FOREIGN KEY (facility_id) REFERENCES facilities(id)
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    slot_id INT NOT NULL,
    note VARCHAR(255) NULL,
    status ENUM('pending','confirmed','rejected','cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (slot_id) REFERENCES time_slots(id) ON DELETE CASCADE
);

-- Demo accounts: admin@hotel.com / admin123  |  user@hotel.com / user123
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@hotel.com', '$2y$10$pzG/Yx7F7q.l7CkYFH3LuuCagzXBiy0FvbwsRYcP2FG7kWkDrFmaW', 'admin'),
('Demo User', 'user@hotel.com', '$2y$10$oQUv0lXyMc9kVi..g0K0XuV4oZHhQHKPI2NPvyf7PXpx.tfjg.D2.', 'user');

INSERT INTO facilities (name, short_description, description, image, opening_hours, guidelines) VALUES
('Swimming Pool',
 'Heated indoor pool with a shallow area for children.',
 'Our heated indoor pool is open all year. It has swimming lanes, a shallow area for children and a relaxing seating area around it. Towels are provided at the entrance.',
 'pool.svg', 'Daily 07:00 - 21:00',
 'Shower before entering the pool\nSwimwear is required\nChildren under 12 must be with an adult\nNo glass items or running around the pool'),
('Gym',
 'Fully equipped gym with cardio and strength machines.',
 'The gym has treadmills, bikes, free weights and strength machines. Water and towels are available inside. Sessions are limited to keep the space comfortable.',
 'gym.svg', 'Daily 06:00 - 22:00',
 'Wear sports shoes and clean clothes\nWipe machines after use\nPut weights back after your session\nMinimum age is 16'),
('Spa',
 'Relaxing spa with massage and sauna sessions.',
 'The spa offers massage rooms, a sauna and a quiet relaxation area. Booking a slot reserves your place for the session.',
 'spa.svg', 'Daily 10:00 - 20:00',
 'Arrive 10 minutes before your slot\nKeep your phone silent\nTell the staff about any health concerns before treatments\nMinimum age is 18'),
('Meeting Room',
 'Private meeting room with projector and Wi-Fi.',
 'A private meeting room for small groups, with a projector, whiteboard and fast Wi-Fi. Suitable for business meetings or study groups.',
 'meeting-room.svg', 'Daily 08:00 - 18:00',
 'Leave the room clean after use\nFood and drinks must be ordered from hotel staff\nReport any equipment problem to reception');

INSERT INTO time_slots (facility_id, slot_date, start_time, end_time, capacity) VALUES
(1, CURDATE() + INTERVAL 1 DAY, '09:00', '11:00', 10),
(1, CURDATE() + INTERVAL 1 DAY, '11:00', '13:00', 10),
(2, CURDATE() + INTERVAL 1 DAY, '07:00', '09:00', 5),
(3, CURDATE() + INTERVAL 2 DAY, '14:00', '15:00', 2),
(4, CURDATE() + INTERVAL 2 DAY, '10:00', '12:00', 1);
