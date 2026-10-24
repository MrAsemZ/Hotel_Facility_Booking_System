<?php
require_once __DIR__ . '/../includes/config.php';

$error = '';
$next  = safeNext($_POST['next'] ?? $_GET['next'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth  = new Auth();
    $error = $auth->login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');

    if (!$error) {
        // Back to the page the visitor came from, otherwise a default page by role
        redirect($next ?: ($auth->user()['role'] === 'admin' ? url('admin/index.php') : url('index.php')));
    }
}

$pageTitle = 'Login';
include ROOT . '/includes/header.php';
?>
<h1>Login</h1>
<?php if ($error): ?><div class="msg error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card">
    <input type="hidden" name="next" value="<?= e($next) ?>">
    <label>Email <input type="email" name="email" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button>Login</button>
</form>
<p>No account? <a href="<?= url('pages/register.php') ?><?= $next ? '?next=' . urlencode($next) : '' ?>">Register</a></p>
<?php include ROOT . '/includes/footer.php'; ?>
