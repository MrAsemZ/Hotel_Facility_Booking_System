<?php
require_once __DIR__ . '/../includes/config.php';

$auth = new Auth();
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $done = (new Reservation())->cancelByUser((int) ($_POST['id'] ?? 0), $auth->user()['id']);

    if ($done) {
        flash('Reservation cancelled.');
    } else {
        flash('This reservation cannot be cancelled.', 'error');
    }
}
redirect(url('pages/my_reservations.php'));
