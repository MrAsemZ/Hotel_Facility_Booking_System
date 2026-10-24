<?php
require_once __DIR__ . '/../includes/config.php';

$auth = new Auth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('pages/availability.php'));
}

$error = (new Reservation())->create(
    $auth->user()['id'],
    (int) ($_POST['slot_id'] ?? 0),
    trim($_POST['note'] ?? '')
);

if ($error) {
    flash($error, 'error');
} else {
    flash('Reservation submitted. Status: Pending.');
}
redirect(url('pages/my_reservations.php'));
