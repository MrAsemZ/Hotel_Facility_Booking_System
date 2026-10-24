<?php
require_once __DIR__ . '/includes/config.php';

$pageTitle  = 'Home';
$featured   = array_slice((new Facility())->all(), 0, 3);

include ROOT . '/includes/header.php';
?>
<section class="hero">
    <img src="<?= asset('images/hero.svg') ?>" alt="Hotel">
    <h1>Welcome to <?= e(HOTEL_NAME) ?></h1>
    <p>Reserve our pool, gym, spa and meeting room in a few clicks.</p>
    <a class="button" href="<?= url('pages/facilities.php') ?>">Explore Facilities</a>
    <a class="button" href="<?= url('pages/availability.php') ?>">Check Availability</a>
</section>

<section>
    <h2>Our Facilities</h2>
    <div class="grid">
        <?php foreach ($featured as $f): ?>
            <?php include ROOT . '/includes/facility_card.php'; ?>
        <?php endforeach; ?>
    </div>
    <p><a href="<?= url('pages/facilities.php') ?>">See all facilities</a></p>
</section>

<section>
    <h2>How to Book</h2>
    <div class="grid steps">
        <div><h3>1. Create an account</h3><p>Register with your name, email and password.</p></div>
        <div><h3>2. Pick a time slot</h3><p>Choose a facility, a date and a free slot.</p></div>
        <div><h3>3. Wait for confirmation</h3><p>Your request is Pending until the hotel confirms it.</p></div>
    </div>
    <p><a href="<?= url('pages/how_it_works.php') ?>">Read the full guide</a></p>
</section>
<?php include ROOT . '/includes/footer.php'; ?>
