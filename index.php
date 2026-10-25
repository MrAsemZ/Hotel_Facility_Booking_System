<?php
require_once __DIR__ . '/includes/config.php';

$pageTitle  = 'Home';
$fullWidth  = true;
$facilities = (new Facility())->all();
$featured   = array_slice($facilities, 0, 3);

include ROOT . '/includes/header.php';
?>
<section class="hero">
    <div class="hero-inner">
        <p class="eyebrow">Hotel facility booking</p>
        <h1>Book our facilities, effortlessly</h1>
        <p class="hero-text">Pool, gym, spa and meeting room, reserved in a few clicks.</p>
        <a class="btn btn-light" href="<?= url('pages/facilities.php') ?>">Explore Facilities</a>
    </div>
</section>

<section class="container">
    <form method="get" action="<?= url('pages/availability.php') ?>" class="booking-bar">
        <label>Date
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
        </label>
        <label>Facility
            <select name="facility">
                <option value="">All facilities</option>
                <?php foreach ($facilities as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= e($f['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="btn">Check Availability</button>
    </form>
</section>

<section class="container section">
    <h2 class="section-title">Our Facilities</h2>
    <p class="section-sub">Everything you need during your stay.</p>
    <div class="grid">
        <?php foreach ($featured as $f): ?>
            <?php include ROOT . '/includes/facility_card.php'; ?>
        <?php endforeach; ?>
    </div>
    <p class="center"><a class="strong" href="<?= url('pages/facilities.php') ?>">See all facilities</a></p>
</section>

<section class="band">
    <div class="container">
        <h2 class="section-title">How to Book</h2>
        <div class="steps">
            <div class="step"><span class="step-number">1</span><h3>Create an account</h3><p>Register with your name, email and password.</p></div>
            <div class="step"><span class="step-number">2</span><h3>Pick a time slot</h3><p>Choose a facility, a date and a free slot.</p></div>
            <div class="step"><span class="step-number">3</span><h3>Wait for confirmation</h3><p>Your request stays Pending until the hotel confirms it.</p></div>
        </div>
        <p class="center"><a class="strong" href="<?= url('pages/how_it_works.php') ?>">Read the full guide</a></p>
    </div>
</section>

<section class="container section about-teaser">
    <img src="<?= asset('images/hotel.jpg') ?>" alt="The hotel">
    <div>
        <p class="eyebrow gold">About us</p>
        <h2>A calm place to stay and unwind</h2>
        <p><?= e(HOTEL_NAME) ?> welcomes guests and visitors with comfortable facilities and friendly service.</p>
        <a class="strong" href="<?= url('pages/about.php') ?>">Learn more about us</a>
    </div>
</section>
<?php include ROOT . '/includes/footer.php'; ?>
