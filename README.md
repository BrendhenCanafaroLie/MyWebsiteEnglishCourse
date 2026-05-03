# MyWebsiteEnglishCourse
Projek Tugas Study Club FullStack (Sistem Informasi)
Kelompok 9:
- Brendhen Canafaro Lie
- Mukhammad Ismul Azam Atmoko

# SpeakUp English — Website Kursus Bahasa Inggris Online

> Aplikasi web dinamis multi-halaman untuk platform kursus bahasa Inggris **SpeakUp English**, dibangun menggunakan **PHP, MySQL, JavaScript, HTML, dan CSS** — tanpa framework.

---

## 🚀 Checkpoint 2 — Dynamic Web Application

Website ini merupakan hasil upgrade dari Checkpoint 1 (statis) menjadi aplikasi web dinamis dengan integrasi **PHP & MySQL** di sisi server, serta fitur interaktif **JavaScript & LocalStorage** di sisi client.

### Yang Berubah dari Checkpoint 1:
- Semua data kursus yang sebelumnya ditulis langsung di HTML kini tersimpan di **database MySQL**
- PHP digunakan untuk **menampilkan konten dari database** (server-side rendering)
- Ditambahkan **halaman detail kursus** dengan routing berbasis slug (`detail.php?slug=...`)
- Ditambahkan **REST API endpoint** (`api/courses.php`, `api/kontak.php`)
- Fitur **pencarian real-time** dan **filter level** tanpa reload halaman
- Fitur **Simpan Favorit** menggunakan LocalStorage
- **Auto-save draft** form pendaftaran ke LocalStorage
- Animasi: scroll reveal, count-up statistik, mouse glow & tilt pada kartu
---

## 📁 Struktur Proyek

```
MyWebsiteEnglishCourse/
│
├── index.php           # Halaman utama / beranda (data dari DB)
├── kursus.php          # Daftar semua program kursus (dari DB)
├── detail.php          # Detail kursus by slug (?slug=nama-kursus)
├── tentang.php         # Tentang SpeakUp English (statistik dari DB)
├── kontak.php          # Halaman kontak
├── daftar.php          # Form pendaftaran (dropdown kursus dari DB)
├── database.sql        # Schema + seed data MySQL
│
├── php/
│   └── config.php      # Koneksi PDO, helper functions
│
├── api/
│   ├── courses.php     # REST API: GET kursus, POST pendaftaran
│   └── kontak.php      # REST API: POST pesan kontak
│
├── css/
│   ├── global.css      # Variabel, reset, navbar, footer, aurora effect
│   ├── index.css       # Style khusus halaman beranda
│   ├── kursus.css      # Style khusus halaman kursus + search + favorit
│   ├── tentang.css     # Style khusus halaman tentang
│   ├── kontak.css      # Style khusus halaman kontak
│   ├── daftar.css      # Style khusus halaman pendaftaran
│   └── detail.css      # Style khusus halaman detail kursus
│
└── js/
    ├── main.js         # Navbar toggle & animasi scroll (shared)
    ├── kursus.js       # Pencarian real-time + filter + LocalStorage favorit
    ├── detail.js       # Favorit toggle di halaman detail
    ├── daftar.js       # Validasi real-time + auto-save draft
    ├── kontak.js       # Submit form kontak via fetch API
    └── animations.js   # Scroll reveal, count-up, mouse glow, aurora
```

---

## ✨ Fitur Website

### 🗄️ Integrasi PHP & Database (Bobot 70%)
- Seluruh data kursus (nama, level, harga, deskripsi, rating, siswa) diambil dari MySQL
- Statistik beranda (total siswa, jumlah kursus, rata-rata rating) dihitung langsung dari DB
- Halaman detail kursus: `detail.php?slug=english-for-beginners`
- Form pendaftaran: data tersimpan ke tabel `pendaftaran` via REST API
- Form kontak: pesan tersimpan ke tabel `pesan_kontak` via REST API
- Dropdown kursus di halaman daftar diambil dinamis dari DB

### ⚡ DOM & Events JavaScript (Bobot 20%)
- **Pencarian real-time** — `input` event, filter kartu tanpa reload halaman
- **Filter level** — `click` event pada tombol filter (Semua / Pemula / Menengah / Lanjutan / Sertifikasi)
- **Validasi real-time** — `blur` & `input` event pada form pendaftaran
- **Tombol favorit** — `click` event, toggle simpan/hapus favorit

### 💾 LocalStorage (Bobot 10%)
- **Favorit kursus** — disimpan dengan key `speakup_favorites`, tampil di panel floating
- **Draft form daftar** — auto-save saat mengetik, key `speakup_daftar_draft`, terhapus otomatis setelah submit berhasil

---

## 🗃️ Database

### Tabel `courses`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | INT | Primary key |
| slug | VARCHAR | URL identifier unik (contoh: `english-for-beginners`) |
| nama | VARCHAR | Nama program kursus |
| level | ENUM | Pemula / Menengah / Lanjutan / Sertifikasi |
| durasi | VARCHAR | Durasi kursus (contoh: 8 Minggu) |
| materi | INT | Jumlah sesi materi |
| rating | DECIMAL | Rating kursus (1–5) |
| siswa | INT | Jumlah siswa terdaftar |
| harga | INT | Harga dalam Rupiah |
| deskripsi | TEXT | Deskripsi singkat |
| deskripsi_panjang | TEXT | Deskripsi lengkap untuk halaman detail |

### Tabel `pendaftaran`
Menyimpan data dari form pendaftaran: nama, email, whatsapp, kursus_id, tujuan.

### Tabel `pesan_kontak`
Menyimpan pesan dari form kontak: nama, email, whatsapp, topik, pesan.

### Data Seed (6 Kursus)
| Program | Level | Durasi | Harga |
|---------|-------|--------|-------|
| English for Beginners | Pemula | 8 Minggu | Rp 299.000 |
| Conversational English | Menengah | 10 Minggu | Rp 399.000 |
| Business English | Lanjutan | 12 Minggu | Rp 499.000 |
| IELTS Preparation | Sertifikasi | 16 Minggu | Rp 699.000 |
| English Writing Skills | Menengah | 10 Minggu | Rp 349.000 |
| Public Speaking in English | Lanjutan | 8 Minggu | Rp 449.000 |

---

## ⚙️ Cara Setup & Menjalankan

### Persyaratan
- PHP 8.0+
- MySQL 5.7+ / MariaDB
- Web server lokal: **XAMPP** atau **Laragon** (direkomendasikan)

### Langkah Instalasi

1. **Clone atau ekstrak** folder project ke:
   - XAMPP: `C:\xampp\htdocs\MyWebsiteEnglishCourse\`
   - Laragon: `C:\laragon\www\MyWebsiteEnglishCourse\`

2. **Import database** — buka phpMyAdmin (`http://localhost/phpmyadmin`):
   - Buat database baru bernama `speakup_english`
   - Import file `database.sql`

3. **Konfigurasi koneksi** — buka `php/config.php`:
   ```php
   define('DB_USER', 'root');   // sesuaikan
   define('DB_PASS', '');       // sesuaikan
   ```

4. **Jalankan** — buka browser:
   ```
   http://localhost/MyWebsiteEnglishCourse/
   ```

---

## 📄 Navigasi Halaman

| Halaman | File | Keterangan |
|---------|------|-----------|
| Beranda | `index.php` | Landing page, statistik & preview kursus dari DB |
| Kursus | `kursus.php` | Daftar & filter semua kursus dari DB |
| Detail Kursus | `detail.php?slug=...` | Detail lengkap per kursus berdasarkan slug |
| Tentang Kami | `tentang.php` | Profil, tim, misi — statistik dari DB |
| Kontak | `kontak.php` | Form kontak, pesan tersimpan ke DB |
| Daftar | `daftar.php` | Form pendaftaran, data tersimpan ke DB |

---

## 👤 Tentang Proyek

**SpeakUp English** adalah platform kursus bahasa Inggris online yang berlokasi di **Samarinda, Kalimantan Timur**.

Website ini dibuat sebagai proyek **Checkpoint 2** Study Club FullStack — mengupgrade website statis menjadi aplikasi web dinamis dengan PHP & MySQL, memenuhi kriteria penilaian: DOM & Events (20%), LocalStorage (10%), dan Integrasi PHP & Database (70%).