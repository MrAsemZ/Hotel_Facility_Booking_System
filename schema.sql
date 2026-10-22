CREATE DATABASE IF NOT EXISTS hotel_booking CHARACTER SET utf8mb4;
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
    name VARCHAR(100) NOT NULL
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

INSERT INTO facilities (name) VALUES ('Swimming Pool'), ('Gym'), ('Spa'), ('Meeting Room');

INSERT INTO time_slots (facility_id, slot_date, start_time, end_time, capacity) VALUES
(1, CURDATE() + INTERVAL 1 DAY, '09:00', '11:00', 10),
(1, CURDATE() + INTERVAL 1 DAY, '11:00', '13:00', 10),
(2, CURDATE() + INTERVAL 1 DAY, '07:00', '09:00', 5),
(3, CURDATE() + INTERVAL 2 DAY, '14:00', '15:00', 2),
(4, CURDATE() + INTERVAL 2 DAY, '10:00', '12:00', 1);
