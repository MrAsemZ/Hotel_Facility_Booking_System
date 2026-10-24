<?php
// Registration, login, logout and access control (session based)
class Auth extends Model
{
    private static $user = false;   // cache for the current request
    private User $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new User();
    }

    // Logged-in user from the database, or null (guest / deactivated)
    public function user(): ?array
    {
        if (self::$user === false) {
            self::$user = null;
            if (!empty($_SESSION['user_id'])) {
                $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ? AND is_active = 1');
                $stmt->execute([$_SESSION['user_id']]);
                self::$user = $stmt->fetch() ?: null;
            }
        }
        return self::$user;
    }

    // Returns a list of error messages (empty = account created)
    public function register(string $name, string $email, string $password, string $confirm): array
    {
        $errors = [];

        if ($name === '' || strlen($name) > 100) {
            $errors[] = 'Name is required (max 100 characters).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }
        if (!$errors && $this->users->findByEmail($email)) {
            $errors[] = 'This email is already registered.';
        }
        if (!$errors) {
            $this->users->create($name, $email, $password);
        }
        return $errors;
    }

    // Returns an error message, or null when login succeeded
    public function login(string $email, string $password): ?string
    {
        $found = $this->users->findByEmail($email);

        if (!$found || !password_verify($password, $found['password'])) {
            return 'Wrong email or password.';
        }
        if (!$found['is_active']) {
            return 'Your account is deactivated.';
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $found['id'];
        return null;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public function requireLogin(): void
    {
        if (!$this->user()) {
            flash('Please log in first.', 'error');
            redirect(url('pages/login.php'));
        }
    }

    public function requireAdmin(): void
    {
        $this->requireLogin();
        if ($this->user()['role'] !== 'admin') {
            http_response_code(403);
            exit('403 - Admins only.');
        }
    }
}
