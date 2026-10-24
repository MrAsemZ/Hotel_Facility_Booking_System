<?php
require_once __DIR__ . '/../includes/config.php';

$pageTitle  = 'Facilities';
$facilities = (new Facility())->all();

include ROOT . '/includes/header.php';
?>
<h1>Our Facilities</h1>
<div class="grid">
    <?php foreach ($facilities as $f): ?>
        <?php include ROOT . '/includes/facility_card.php'; ?>
    <?php endforeach; ?>
</div>
<?php if (!$facilities): ?><p>No facilities yet.</p><?php endif; ?>
<?php include ROOT . '/includes/footer.php'; ?>
