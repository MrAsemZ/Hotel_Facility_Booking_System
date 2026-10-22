<?php
require '../includes/config.php';
require_admin();

// action => [new status, statuses it is allowed from]
$rules = [
    'confirm' => ['confirmed', ['pending']],
    'reject'  => ['rejected',  ['pending']],
    'cancel'  => ['cancelled', ['pending', 'confirmed']],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (isset($rules[$action])) {
        [$newStatus, $allowedFrom] = $rules[$action];
        $marks = implode(',', array_fill(0, count($allowedFrom), '?'));

        $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ? AND status IN ($marks)");
        $stmt->execute(array_merge([$newStatus, (int) ($_POST['id'] ?? 0)], $allowedFrom));

        if ($stmt->rowCount() > 0) {
            flash('Reservation updated.');
        } else {
            flash('This status change is not allowed.', 'error');
        }
    }
    redirect($_SERVER['REQUEST_URI']);
}

// Search + filter
$q      = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

$sql = "SELECT r.*, u.name, u.email, f.name AS facility, s.slot_date, s.start_time, s.end_time
        FROM reservations r
        JOIN users u ON u.id = r.user_id
        JOIN time_slots s ON s.id = r.slot_id
        JOIN facilities f ON f.id = s.facility_id
        WHERE (u.name LIKE ? OR u.email LIKE ?)";
$params = ["%$q%", "%$q%"];

if (in_array($status, ['pending', 'confirmed', 'rejected', 'cancelled'], true)) {
    $sql .= ' AND r.status = ?';
    $params[] = $status;
}
$sql .= ' ORDER BY s.slot_date DESC, s.start_time DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservations = $stmt->fetchAll();

require '../includes/header.php';
require '../includes/admin_nav.php';
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
    <?php foreach ($reservations as $r): ?>
    <tr>
        <td><?= e($r['name']) ?><br><small><?= e($r['email']) ?></small></td>
        <td><?= e($r['facility']) ?></td>
        <td><?= e($r['slot_date']) ?></td>
        <td><?= e(substr($r['start_time'], 0, 5)) ?> - <?= e(substr($r['end_time'], 0, 5)) ?></td>
        <td><?= e($r['note']) ?></td>
        <td><span class="badge <?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span></td>
        <td class="actions">
            <?php foreach ($rules as $act => [$to, $from]): ?>
                <?php if (in_array($r['status'], $from)): ?>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button name="action" value="<?= $act ?>"><?= ucfirst($act) ?></button>
                </form>
                <?php endif; ?>
            <?php endforeach; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$reservations): ?><tr><td colspan="7">No reservations found.</td></tr><?php endif; ?>
</table>
</div>
<?php require '../includes/footer.php'; ?>
