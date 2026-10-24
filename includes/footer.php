</main>
<footer>
    <div>
        <strong><?= e(HOTEL_NAME) ?></strong><br>
        <?= e(HOTEL_ADDRESS) ?><br>
        <?= e(HOTEL_PHONE) ?> | <?= e(HOTEL_EMAIL) ?>
    </div>
    <div>
        <a href="<?= url('pages/facilities.php') ?>">Facilities</a> |
        <a href="<?= url('pages/how_it_works.php') ?>">How It Works</a> |
        <a href="<?= url('pages/about.php') ?>">About Us</a> |
        <a href="<?= url('pages/contact.php') ?>">Contact</a>
    </div>
    <div>&copy; <?= date('Y') ?> <?= e(HOTEL_NAME) ?></div>
</footer>
</body>
</html>
