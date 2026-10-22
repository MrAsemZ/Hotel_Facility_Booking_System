<?php
require '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $found = $stmt->fetch();

    if (!$found || !password_verify($password, $found['password'])) {
        $error = 'Wrong email or password.';
    } elseif (!$found['is_active']) {
        $error = 'Your account is deactivated.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $found['id'];
        redirect($found['role'] === 'admin' ? '../admin/index.php' : '../index.php');
    }
}

require '../includes/header.php';
?>
<h1>Login</h1>
<?php if ($error): ?><div class="msg error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card">
    <label>Email <input type="email" name="email" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button>Login</button>
</form>
<?php require '../includes/footer.php'; ?>
