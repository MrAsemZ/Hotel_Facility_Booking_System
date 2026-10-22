<?php
require '../includes/config.php';
require_admin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Delete a slot (only if it has no pending/confirmed reservations)
    if (isset($_POST['delete'])) {
        $id = (int) $_POST['delete'];
        $stmt = $pdo->prepare(
            "DELETE FROM time_slots WHERE id = ?
             AND NOT EXISTS (SELECT 1 FROM reservations r
                             WHERE r.slot_id = time_slots.id AND r.status IN ('pending','confirmed'))"
        );
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            flash('Time slot deleted.');
        } else {
            flash('This slot has pending or confirmed reservations and cannot be deleted.', 'error');
        }
        redirect('slots.php');
    }

    // Add a slot
    $facilityId = (int) ($_POST['facility_id'] ?? 0);
    $date       = $_POST['slot_date'] ?? '';
    $start      = $_POST['start_time'] ?? '';
    $end        = $_POST['end_time'] ?? '';
    $capacity   = (int) ($_POST['capacity'] ?? 0);

    $check = $pdo->prepare('SELECT id FROM facilities WHERE id = ?');
    $check->execute([$facilityId]);
    if (!$check->fetch()) {
        $errors[] = 'Choose a valid facility.';
    }

    $parsed = DateTime::createFromFormat('Y-m-d', $date);
    if (!$parsed || $parsed->format('Y-m-d') !== $date) {
        $errors[] = 'Enter a valid date.';
    } elseif ($date < date('Y-m-d')) {
        $errors[] = 'The date cannot be in the past.';
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $start) || !preg_match('/^\d{2}:\d{2}$/', $end)) {
        $errors[] = 'Enter valid start and end times.';
    } elseif ($end <= $start) {
        $errors[] = 'End time must be after start time.';
    }

    if ($capacity < 1 || $capacity > 500) {
        $errors[] = 'Capacity must be between 1 and 500.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO time_slots (facility_id, slot_date, start_time, end_time, capacity) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$facilityId, $date, $start, $end, $capacity]);
        flash('Time slot added.');
        redirect('slots.php');
    }
}

$facilities = $pdo->query('SELECT * FROM facilities ORDER BY name')->fetchAll();
$slots = $pdo->query(
    "SELECT s.*, f.name AS facility,
        (SELECT COUNT(*) FROM reservations r
          WHERE r.slot_id = s.id AND r.status IN ('pending','confirmed')) AS taken
     FROM time_slots s JOIN facilities f ON f.id = s.facility_id
     ORDER BY s.slot_date DESC, s.start_time"
)->fetchAll();

require '../includes/header.php';
require '../includes/admin_nav.php';
?>
<h1>Time Slots</h1>
<?php foreach ($errors as $err): ?><div class="msg error"><?= e($err) ?></div><?php endforeach; ?>

<form method="post" class="card">
    <label>Facility
        <select name="facility_id" required>
            <?php foreach ($facilities as $f): ?>
                <option value="<?= $f['id'] ?>"><?= e($f['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Date <input type="date" name="slot_date" required></label>
    <label>Start time <input type="time" name="start_time" required></label>
    <label>End time <input type="time" name="end_time" required></label>
    <label>Capacity <input type="number" name="capacity" min="1" max="500" required></label>
    <button>Add slot</button>
</form>

<div class="table-wrap">
<table>
    <tr><th>Facility</th><th>Date</th><th>Time</th><th>Booked / Capacity</th><th></th></tr>
    <?php foreach ($slots as $s): ?>
    <tr>
        <td><?= e($s['facility']) ?></td>
        <td><?= e($s['slot_date']) ?></td>
        <td><?= e(substr($s['start_time'], 0, 5)) ?> - <?= e(substr($s['end_time'], 0, 5)) ?></td>
        <td><?= $s['taken'] ?> / <?= $s['capacity'] ?></td>
        <td>
            <form method="post" onsubmit="return confirm('Delete this slot?')">
                <button class="danger" name="delete" value="<?= $s['id'] ?>">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$slots): ?><tr><td colspan="5">No time slots yet.</td></tr><?php endif; ?>
</table>
</div>
<?php require '../includes/footer.php'; ?>
