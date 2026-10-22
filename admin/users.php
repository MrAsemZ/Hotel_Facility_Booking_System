<?php
require '../includes/config.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Flip active/inactive (admins are excluded)
    $stmt = $pdo->prepare("UPDATE users SET is_active = 1 - is_active WHERE id = ? AND role = 'user'");
    $stmt->execute([(int) ($_POST['id'] ?? 0)]);
    flash('User updated.');
    redirect('users.php');
}

$users = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY id DESC")->fetchAll();

require '../includes/header.php';
require '../includes/admin_nav.php';
?>
<h1>Users</h1>
<div class="table-wrap">
<table>
    <tr><th>Name</th><th>Email</th><th>Status</th><th></th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= e($u['name']) ?></td>
        <td><?= e($u['email']) ?></td>
        <td><?= $u['is_active'] ? 'Active' : 'Deactivated' ?></td>
        <td>
            <form method="post">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button class="<?= $u['is_active'] ? 'danger' : '' ?>">
                    <?= $u['is_active'] ? 'Deactivate' : 'Activate' ?>
                </button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$users): ?><tr><td colspan="4">No users yet.</td></tr><?php endif; ?>
</table>
</div>
<?php require '../includes/footer.php'; ?>
