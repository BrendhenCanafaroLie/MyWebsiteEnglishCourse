<?php
// ============================================================
// app/models/UserModel.php
// ============================================================

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, username, email, role, created_at FROM users WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $username, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, "user")'
        );
        $stmt->execute([$username, $email, $hash]);
        return (int) $this->db->lastInsertId();
    }

    public function getAll(int $limit = 15, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, username, email, role, created_at FROM users
             ORDER BY created_at DESC LIMIT ? OFFSET ?'
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function countRole(string $role): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE role = ?');
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        return (bool) $stmt->fetchColumn();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ? AND role != "admin"');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
