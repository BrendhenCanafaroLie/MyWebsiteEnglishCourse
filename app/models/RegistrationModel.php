<?php
// ============================================================
// app/models/RegistrationModel.php
// ============================================================

class RegistrationModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pendaftaran (user_id, nama, email, whatsapp, kursus_id, tujuan)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['user_id'] ?? null,
            $data['nama'],
            $data['email'],
            $data['whatsapp'],
            (int) $data['kursus_id'],
            $data['tujuan'] ?? '',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getAll(int $limit = 15, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.nama AS nama_kursus
             FROM pendaftaran p
             JOIN courses c ON c.id = p.kursus_id
             ORDER BY p.created_at DESC LIMIT ? OFFSET ?'
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM pendaftaran')->fetchColumn();
    }

    public function getByUserId(int $userId, int $limit = 15, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.nama AS nama_kursus, c.emoji, c.level, c.harga, c.thumb_class, c.slug
             FROM pendaftaran p
             JOIN courses c ON c.id = p.kursus_id
             WHERE p.user_id = ?
             ORDER BY p.created_at DESC LIMIT ? OFFSET ?'
        );
        $stmt->execute([$userId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function countByUserId(int $userId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM pendaftaran WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM pendaftaran WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
