<?php
require 'includes/config.php';

$user = current_user();

// Selected date (defaults to today)
$date = $_GET['date'] ?? date('Y-m-d');
$parsed = DateTime::createFromFormat('Y-m-d', $date);
if (!$parsed || $parsed->format('Y-m-d') !== $date) {
    $date = date('Y-m-d');
}

// Future slots of that date, with how many places are taken
$stmt = $pdo->prepare(
    "SELECT s.*, f.name AS facility,
        (SELECT COUNT(*) FROM reservations r
          WHERE r.slot_id = s.id AND r.status IN ('pending','confirmed')) AS taken,
        (SELECT COUNT(*) FROM reservations r
          WHERE r.slot_id = s.id AND r.user_id = ? AND r.status IN ('pending','confirmed')) AS mine
     FROM time_slots s
     JOIN facilities f ON f.id = s.facility_id
     WHERE s.slot_date = ? AND TIMESTAMP(s.slot_date, s.start_time) > NOW()
     ORDER BY s.start_time, f.name"
);
$stmt->execute([$user['id'] ?? 0, $date]);
$slots = $stmt->fetchAll();

require 'includes/header.php';
?>
<h1>Available Time Slots</h1>
<form method="get" class="inline">
    <input type="date" name="date" value="<?= e($date) ?>">
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
            <?php if (!$user): ?>
                <a href="pages/login.php">Log in to reserve</a>
            <?php elseif ($s['mine']): ?>
                Already reserved
            <?php elseif ($left <= 0): ?>
                Full
            <?php else: ?>
                <form method="post" action="pages/reserve.php" class="inline">
                    <input type="hidden" name="slot_id" value="<?= $s['id'] ?>">
                    <input type="text" name="note" placeholder="Note (optional)" maxlength="255">
                    <button>Reserve</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$slots): ?><tr><td colspan="4">No available slots on this date.</td></tr><?php endif; ?>
</table>
</div>
<?php require 'includes/footer.php'; ?>
