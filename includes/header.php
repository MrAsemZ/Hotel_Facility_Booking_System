<?php $currentUser = (new Auth())->user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(($pageTitle ?? 'Home') . ' | ' . HOTEL_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
<header class="site-header">
    <nav class="main-nav container">
        <a class="brand" href="<?= url('index.php') ?>"><?= e(HOTEL_NAME) ?></a>
        <a href="<?= url('pages/facilities.php') ?>">Facilities</a>
        <a href="<?= url('pages/availability.php') ?>">Book Now</a>
        <a href="<?= url('pages/about.php') ?>">About Us</a>
        <?php if ($currentUser): ?>
            <a href="<?= url('pages/my_reservations.php') ?>">My Reservations</a>
            <?php if ($currentUser['role'] === 'admin'): ?>
                <a href="<?= url('admin/index.php') ?>">Admin</a>
            <?php endif; ?>
            <a class="btn btn-small" href="<?= url('pages/logout.php') ?>">Logout (<?= e($currentUser['name']) ?>)</a>
        <?php else: ?>
            <a href="<?= url('pages/login.php') ?>">Login</a>
            <a class="btn btn-small" href="<?= url('pages/register.php') ?>">Register</a>
        <?php endif; ?>
    </nav>
</header>
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="container flash-wrap">
        <div class="msg <?= e($_SESSION['flash'][1]) ?>"><?= e($_SESSION['flash'][0]) ?></div>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
<main class="<?= !empty($fullWidth) ? 'full' : 'container page' ?>">
