</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <p class="footer-brand"><?= e(HOTEL_NAME) ?></p>
            <p><?= e(HOTEL_ADDRESS) ?></p>
        </div>
        <div>
            <p class="footer-title">Explore</p>
            <p>
                <a href="<?= url('pages/facilities.php') ?>">Facilities</a><br>
                <a href="<?= url('pages/how_it_works.php') ?>">How It Works</a><br>
                <a href="<?= url('pages/about.php') ?>">About Us</a><br>
                <a href="<?= url('pages/contact.php') ?>">Contact</a>
            </p>
        </div>
        <div>
            <p class="footer-title">Contact</p>
            <p><?= e(HOTEL_PHONE) ?><br><?= e(HOTEL_EMAIL) ?></p>
        </div>
    </div>
    <div class="footer-bottom">&copy; <?= date('Y') ?> <?= e(HOTEL_NAME) ?></div>
</footer>
</body>
</html>
