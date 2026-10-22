<?php
session_start();

// URL folder of the project (change if you put it somewhere else)
define('BASE', '/hotel-booking');

$pdo = new PDO('mysql:host=localhost;dbname=hotel_booking;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function flash($message, $type = 'success')
{
    $_SESSION['flash'] = [$message, $type];
}

// Logged-in user from the database (null if guest or deactivated)
function current_user()
{
    global $pdo;
    static $user = false;

    if ($user === false) {
        $user = null;
        if (!empty($_SESSION['user_id'])) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? AND is_active = 1');
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch() ?: null;
        }
    }
    return $user;
}

function require_login()
{
    if (!current_user()) {
        flash('Please log in first.', 'error');
        redirect(BASE . '/pages/login.php');
    }
}

function require_admin()
{
    require_login();
    if (current_user()['role'] !== 'admin') {
        http_response_code(403);
        exit('403 - Admins only.');
    }
}
