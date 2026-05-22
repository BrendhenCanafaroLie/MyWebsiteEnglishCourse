<?php
// ============================================================
// app/models/CourseModel.php
// ============================================================

class CourseModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses ORDER BY id ASC LIMIT ? OFFSET ?');
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getPreview(int $limit = 3): array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses ORDER BY siswa DESC LIMIT ?');
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getRecommendations(int $excludeId, int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, slug, emoji, nama, level, harga, rating, thumb_class
             FROM courses WHERE id != ? ORDER BY siswa DESC LIMIT ?'
        );
        $stmt->execute([$excludeId, $limit]);
        return $stmt->fetchAll();
    }

    public function search(string $query = '', string $level = ''): array
    {
        $where  = [];
        $params = [];

        if ($level !== '' && $level !== 'Semua') {
            $where[]  = 'level = ?';
            $params[] = $level;
        }
        if ($query !== '') {
            $where[]  = '(nama LIKE ? OR deskripsi LIKE ?)';
            $params[] = "%$query%";
            $params[] = "%$query%";
        }

        $sql = 'SELECT id, slug, emoji, nama, level, durasi, materi, rating, siswa, harga, deskripsi, thumb_class
                FROM courses';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY id ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getStats(): array
    {
        return $this->db->query(
            'SELECT SUM(siswa) AS total_siswa, COUNT(*) AS total_kursus, AVG(rating) AS avg_rating
             FROM courses'
        )->fetch();
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM courses')->fetchColumn();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO courses
             (slug, emoji, nama, level, durasi, materi, rating, siswa, harga, deskripsi, deskripsi_panjang, thumb_class)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['slug'],
            $data['emoji'],
            $data['nama'],
            $data['level'],
            $data['durasi'],
            (int) $data['materi'],
            (float) $data['rating'],
            (int) ($data['siswa'] ?? 0),
            (int) $data['harga'],
            $data['deskripsi'],
            $data['deskripsi_panjang'] ?? '',
            $data['thumb_class'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE courses SET
             slug = ?, emoji = ?, nama = ?, level = ?, durasi = ?, materi = ?,
             rating = ?, siswa = ?, harga = ?, deskripsi = ?, deskripsi_panjang = ?, thumb_class = ?
             WHERE id = ?'
        );
        $stmt->execute([
            $data['slug'],
            $data['emoji'],
            $data['nama'],
            $data['level'],
            $data['durasi'],
            (int) $data['materi'],
            (float) $data['rating'],
            (int) ($data['siswa'] ?? 0),
            (int) $data['harga'],
            $data['deskripsi'],
            $data['deskripsi_panjang'] ?? '',
            $data['thumb_class'],
            $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function slugExists(string $slug, int $excludeId = 0): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM courses WHERE slug = ? AND id != ? LIMIT 1'
        );
        $stmt->execute([$slug, $excludeId]);
        return (bool) $stmt->fetchColumn();
    }

    public function generateSlug(string $nama): string
    {
        $slug = strtolower(trim($nama));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');

        $base  = $slug;
        $count = 1;
        while ($this->slugExists($slug)) {
            $slug = $base . '-' . $count++;
        }
        return $slug;
    }
}
