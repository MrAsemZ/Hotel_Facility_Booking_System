<?php
require_once __DIR__ . '/../includes/config.php';

$f = (new Facility())->find((int) ($_GET['id'] ?? 0));
if (!$f) {
    flash('Facility not found.', 'error');
    redirect(url('pages/facilities.php'));
}

$pageTitle  = $f['name'];
$guidelines = Facility::guidelineList($f['guidelines']);

include ROOT . '/includes/header.php';
?>
<h1><?= e($f['name']) ?></h1>
<img src="<?= e(Facility::imageUrl($f['image'])) ?>" alt="<?= e($f['name']) ?>">

<p><?= nl2br(e($f['description'])) ?></p>
<p><strong>Opening hours:</strong> <?= e($f['opening_hours']) ?></p>

<?php if ($guidelines): ?>
    <h2>Guidelines</h2>
    <ul>
        <?php foreach ($guidelines as $line): ?><li><?= e($line) ?></li><?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>
    <a class="button" href="<?= url('pages/availability.php?facility=' . $f['id']) ?>">Book this facility</a>
    <a href="<?= url('pages/facilities.php') ?>">Back to facilities</a>
</p>
<?php include ROOT . '/includes/footer.php'; ?>
