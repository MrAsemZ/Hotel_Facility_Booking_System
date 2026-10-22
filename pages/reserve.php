<?php
require '../includes/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$userId = current_user()['id'];
$slotId = (int) ($_POST['slot_id'] ?? 0);
$note   = trim($_POST['note'] ?? '');

if (strlen($note) > 255) {
    flash('Note is too long (max 255 characters).', 'error');
    redirect('../index.php');
}

$pdo->beginTransaction();

// Lock the slot row so two users cannot take the last place together
$stmt = $pdo->prepare(
    'SELECT *, (TIMESTAMP(slot_date, start_time) > NOW()) AS is_future
     FROM time_slots WHERE id = ? FOR UPDATE'
);
$stmt->execute([$slotId]);
$slot = $stmt->fetch();

$error = '';
if (!$slot) {
    $error = 'Time slot not found.';
} elseif (!$slot['is_future']) {
    $error = 'You cannot reserve a time slot in the past.';
} else {
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) AS taken,
                SUM(user_id = ?) AS mine
         FROM reservations
         WHERE slot_id = ? AND status IN ('pending','confirmed')"
    );
    $stmt->execute([$userId, $slotId]);
    $count = $stmt->fetch();

    if ($count['mine'] > 0) {
        $error = 'You already reserved this time slot.';
    } elseif ($count['taken'] >= $slot['capacity']) {
        $error = 'This time slot is full.';
    }
}

if ($error) {
    $pdo->rollBack();
    flash($error, 'error');
} else {
    $stmt = $pdo->prepare('INSERT INTO reservations (user_id, slot_id, note) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $slotId, $note !== '' ? $note : null]);
    $pdo->commit();
    flash('Reservation submitted. Status: Pending.');
}

redirect('my_reservations.php');
