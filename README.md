# 🎓 SpeakUp English — Final Project (Checkpoint 3)

Platform kursus bahasa Inggris online dengan fitur CRUD penuh, autentikasi berbasis role, dan pola arsitektur MVC.

---

## 📋 Deskripsi Proyek

SpeakUp English adalah website platform kursus bahasa Inggris yang dibangun sebagai Final Project mata kuliah Pemrograman Web. Website ini mendukung fitur Create, Read, Update, dan Delete (CRUD) secara penuh dengan sistem autentikasi admin dan user.

**Tema:** Platform Kursus Online  
**Checkpoint:** 3 (Final Project)

---

## ✅ Fitur yang Diimplementasikan

### Autentikasi & Role Management
- **Admin Login** — akses penuh ke dashboard dan semua fitur CRUD
- **User Register & Login** — pengguna bisa membuat akun dan login
- **Role Management** — admin dan user memiliki hak akses berbeda
- **CSRF Protection** — setiap form dilindungi token CSRF
- **Password Hashing** — password di-hash menggunakan bcrypt (cost 12)
- **Rate Limiting Login** — maksimal 5 percobaan gagal per 15 menit per IP

### CRUD Kursus (Admin)
| Operasi | Endpoint |
|---------|----------|
| Create  | `admin/courses.php?action=create` |
| Read    | `admin/courses.php`, halaman publik `kursus.php` |
| Update  | `admin/courses.php?action=edit&id=N` |
| Delete  | POST ke `admin/courses.php` (dengan modal konfirmasi) |

### Fitur Admin Panel
- 📊 **Dashboard** dengan 5 stat card (kursus, siswa, pendaftaran, akun user, pesan kontak)
- 📚 **Kelola Kursus** — CRUD lengkap dengan auto-generate slug
- 👥 **Daftar User** — lihat semua akun dan hapus user
- 📋 **Data Pendaftaran** — lihat & hapus data pendaftaran masuk
- ✉️ **Pesan Kontak** — lihat, tandai sudah dibaca, dan hapus pesan dari pengunjung
- 🔍 **Real-time Search** di setiap tabel admin
- 📄 **Pagination** di semua tabel (10 item per halaman)

### Fitur User
- 📋 **Riwayat Pendaftaran** (`riwayat.php`) — user yang login bisa melihat kursus yang pernah didaftarkan
- 🔐 Session autentikasi menyimpan `user_id` dan `user_username`
- Pendaftaran kursus otomatis tertaut ke akun user jika sedang login

### Halaman Publik (Guest & User)
- `index.php` — beranda dengan kursus unggulan & statistik dinamis
- `kursus.php` — daftar semua kursus dengan filter level & search real-time
- `detail.php` — halaman detail kursus dengan rekomendasi
- `daftar.php` — form pendaftaran kursus (validasi real-time + simpan draft)
- `tentang.php` — halaman tentang kami
- `kontak.php` — form kontak (pesan tersimpan ke database)

---

## 🏗️ Arsitektur MVC

```
MyWebsiteEnglishCourse/
│
├── admin/                    ← Router (entry point) tiap halaman admin
│   ├── dashboard.php
│   ├── courses.php
│   ├── users.php
│   ├── registrations.php
│   └── kontak.php            ← (baru) Pesan kontak
│
├── app/
│   ├── controllers/
│   │   ├── AdminController.php   ← Logika CRUD semua fitur admin
│   │   └── AuthController.php    ← Login, register, logout, rate limiting
│   │
│   ├── models/
│   │   ├── CourseModel.php       ← Query tabel courses
│   │   ├── UserModel.php         ← Query tabel users
│   │   ├── RegistrationModel.php ← Query tabel pendaftaran
│   │   └── KontakModel.php       ← (baru) Query tabel pesan_kontak
│   │
│   └── views/
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── courses/          ← index, create, edit, _form
│       │   ├── users/            ← index
│       │   ├── registrations/    ← index
│       │   └── kontak/           ← (baru) index
│       │
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       │
│       └── layouts/
│           ├── admin_nav.php
│           └── flash.php
│
├── api/                      ← REST API endpoints
│   ├── courses.php           ← GET kursus, POST pendaftaran
│   └── kontak.php            ← POST pesan kontak
│
├── php/
│   └── config.php            ← Database, helper, rate limiting
│
├── css/                      ← Stylesheet per halaman
├── js/                       ← JavaScript per halaman
├── database.sql              ← Schema + seed data
└── README.md
```

---

## 🗄️ Struktur Database

| Tabel | Keterangan |
|-------|------------|
| `users` | Akun admin dan user (password bcrypt) |
| `courses` | Data kursus dengan slug, level, harga, dsb |
| `pendaftaran` | Data pendaftaran kursus (FK ke courses & users) |
| `pesan_kontak` | Pesan dari form kontak (is_read flag) |
| `login_attempts` | Catatan percobaan login untuk rate limiting |

---

## 🚀 Cara Menjalankan

### Persyaratan
- PHP 8.0+
- MySQL / MariaDB
- Web server (Apache / XAMPP / Laragon)

### Langkah Setup

1. **Clone / ekstrak** project ke folder web server (misal: `htdocs/`)

2. **Import database**
   ```sql
   -- Di phpMyAdmin atau MySQL CLI:
   SOURCE /path/to/database.sql;
   ```

3. **Konfigurasi database** di `php/config.php`
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');      // sesuaikan
   define('DB_PASS', '');          // sesuaikan
   define('DB_NAME', 'speakup_english');
   ```

4. **Akses website** di browser:
   ```
   http://localhost/MyWebsiteEnglishCourse/
   ```

---

## 🔐 Akun Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |

> ⚠️ Ganti password admin setelah deploy ke production.

---

## 💡 Eksplorasi Nilai Tambah

### ✅ MVC (Model-View-Controller)
Arsitektur MVC diterapkan secara penuh:
- **Model** — class PHP di `app/models/` yang menangani query database
- **View** — template HTML di `app/views/` yang hanya menampilkan data
- **Controller** — class di `app/controllers/` yang menjadi jembatan
- Router tipis di `admin/*.php` meneruskan request ke controller yang sesuai

### ✅ Clean Code
- Nama variabel dan fungsi deskriptif (`getByUserId`, `countUnread`, `formatRupiah`)
- Setiap fungsi fokus pada satu tugas (Single Responsibility)
- Struktur folder rapi, dipisah antara admin, public, API, dan aset
- Konsistensi gaya penulisan di seluruh file

### 🌐 Hosting 
Project siap di-deploy ke:
InfinityFree** (PHP + MySQL gratis)


---

## 🔒 Keamanan yang Diterapkan

| Fitur | Implementasi |
|-------|-------------|
| SQL Injection | PDO Prepared Statements |
| XSS | `htmlspecialchars()` via fungsi `e()` |
| CSRF | Token per-session di setiap form POST |
| Brute Force | Rate limiting: maks 5 percobaan/15 menit/IP |
| Password | bcrypt hash dengan cost 12 |
| Session | `session_regenerate_id(true)` setelah login |

---

## 📊 Kriteria Penilaian (Final Project)

| Kriteria | Bobot | Status |
|----------|-------|--------|
| Autentikasi (Login Admin + Session) | 30% | ✅ |
| CRUD Penuh (Create, Read, Update, Delete) | 30% | ✅ |
| UX & Responsivitas | 30% | ✅ |
| Interaktivitas JS (search, modal, validasi) | 10% | ✅ |
| **Nilai Tambah MVC** | +5 poin | ✅ |
| **Nilai Tambah Clean Code** | +5 poin | ✅ |

---

