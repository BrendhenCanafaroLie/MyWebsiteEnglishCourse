<?php
// ============================================================
// app/models/KontakModel.php
// ============================================================

class KontakModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(int $limit = 15, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM pesan_kontak ORDER BY created_at DESC LIMIT ? OFFSET ?'
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM pesan_kontak WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM pesan_kontak')->fetchColumn();
    }

    public function countUnread(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM pesan_kontak WHERE is_read = 0')->fetchColumn();
    }

    public function markRead(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE pesan_kontak SET is_read = 1 WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM pesan_kontak WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
