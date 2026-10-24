<?php
$adminLinks = [
    'index.php'        => 'Dashboard',
    'reservations.php' => 'Reservations',
    'users.php'        => 'Users',
    'slots.php'        => 'Time Slots',
    'facilities.php'   => 'Facilities',
];
$here = basename($_SERVER['SCRIPT_NAME']);
?>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <?php foreach ($adminLinks as $file => $label): ?>
            <a href="<?= url('admin/' . $file) ?>" class="<?= $here === $file ? 'active' : '' ?>"><?= $label ?></a>
        <?php endforeach; ?>
    </aside>
    <section class="admin-content">
