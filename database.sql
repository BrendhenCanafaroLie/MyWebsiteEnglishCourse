-- ============================================================
-- SpeakUp English — Database Schema & Seed Data
-- Checkpoint 2: Integrasi PHP & MySQL
-- ============================================================

CREATE DATABASE IF NOT EXISTS speakup_english CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE speakup_english;

-- ============================================================
-- TABLE: courses (Kursus)
-- ============================================================
DROP TABLE IF EXISTS pendaftaran;
DROP TABLE IF EXISTS pesan_kontak;
DROP TABLE IF EXISTS courses;

CREATE TABLE courses (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  slug       VARCHAR(100) NOT NULL UNIQUE,
  emoji      VARCHAR(10)  NOT NULL DEFAULT '📚',
  nama       VARCHAR(150) NOT NULL,
  level      ENUM('Pemula','Menengah','Lanjutan','Sertifikasi') NOT NULL,
  durasi     VARCHAR(50)  NOT NULL,
  materi     INT          NOT NULL DEFAULT 0,
  rating     DECIMAL(2,1) NOT NULL DEFAULT 4.5,
  siswa      INT          NOT NULL DEFAULT 0,
  harga      INT          NOT NULL,
  deskripsi  TEXT         NOT NULL,
  deskripsi_panjang TEXT,
  thumb_class VARCHAR(5)  NOT NULL DEFAULT 'c1',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO courses (slug, emoji, nama, level, durasi, materi, rating, siswa, harga, deskripsi, deskripsi_panjang, thumb_class) VALUES
(
  'english-for-beginners', '🔤', 'English for Beginners', 'Pemula', '8 Minggu', 24, 4.9, 452, 299000,
  'Pelajaran dasar bahasa Inggris mulai dari alfabet, kosakata, hingga kalimat sederhana untuk kehidupan sehari-hari.',
  'Program ini dirancang khusus untuk kamu yang benar-benar baru memulai belajar bahasa Inggris. Kamu akan belajar dari dasar: alfabet, pelafalan, kosakata sehari-hari, hingga membentuk kalimat sederhana. Dengan metode pembelajaran yang menyenangkan dan interaktif, kamu tidak perlu takut membuat kesalahan — justru itulah cara terbaik belajar!\n\nMateri meliputi:\n• Alfabet & pelafalan (phonics)\n• Kosakata sehari-hari (500+ kata)\n• Kalimat dasar: greetings, numbers, colors, family\n• Grammar dasar: to be, simple present tense\n• Latihan speaking & listening dengan audio native speaker\n• Kuis dan evaluasi mingguan',
  'c1'
),
(
  'conversational-english', '💬', 'Conversational English', 'Menengah', '10 Minggu', 30, 4.8, 387, 399000,
  'Latih kemampuan berbicara dengan simulasi percakapan nyata, role play, dan diskusi topik aktual.',
  'Kursus ini berfokus pada kemampuan berbicara (speaking) dan mendengarkan (listening) dalam bahasa Inggris sehari-hari. Kamu akan berlatih dengan topik-topik nyata yang relevan dengan kehidupan modern, seperti berbelanja, traveling, wawancara kerja, hingga diskusi opini.\n\nMateri meliputi:\n• Role play situasi nyata (airport, hotel, kantor, kafe)\n• Diskusi dan debat topik aktual\n• Idiom dan ekspresi percakapan informal\n• Mendengarkan aksen berbeda (American, British, Australian)\n• Latihan pronunciation & intonasi\n• Session live conversation dengan native speaker (2x per kursus)',
  'c2'
),
(
  'business-english', '📝', 'Business English', 'Lanjutan', '12 Minggu', 36, 4.9, 291, 499000,
  'Kuasai bahasa Inggris profesional: email bisnis, presentasi, negosiasi, dan rapat formal.',
  'Program Business English dirancang untuk para profesional, karyawan, dan wirausaha yang ingin meningkatkan kemampuan bahasa Inggris dalam konteks bisnis internasional. Materi mencakup komunikasi formal, penulisan dokumen bisnis, hingga presentasi di hadapan klien asing.\n\nMateri meliputi:\n• Penulisan email dan surat bisnis profesional\n• Teknik presentasi dalam bahasa Inggris\n• Bahasa negosiasi dan persuasi\n• Rapat dan konferensi (meeting etiquette)\n• Report writing & executive summary\n• Business vocabulary: finance, HR, marketing, operations\n• Simulasi job interview dalam bahasa Inggris',
  'c3'
),
(
  'ielts-preparation', '🎓', 'IELTS Preparation', 'Sertifikasi', '16 Minggu', 48, 4.9, 213, 699000,
  'Program intensif persiapan IELTS dengan latihan soal, simulasi ujian, dan strategi menjawab soal.',
  'Program persiapan IELTS kami telah membantu ratusan siswa meraih skor target mereka untuk keperluan studi ke luar negeri, imigrasi, atau karier internasional. Fokus pada keempat komponen IELTS: Listening, Reading, Writing, dan Speaking.\n\nMateri meliputi:\n• Pengenalan format IELTS Academic & General\n• Listening: strategi menjawab berbagai tipe soal\n• Reading: skimming, scanning, dan analisis teks\n• Writing Task 1: deskripsi grafik, diagram, proses\n• Writing Task 2: argumentative essay\n• Speaking: Part 1, 2, 3 dengan simulasi nyata\n• 5x simulasi ujian penuh (full mock test)\n• Feedback personal dari instruktur bersertifikat IELTS\n• Target skor: 6.0 – 8.0+',
  'c4'
),
(
  'english-writing-skills', '✍️', 'English Writing Skills', 'Menengah', '10 Minggu', 28, 4.7, 178, 349000,
  'Pelajari cara menulis esai, laporan, dan artikel dalam bahasa Inggris yang baik dan benar.',
  'Kursus ini ditujukan bagi mahasiswa, penulis konten, blogger, dan siapa pun yang ingin meningkatkan kemampuan menulis dalam bahasa Inggris. Kamu akan belajar menulis dengan struktur yang baik, kosakata yang kaya, dan gaya yang menarik.\n\nMateri meliputi:\n• Paragraf efektif: topic sentence, supporting ideas, conclusion\n• Essay writing: argumentative, descriptive, narrative\n• Academic writing untuk mahasiswa\n• Content writing & copywriting dasar\n• Grammar untuk tulisan: tenses, conjunctions, punctuation\n• Proofreading dan self-editing\n• Latihan menulis dengan feedback instruktur',
  'c5'
),
(
  'public-speaking-english', '🎤', 'Public Speaking in English', 'Lanjutan', '8 Minggu', 20, 4.8, 156, 449000,
  'Tingkatkan kepercayaan diri berbicara di depan umum dalam bahasa Inggris. Cocok untuk mahasiswa dan profesional.',
  'Takut berbicara di depan umum, apalagi dalam bahasa Inggris? Kursus ini adalah solusinya! Kamu akan belajar teknik public speaking profesional sambil meningkatkan kemampuan bahasa Inggrismu secara bersamaan.\n\nMateri meliputi:\n• Mengatasi rasa takut berbicara di depan umum\n• Struktur pidato dan presentasi yang efektif\n• Body language dan kontak mata (eye contact)\n• Teknik vocal: intonasi, volume, pace\n• Storytelling dalam bahasa Inggris\n• Impromptu speaking (berbicara spontan)\n• Simulasi pitching ide dan presentasi proyek\n• 2x sesi live performance dengan audience nyata',
  'c6'
);

-- ============================================================
-- TABLE: pendaftaran (Form Daftar)
-- ============================================================
CREATE TABLE pendaftaran (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nama       VARCHAR(150) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  whatsapp   VARCHAR(20)  NOT NULL,
  kursus_id  INT NOT NULL,
  tujuan     TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kursus_id) REFERENCES courses(id)
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: pesan_kontak (Form Kontak)
-- ============================================================
CREATE TABLE pesan_kontak (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nama       VARCHAR(150) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  whatsapp   VARCHAR(20),
  topik      VARCHAR(100),
  pesan      TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
