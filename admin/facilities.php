<?php
require_once __DIR__ . '/../includes/config.php';

(new Auth())->requireAdmin();
$facilities = new Facility();
$errors     = [];

// Form values: empty for "add", filled for "edit"
$form = ['name' => '', 'short_description' => '', 'description' => '',
         'image' => '', 'opening_hours' => '', 'guidelines' => ''];
$editId = (int) ($_GET['edit'] ?? 0);

if ($editId && ($existing = $facilities->find($editId))) {
    $form = array_intersect_key($existing, $form);
} else {
    $editId = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editId = (int) ($_POST['id'] ?? 0);
    foreach ($form as $field => $value) {
        $form[$field] = trim($_POST[$field] ?? '');
    }

    $errors = $facilities->validate($form);

    if (!$errors) {
        if ($editId) {
            $facilities->update($editId, $form);
            flash('Facility updated.');
        } else {
            $facilities->create($form);
            flash('Facility added.');
        }
        redirect(url('admin/facilities.php'));
    }
}

$list = $facilities->all();

$pageTitle = 'Manage Facilities';
include ROOT . '/includes/header.php';
include ROOT . '/includes/admin_nav.php';
?>
<h1>Facilities</h1>
<?php foreach ($errors as $err): ?><div class="msg error"><?= e($err) ?></div><?php endforeach; ?>

<h2><?= $editId ? 'Edit facility' : 'Add facility' ?></h2>
<form method="post" class="card">
    <input type="hidden" name="id" value="<?= $editId ?>">
    <label>Name <input type="text" name="name" value="<?= e($form['name']) ?>" required></label>
    <label>Short description <input type="text" name="short_description" value="<?= e($form['short_description']) ?>" required></label>
    <label>Description <textarea name="description" rows="4" required><?= e($form['description']) ?></textarea></label>
    <label>Image file name (inside assets/images) <input type="text" name="image" value="<?= e($form['image']) ?>" placeholder="pool.jpg"></label>
    <label>Opening hours <input type="text" name="opening_hours" value="<?= e($form['opening_hours']) ?>"></label>
    <label>Guidelines (one per line) <textarea name="guidelines" rows="5"><?= e($form['guidelines']) ?></textarea></label>
    <button><?= $editId ? 'Save changes' : 'Add facility' ?></button>
    <?php if ($editId): ?><a href="<?= url('admin/facilities.php') ?>">Cancel</a><?php endif; ?>
</form>

<div class="table-wrap">
<table>
    <tr><th>Name</th><th>Hours</th><th>Image</th><th></th></tr>
    <?php foreach ($list as $f): ?>
    <tr>
        <td><?= e($f['name']) ?></td>
        <td><?= e($f['opening_hours']) ?></td>
        <td><?= e($f['image']) ?></td>
        <td><a href="<?= url('admin/facilities.php?edit=' . $f['id']) ?>">Edit</a></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<?php include ROOT . '/includes/admin_end.php';
include ROOT . '/includes/footer.php'; ?>
