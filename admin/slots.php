<?php
require_once __DIR__ . '/../includes/config.php';

(new Auth())->requireAdmin();
$slots  = new TimeSlot();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete'])) {
        if ($slots->delete((int) $_POST['delete'])) {
            flash('Time slot deleted.');
        } else {
            flash('This slot has pending or confirmed reservations and cannot be deleted.', 'error');
        }
        redirect(url('admin/slots.php'));
    }

    $facilityId = (int) ($_POST['facility_id'] ?? 0);
    $date       = $_POST['slot_date'] ?? '';
    $start      = $_POST['start_time'] ?? '';
    $end        = $_POST['end_time'] ?? '';
    $capacity   = (int) ($_POST['capacity'] ?? 0);

    $errors = $slots->validate($facilityId, $date, $start, $end, $capacity);

    if (!$errors) {
        $slots->create($facilityId, $date, $start, $end, $capacity);
        flash('Time slot added.');
        redirect(url('admin/slots.php'));
    }
}

$facilities = (new Facility())->all();
$list       = $slots->all();

$pageTitle = 'Manage Time Slots';
include ROOT . '/includes/header.php';
include ROOT . '/includes/admin_nav.php';
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
    <?php foreach ($list as $s): ?>
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
    <?php if (!$list): ?><tr><td colspan="5">No time slots yet.</td></tr><?php endif; ?>
</table>
</div>
<?php include ROOT . '/includes/admin_end.php';
include ROOT . '/includes/footer.php'; ?>
