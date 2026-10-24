<article class="facility-card">
    <img src="<?= e(Facility::imageUrl($f['image'])) ?>" alt="<?= e($f['name']) ?>">
    <div class="facility-body">
        <h3><?= e($f['name']) ?></h3>
        <p><?= e($f['short_description']) ?></p>
        <p class="hours"><?= e($f['opening_hours']) ?></p>
        <a href="<?= url('pages/facility.php?id=' . $f['id']) ?>">Details</a>
        <span class="sep">|</span>
        <a class="book-link" href="<?= url('pages/availability.php?facility=' . $f['id']) ?>">Book</a>
    </div>
</article>
