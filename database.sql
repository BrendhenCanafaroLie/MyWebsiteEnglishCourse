-- ============================================================
-- SpeakUp English — Database Schema & Seed Data
-- Checkpoint 3: Full CRUD + Auth (Admin-managed)
-- ============================================================

CREATE DATABASE IF NOT EXISTS speakup_english CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE speakup_english;

-- ============================================================
-- DROP urutan FK-safe
-- ============================================================
DROP TABLE IF EXISTS pendaftaran;
DROP TABLE IF EXISTS pesan_kontak;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS login_attempts;
DROP TABLE IF EXISTS users;

-- ============================================================
-- TABLE: users
-- role: 'admin' | 'user'
-- ============================================================
CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(60)  NOT NULL UNIQUE,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,          -- bcrypt hash
  role       ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Admin default: password = "admin123"
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@speakupenglish.id', '$2y$12$3u6crw5DmWEK0wfrjVtC4.XD6qCfkPs.GM.hMn.1x2TEDfMIBMJlu', 'admin');

-- ============================================================
-- TABLE: login_attempts (rate limiting brute force)
-- ============================================================
CREATE TABLE login_attempts (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  ip           VARCHAR(45) NOT NULL,
  attempts     INT NOT NULL DEFAULT 1,
  last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_ip (ip)
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: courses
-- ============================================================
CREATE TABLE courses (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  slug              VARCHAR(100) NOT NULL UNIQUE,
  emoji             VARCHAR(10)  NOT NULL DEFAULT '📚',
  nama              VARCHAR(150) NOT NULL,
  level             ENUM('Pemula','Menengah','Lanjutan','Sertifikasi') NOT NULL,
  durasi            VARCHAR(50)  NOT NULL,
  materi            INT          NOT NULL DEFAULT 0,
  rating            DECIMAL(2,1) NOT NULL DEFAULT 4.5,
  siswa             INT          NOT NULL DEFAULT 0,
  harga             INT          NOT NULL,
  deskripsi         TEXT         NOT NULL,
  deskripsi_panjang TEXT,
  thumb_class       VARCHAR(5)   NOT NULL DEFAULT 'c1',
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO courses (slug, emoji, nama, level, durasi, materi, rating, siswa, harga, deskripsi, deskripsi_panjang, thumb_class) VALUES
(
  'english-for-beginners', '🔤', 'English for Beginners', 'Pemula', '8 Minggu', 24, 4.9, 452, 299000,
  'Pelajaran dasar bahasa Inggris mulai dari alfabet, kosakata, hingga kalimat sederhana untuk kehidupan sehari-hari.',
  'Program ini dirancang khusus untuk kamu yang benar-benar baru memulai belajar bahasa Inggris. Kamu akan belajar dari dasar: alfabet, pelafalan, kosakata sehari-hari, hingga membentuk kalimat sederhana.\n\nMateri meliputi:\n• Alfabet & pelafalan (phonics)\n• Kosakata sehari-hari (500+ kata)\n• Kalimat dasar: greetings, numbers, colors, family\n• Grammar dasar: to be, simple present tense\n• Latihan speaking & listening dengan audio native speaker\n• Kuis dan evaluasi mingguan',
  'c1'
),
(
  'conversational-english', '💬', 'Conversational English', 'Menengah', '10 Minggu', 30, 4.8, 387, 399000,
  'Latih kemampuan berbicara dengan simulasi percakapan nyata, role play, dan diskusi topik aktual.',
  'Kursus ini berfokus pada kemampuan berbicara dan mendengarkan dalam bahasa Inggris sehari-hari.\n\nMateri meliputi:\n• Role play situasi nyata (airport, hotel, kantor, kafe)\n• Diskusi dan debat topik aktual\n• Idiom dan ekspresi percakapan informal\n• Mendengarkan aksen berbeda (American, British, Australian)\n• Latihan pronunciation & intonasi\n• Session live conversation dengan native speaker (2x per kursus)',
  'c2'
),
(
  'business-english', '📝', 'Business English', 'Lanjutan', '12 Minggu', 36, 4.9, 291, 499000,
  'Kuasai bahasa Inggris profesional: email bisnis, presentasi, negosiasi, dan rapat formal.',
  'Program Business English dirancang untuk para profesional yang ingin meningkatkan kemampuan bahasa Inggris dalam konteks bisnis internasional.\n\nMateri meliputi:\n• Penulisan email dan surat bisnis profesional\n• Teknik presentasi dalam bahasa Inggris\n• Bahasa negosiasi dan persuasi\n• Rapat dan konferensi (meeting etiquette)\n• Report writing & executive summary\n• Simulasi job interview dalam bahasa Inggris',
  'c3'
),
(
  'ielts-preparation', '🎓', 'IELTS Preparation', 'Sertifikasi', '16 Minggu', 48, 4.9, 213, 699000,
  'Program intensif persiapan IELTS dengan latihan soal, simulasi ujian, dan strategi menjawab soal.',
  'Program persiapan IELTS kami telah membantu ratusan siswa meraih skor target mereka.\n\nMateri meliputi:\n• Pengenalan format IELTS Academic & General\n• Listening: strategi menjawab berbagai tipe soal\n• Reading: skimming, scanning, dan analisis teks\n• Writing Task 1 & 2\n• Speaking: Part 1, 2, 3 dengan simulasi nyata\n• 5x simulasi ujian penuh (full mock test)\n• Target skor: 6.0 – 8.0+',
  'c4'
),
(
  'english-writing-skills', '✍️', 'English Writing Skills', 'Menengah', '10 Minggu', 28, 4.7, 178, 349000,
  'Pelajari cara menulis esai, laporan, dan artikel dalam bahasa Inggris yang baik dan benar.',
  'Kursus ini ditujukan bagi mahasiswa, penulis konten, dan siapa pun yang ingin meningkatkan kemampuan menulis dalam bahasa Inggris.\n\nMateri meliputi:\n• Paragraf efektif: topic sentence, supporting ideas, conclusion\n• Essay writing: argumentative, descriptive, narrative\n• Academic writing untuk mahasiswa\n• Grammar untuk tulisan: tenses, conjunctions, punctuation\n• Proofreading dan self-editing\n• Latihan menulis dengan feedback instruktur',
  'c5'
),
(
  'public-speaking-english', '🎤', 'Public Speaking in English', 'Lanjutan', '8 Minggu', 20, 4.8, 156, 449000,
  'Tingkatkan kepercayaan diri berbicara di depan umum dalam bahasa Inggris. Cocok untuk mahasiswa dan profesional.',
  'Takut berbicara di depan umum, apalagi dalam bahasa Inggris? Kursus ini adalah solusinya!\n\nMateri meliputi:\n• Mengatasi rasa takut berbicara di depan umum\n• Struktur pidato dan presentasi yang efektif\n• Body language dan kontak mata\n• Teknik vocal: intonasi, volume, pace\n• Storytelling dalam bahasa Inggris\n• 2x sesi live performance dengan audience nyata',
  'c6'
);

-- ============================================================
-- TABLE: pendaftaran
-- ============================================================
CREATE TABLE pendaftaran (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NULL,
  nama       VARCHAR(150) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  whatsapp   VARCHAR(20)  NOT NULL,
  kursus_id  INT NOT NULL,
  tujuan     TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kursus_id) REFERENCES courses(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: pesan_kontak
-- ============================================================
CREATE TABLE pesan_kontak (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nama       VARCHAR(150) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  whatsapp   VARCHAR(20),
  topik      VARCHAR(100),
  pesan      TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
