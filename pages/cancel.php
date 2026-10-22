<?php
require '../includes/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('my_reservations.php');
}

// Only the owner can cancel, only active reservations, only before the slot starts
$stmt = $pdo->prepare(
    "UPDATE reservations r
     JOIN time_slots s ON s.id = r.slot_id
     SET r.status = 'cancelled'
     WHERE r.id = ? AND r.user_id = ?
       AND r.status IN ('pending','confirmed')
       AND TIMESTAMP(s.slot_date, s.start_time) > NOW()"
);
$stmt->execute([(int) ($_POST['id'] ?? 0), current_user()['id']]);

if ($stmt->rowCount() > 0) {
    flash('Reservation cancelled.');
} else {
    flash('This reservation cannot be cancelled.', 'error');
}
redirect('my_reservations.php');
