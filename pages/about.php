<?php
require_once __DIR__ . '/../includes/config.php';

$pageTitle = 'About Us';
include ROOT . '/includes/header.php';
?>
<h1>About Us</h1>
<img src="<?= asset('images/placeholder.jpg') ?>" alt="The hotel">

<h2>Our Story</h2>
<p><?= e(HOTEL_NAME) ?> is a family-run hotel that welcomes guests and visitors to enjoy comfortable
   facilities in a calm setting. (Replace this text with your hotel's real story.)</p>

<h2>What We Value</h2>
<div class="grid steps">
    <div><h3>Comfort</h3><p>Clean, quiet and well-kept spaces.</p></div>
    <div><h3>Service</h3><p>Friendly staff ready to help.</p></div>
    <div><h3>Simplicity</h3><p>Easy online booking with no hidden steps.</p></div>
</div>

<h2>Visit Us</h2>
<p><?= e(HOTEL_ADDRESS) ?><br><?= e(HOTEL_PHONE) ?><br><?= e(HOTEL_EMAIL) ?></p>
<?php include ROOT . '/includes/footer.php'; ?>
