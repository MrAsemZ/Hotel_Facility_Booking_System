<?php
require_once __DIR__ . '/../includes/config.php';

$pageTitle = 'Contact';
include ROOT . '/includes/header.php';
?>
<h1>Contact</h1>
<div class="card">
    <p><strong>Address:</strong> <?= e(HOTEL_ADDRESS) ?></p>
    <p><strong>Phone:</strong> <?= e(HOTEL_PHONE) ?></p>
    <p><strong>Email:</strong> <?= e(HOTEL_EMAIL) ?></p>
    <p><strong>Reception:</strong> open every day, 24 hours.</p>
</div>
<p>Need help with a reservation? Call reception and mention your name and the date of your booking.</p>
<?php include ROOT . '/includes/footer.php'; ?>
