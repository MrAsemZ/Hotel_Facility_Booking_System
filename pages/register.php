<?php
require '../includes/config.php';

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || strlen($name) > 100) {
        $errors[] = 'Name is required (max 100 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'This email is already registered.';
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
        flash('Account created. You can log in now.');
        redirect('login.php');
    }
}

require '../includes/header.php';
?>
<h1>Register</h1>
<?php foreach ($errors as $err): ?><div class="msg error"><?= e($err) ?></div><?php endforeach; ?>
<form method="post" class="card">
    <label>Name <input type="text" name="name" value="<?= e($name) ?>" required></label>
    <label>Email <input type="email" name="email" value="<?= e($email) ?>" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button>Register</button>
</form>
<?php require '../includes/footer.php'; ?>
