<?php
define('ROOT', dirname(__DIR__));

// URL folder of the project (change if the folder name changes)
define('BASE', '/Hotel_Facility_Booking_System');

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'hotel_booking');
define('DB_USER', 'root');
define('DB_PASS', '');

// Hotel info shown on the site (change freely)
define('HOTEL_NAME', 'Grand Haven Hotel');
define('HOTEL_PHONE', '+962 6 000 0000');
define('HOTEL_EMAIL', 'info@grandhaven.example');
define('HOTEL_ADDRESS', 'Amman, Jordan');

session_start();

require_once ROOT . '/includes/helpers.php';
require_once ROOT . '/includes/autoload.php';
