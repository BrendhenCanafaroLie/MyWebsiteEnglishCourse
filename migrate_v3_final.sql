-- ============================================================
-- migrate_v3_final.sql
-- Jalankan file ini jika database sudah ada dari versi sebelumnya
-- (tanpa perlu drop/recreate ulang dari awal)
-- ============================================================

USE speakup_english;

-- ---- 1. Tambah tabel login_attempts (rate limiting) ----
CREATE TABLE IF NOT EXISTS login_attempts (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  ip           VARCHAR(45) NOT NULL,
  attempts     INT NOT NULL DEFAULT 1,
  last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_ip (ip),
  INDEX idx_ip (ip)
) ENGINE=InnoDB;

-- ---- 2. Tambah kolom is_read ke pesan_kontak (jika belum ada) ----
ALTER TABLE pesan_kontak
  ADD COLUMN IF NOT EXISTS is_read TINYINT(1) NOT NULL DEFAULT 0;

-- ---- 3. Tambah kolom user_id ke pendaftaran (jika belum ada) ----
ALTER TABLE pendaftaran
  ADD COLUMN IF NOT EXISTS user_id INT NULL AFTER id;

-- Tambah FK user_id → users (aman, hanya tambah jika belum ada)
-- Lewati bagian ini jika muncul error "Duplicate key name"
ALTER TABLE pendaftaran
  ADD CONSTRAINT fk_pendaftaran_user
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;
