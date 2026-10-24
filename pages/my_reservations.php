<?php
require_once __DIR__ . '/../includes/config.php';

$auth = new Auth();
$auth->requireLogin();

$reservations = (new Reservation())->forUser($auth->user()['id']);

$pageTitle = 'My Reservations';
include ROOT . '/includes/header.php';
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
                <form method="post" action="<?= url('pages/cancel.php') ?>" onsubmit="return confirm('Cancel this reservation?')">
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
<?php include ROOT . '/includes/footer.php'; ?>
