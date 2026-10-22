<?php
require '../includes/config.php';
require_login();

$stmt = $pdo->prepare(
    "SELECT r.*, f.name AS facility, s.slot_date, s.start_time, s.end_time,
            (TIMESTAMP(s.slot_date, s.start_time) > NOW()) AS is_future
     FROM reservations r
     JOIN time_slots s ON s.id = r.slot_id
     JOIN facilities f ON f.id = s.facility_id
     WHERE r.user_id = ?
     ORDER BY s.slot_date DESC, s.start_time DESC"
);
$stmt->execute([current_user()['id']]);
$reservations = $stmt->fetchAll();

require '../includes/header.php';
?>
<h1>My Reservations</h1>
<div class="table-wrap">
<table>
    <tr><th>Facility</th><th>Date</th><th>Time</th><th>Note</th><th>Status</th><th></th></tr>
    <?php foreach ($reservations as $r): ?>
    <tr>
        <td><?= e($r['facility']) ?></td>
        <td><?= e($r['slot_date']) ?></td>
        <td><?= e(substr($r['start_time'], 0, 5)) ?> - <?= e(substr($r['end_time'], 0, 5)) ?></td>
        <td><?= e($r['note']) ?></td>
        <td><span class="badge <?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span></td>
        <td>
            <?php if (in_array($r['status'], ['pending', 'confirmed']) && $r['is_future']): ?>
                <form method="post" action="cancel.php" onsubmit="return confirm('Cancel this reservation?')">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button class="danger">Cancel</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$reservations): ?><tr><td colspan="6">No reservations yet.</td></tr><?php endif; ?>
</table>
</div>
<?php require '../includes/footer.php'; ?>
