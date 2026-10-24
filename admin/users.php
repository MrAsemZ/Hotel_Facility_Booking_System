<?php
require_once __DIR__ . '/../includes/config.php';

(new Auth())->requireAdmin();
$users = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users->toggleActive((int) ($_POST['id'] ?? 0));
    flash('User updated.');
    redirect(url('admin/users.php'));
}

$list = $users->allCustomers();

$pageTitle = 'Manage Users';
include ROOT . '/includes/header.php';
include ROOT . '/includes/admin_nav.php';
?>
<h1>Users</h1>
<div class="table-wrap">
<table>
    <tr><th>Name</th><th>Email</th><th>Status</th><th></th></tr>
    <?php foreach ($list as $u): ?>
    <tr>
        <td><?= e($u['name']) ?></td>
        <td><?= e($u['email']) ?></td>
        <td><?= $u['is_active'] ? 'Active' : 'Deactivated' ?></td>
        <td>
            <form method="post">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button class="<?= $u['is_active'] ? 'danger' : '' ?>"><?= $u['is_active'] ? 'Deactivate' : 'Activate' ?></button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$list): ?><tr><td colspan="4">No users yet.</td></tr><?php endif; ?>
</table>
</div>
<?php include ROOT . '/includes/admin_end.php';
include ROOT . '/includes/footer.php'; ?>
