<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Facility Booking</title>
    <link rel="stylesheet" href="<?= BASE ?>/css/style.css">
</head>
<body>
<nav>
    <a class="brand" href="<?= BASE ?>/index.php">Hotel Booking</a>
    <a href="<?= BASE ?>/index.php">Availability</a>
    <?php if ($user): ?>
        <a href="<?= BASE ?>/pages/my_reservations.php">My Reservations</a>
        <?php if ($user['role'] === 'admin'): ?>
            <a href="<?= BASE ?>/admin/index.php">Admin</a>
        <?php endif; ?>
        <a href="<?= BASE ?>/pages/logout.php">Logout (<?= e($user['name']) ?>)</a>
    <?php else: ?>
        <a href="<?= BASE ?>/pages/login.php">Login</a>
        <a href="<?= BASE ?>/pages/register.php">Register</a>
    <?php endif; ?>
</nav>
<main>
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="msg <?= e($_SESSION['flash'][1]) ?>"><?= e($_SESSION['flash'][0]) ?></div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
