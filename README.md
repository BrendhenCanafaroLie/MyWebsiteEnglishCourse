# MyWebsiteEnglishCourse
Projek Tugas Study Club FullStack (Sistem Informasi)
Kelompok 9:
- Brendhen Canafaro Lie
- Mukhammad Ismul Azam Atmoko

# SpeakUp English — Website Kursus Bahasa Inggris Online

> Website statis multi-halaman untuk platform kursus bahasa Inggris **SpeakUp English**, dibangun menggunakan HTML, CSS, dan JavaScript murni tanpa framework.

---

## 🖼️ Preview

### Halaman Utama (index.html)
![Homepage Preview](https://via.placeholder.com/900x500/0a1628/00b4d8?text=SpeakUp+Homepage+Preview)
> Hero section dengan statistik, fitur unggulan, dan preview kursus

### Halaman Kursus (kursus.html)
![Kursus Preview](https://via.placeholder.com/900x400/0d1f35/f4a261?text=Halaman+Kursus+Preview)
> 6 program kursus dengan harga, rating, dan tombol pendaftaran

### Halaman Tentang (tentang.html)
![Tentang Preview](https://via.placeholder.com/900x400/112240/90e0ef?text=Halaman+Tentang+Kami)
> Profil lembaga, tim instruktur, dan pencapaian

### Halaman Kontak (kontak.html)
![Kontak Preview](https://via.placeholder.com/900x400/0a1628/ffffff?text=Halaman+Kontak)
> Form kontak dengan validasi nama, email, dan pesan

### Halaman Daftar (daftar.html)
![Daftar Preview](https://via.placeholder.com/900x400/112240/00b4d8?text=Halaman+Pendaftaran)
> Form pendaftaran dengan validasi email dan nomor WhatsApp Indonesia

---

## 📁 Struktur Proyek

```
MyWebsiteEnglishCourse/
├── index.html          # Halaman utama / beranda
├── kursus.html         # Daftar semua program kursus
├── tentang.html        # Tentang SpeakUp English
├── kontak.html         # Form kontak
├── daftar.html         # Form pendaftaran siswa baru
│
├── css/
│   ├── global.css      # Variabel, reset, navbar, footer (shared)
│   ├── index.css       # Style khusus halaman beranda
│   ├── kursus.css      # Style khusus halaman kursus
│   ├── tentang.css     # Style khusus halaman tentang
│   ├── kontak.css      # Style khusus halaman kontak
│   └── daftar.css      # Style khusus halaman pendaftaran
│
└── js/
    ├── main.js         # Navbar toggle & animasi (shared)
    ├── kursus.js       # Filter kursus berdasarkan level
    └── kontak.js       # Handler form kontak
```

---

## ✨ Fitur Website

### Halaman Beranda (index.html)
- **Hero section** dengan badge, judul, deskripsi, CTA button, dan statistik (2K+ siswa, 15+ instruktur, 98% kepuasan)
- **Features grid** — 4 keunggulan platform (kurikulum, online, instruktur, sertifikat)
- **Preview kursus** — 3 kursus unggulan (Beginner, Conversational, Business)
- **Why Choose Us section** dengan animasi scroll (IntersectionObserver)
- **CTA banner** ajakan daftar gratis
- Footer lengkap dengan navigasi & program

### Halaman Kursus (kursus.html)
- **Filter bar** berdasarkan level (Semua / Pemula / Menengah / Lanjutan / Sertifikasi)
- **Promo banner** diskon 30%
- **6 program kursus** lengkap dengan harga, durasi, jumlah materi, rating, dan jumlah siswa:
  | Program | Level | Durasi | Harga |
  |---------|-------|--------|-------|
  | English for Beginners | Pemula | 8 Minggu | Rp 299.000 |
  | Conversational English | Menengah | 10 Minggu | Rp 399.000 |
  | Business English | Lanjutan | 12 Minggu | Rp 499.000 |
  | IELTS Preparation | Sertifikasi | 16 Minggu | Rp 699.000 |
  | English Writing Skills | Menengah | 10 Minggu | Rp 349.000 |
  | Public Speaking in English | Lanjutan | 8 Minggu | Rp 449.000 |

### Halaman Kontak (kontak.html)
- Form kontak dengan field: Nama, Email, Subjek, dan Pesan
- Validasi JavaScript — semua field wajib diisi
- Pesan sukses otomatis hilang setelah 4 detik

### Halaman Daftar (daftar.html)
- Form pendaftaran: Nama, Email, No. WhatsApp, pilihan Kursus
- **Validasi email** menggunakan regex
- **Validasi nomor WhatsApp Indonesia** (format `08xx`, `628xx`, `+62xx`)
- Pesan sukses setelah submit berhasil

---

## 🎨 Desain & Teknologi

### Stack Teknologi
- **HTML5** — Semantic markup, multi-page architecture
- **CSS3** — Custom properties (CSS variables), Flexbox, Grid, Responsive
- **Vanilla JavaScript** — DOM manipulation, form validation, IntersectionObserver API
- **Google Fonts** — `Playfair Display` (heading) + `DM Sans` (body)

### Color Palette
| Nama | Hex | Penggunaan |
|------|-----|------------|
| Navy | `#0a1628` | Background utama |
| Navy 2 | `#112240` | Background section |
| Teal | `#00b4d8` | Aksen utama, CTA |
| Teal Light | `#90e0ef` | Hover states |
| Gold | `#f4a261` | Badge level menengah |
| Card BG | `#142338` | Background kartu |
| Gray | `#8892a4` | Teks sekunder |

### Responsif (Mobile-Friendly)
- **Hamburger menu** dengan slide-in dari kanan (`right: -100%` → `right: 0`)
- **Overlay gelap** saat mobile nav terbuka
- Breakpoint utama: `@media (max-width: 700px)`

---

## 🚀 Cara Menjalankan

Karena ini adalah website statis (tidak memerlukan server atau build tool), cukup:

### Opsi 1 — Buka langsung di browser
```bash
# Clone repository
git clone https://github.com/BrendhenCanafaroLie/MyWebsiteEnglishCourse.git

# Masuk ke folder
cd MyWebsiteEnglishCourse

# Buka di browser
open index.html       # macOS
start index.html      # Windows
xdg-open index.html   # Linux
```

### Opsi 2 — Live Server (VS Code)
1. Install ekstensi **Live Server** di VS Code
2. Klik kanan `index.html` → `Open with Live Server`
3. Website terbuka otomatis di `http://127.0.0.1:5500`

### Opsi 3 — Python HTTP Server
```bash
cd MyWebsiteEnglishCourse
python -m http.server 8000
# Buka http://localhost:8000
```

---

## 📄 Navigasi Halaman

| Halaman | File | Deskripsi |
|---------|------|-----------|
| Beranda | `index.html` | Landing page utama |
| Kursus | `kursus.html` | Daftar & filter semua kursus |
| Tentang Kami | `tentang.html` | Profil & tim SpeakUp |
| Kontak | `kontak.html` | Form hubungi kami |
| Daftar | `daftar.html` | Formulir pendaftaran siswa |

---

## 👤 Tentang Proyek

**SpeakUp English** adalah platform kursus bahasa Inggris online yang berlokasi di **Samarinda, Kalimantan Timur**.

Website ini dibuat sebagai proyek front-end untuk menampilkan layanan kursus bahasa Inggris secara profesional, dengan desain dark-mode modern bernuansa navy dan teal.

---

*© 2025 SpeakUp English. Dibuat dengan ❤ untuk pelajar Indonesia.*
