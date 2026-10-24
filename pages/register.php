<?php
require_once __DIR__ . '/../includes/config.php';

$errors = [];
$name = $email = '';
$next = safeNext($_POST['next'] ?? $_GET['next'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $errors = (new Auth())->register($name, $email, $_POST['password'] ?? '', $_POST['confirm_password'] ?? '');

    if (!$errors) {
        flash('Account created. You can log in now.');
        redirect(url('pages/login.php') . ($next ? '?next=' . urlencode($next) : ''));
    }
}

$pageTitle = 'Register';
include ROOT . '/includes/header.php';
?>
<h1>Register</h1>
<?php foreach ($errors as $err): ?><div class="msg error"><?= e($err) ?></div><?php endforeach; ?>
<form method="post" class="card">
    <input type="hidden" name="next" value="<?= e($next) ?>">
    <label>Name <input type="text" name="name" value="<?= e($name) ?>" required></label>
    <label>Email <input type="email" name="email" value="<?= e($email) ?>" required></label>
    <label>Password <input type="password" name="password" required></label>
    <label>Confirm password <input type="password" name="confirm_password" required></label>
    <button>Register</button>
</form>
<p>Already have an account? <a href="<?= url('pages/login.php') ?><?= $next ? '?next=' . urlencode($next) : '' ?>">Login</a></p>
<?php include ROOT . '/includes/footer.php'; ?>
