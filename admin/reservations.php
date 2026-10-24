<?php
require_once __DIR__ . '/../includes/config.php';

(new Auth())->requireAdmin();
$reservations = new Reservation();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $done = $reservations->changeStatus((int) ($_POST['id'] ?? 0), $_POST['action'] ?? '');
    flash($done ? 'Reservation updated.' : 'This status change is not allowed.', $done ? 'success' : 'error');
    redirect($_SERVER['REQUEST_URI']);
}

$q      = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$list   = $reservations->search($q, $status);

$pageTitle = 'Manage Reservations';
include ROOT . '/includes/header.php';
include ROOT . '/includes/admin_nav.php';
?>
<h1>Reservations</h1>
<form method="get" class="inline">
    <input type="text" name="q" placeholder="Search name or email" value="<?= e($q) ?>">
    <select name="status">
        <option value="">All statuses</option>
        <?php foreach (['pending', 'confirmed', 'rejected', 'cancelled'] as $st): ?>
            <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
        <?php endforeach; ?>
    </select>
    <button>Filter</button>
</form>

<div class="table-wrap">
<table>
    <tr><th>User</th><th>Facility</th><th>Date</th><th>Time</th><th>Note</th><th>Status</th><th>Actions</th></tr>
    <?php foreach ($list as $r): ?>
    <tr>
        <td><?= e($r['name']) ?><br><small><?= e($r['email']) ?></small></td>
        <td><?= e($r['facility']) ?></td>
        <td><?= e($r['slot_date']) ?></td>
        <td><?= e(substr($r['start_time'], 0, 5)) ?> - <?= e(substr($r['end_time'], 0, 5)) ?></td>
        <td><?= e($r['note']) ?></td>
        <td><span class="badge <?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span></td>
        <td class="actions">
            <?php foreach (Reservation::actionsFor($r['status']) as $action): ?>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button name="action" value="<?= $action ?>"><?= ucfirst($action) ?></button>
                </form>
            <?php endforeach; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$list): ?><tr><td colspan="7">No reservations found.</td></tr><?php endif; ?>
</table>
</div>
<?php include ROOT . '/includes/admin_end.php';
include ROOT . '/includes/footer.php'; ?>
