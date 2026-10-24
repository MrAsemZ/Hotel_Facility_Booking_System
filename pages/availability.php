<?php
require_once __DIR__ . '/../includes/config.php';

$currentUser = (new Auth())->user();
$facilityIds = new Facility();

// Selected date (defaults to today) and optional facility
$date = $_GET['date'] ?? date('Y-m-d');
$parsed = DateTime::createFromFormat('Y-m-d', $date);
if (!$parsed || $parsed->format('Y-m-d') !== $date) {
    $date = date('Y-m-d');
}
$facilityId = (int) ($_GET['facility'] ?? 0) ?: null;

$facilities = $facilityIds->all();
$slots      = (new TimeSlot())->forDate($date, $currentUser['id'] ?? 0, $facilityId);

$pageTitle = 'Availability';
include ROOT . '/includes/header.php';
?>
<h1>Available Time Slots</h1>
<form method="get" class="inline">
    <input type="date" name="date" value="<?= e($date) ?>">
    <select name="facility">
        <option value="">All facilities</option>
        <?php foreach ($facilities as $f): ?>
            <option value="<?= $f['id'] ?>" <?= $facilityId === (int) $f['id'] ? 'selected' : '' ?>><?= e($f['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <button>Show</button>
</form>

<div class="table-wrap">
<table>
    <tr><th>Facility</th><th>Time</th><th>Remaining</th><th>Reserve</th></tr>
    <?php foreach ($slots as $s): $left = $s['capacity'] - $s['taken']; ?>
    <tr>
        <td><?= e($s['facility']) ?></td>
        <td><?= e(substr($s['start_time'], 0, 5)) ?> - <?= e(substr($s['end_time'], 0, 5)) ?></td>
        <td><?= $left ?> / <?= $s['capacity'] ?></td>
        <td>
            <?php if (!$currentUser): ?>
                <a href="<?= url('pages/login.php') ?>?next=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Log in to reserve</a>
            <?php elseif ($s['mine']): ?>
                Already reserved
            <?php elseif ($left <= 0): ?>
                Full
            <?php else: ?>
                <form method="post" action="<?= url('pages/reserve.php') ?>" class="inline">
                    <input type="hidden" name="slot_id" value="<?= $s['id'] ?>">
                    <input type="text" name="note" placeholder="Note (optional)" maxlength="255">
                    <button>Reserve</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$slots): ?><tr><td colspan="4">No available slots for this selection.</td></tr><?php endif; ?>
</table>
</div>
<?php include ROOT . '/includes/footer.php'; ?>
