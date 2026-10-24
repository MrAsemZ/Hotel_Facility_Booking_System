<?php
class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $email, string $password): void
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function allCustomers(): array
    {
        return $this->db->query("SELECT * FROM users WHERE role = 'user' ORDER BY id DESC")->fetchAll();
    }

    public function countCustomers(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    }

    // Flip active / deactivated (admins are excluded)
    public function toggleActive(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE users SET is_active = 1 - is_active WHERE id = ? AND role = 'user'");
        $stmt->execute([$id]);
    }
}
