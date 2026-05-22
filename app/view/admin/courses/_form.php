<!-- app/views/admin/courses/_form.php -->
<!-- $course diisi saat edit, kosong saat create -->
<?php
$isEdit      = isset($course);
$formAction  = 'courses.php';
$pageTitle   = $isEdit ? 'Edit Kursus' : 'Tambah Kursus';
$btnLabel    = $isEdit ? 'Simpan Perubahan' : 'Tambah Kursus';
$actionValue = $isEdit ? 'update' : 'store';

// Pre-fill values
$v = [
    'nama'              => $course['nama']              ?? '',
    'slug'              => $course['slug']              ?? '',
    'emoji'             => $course['emoji']             ?? '📚',
    'level'             => $course['level']             ?? 'Pemula',
    'durasi'            => $course['durasi']            ?? '',
    'materi'            => $course['materi']            ?? '',
    'rating'            => $course['rating']            ?? '4.5',
    'siswa'             => $course['siswa']             ?? '0',
    'harga'             => $course['harga']             ?? '',
    'deskripsi'         => $course['deskripsi']         ?? '',
    'deskripsi_panjang' => $course['deskripsi_panjang'] ?? '',
    'thumb_class'       => $course['thumb_class']       ?? 'c1',
];
$levels      = ['Pemula', 'Menengah', 'Lanjutan', 'Sertifikasi'];
$thumbClasses = ['c1', 'c2', 'c3', 'c4', 'c5', 'c6'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $pageTitle ?> — Admin SpeakUp</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-body">

  <?php require APP_PATH . '/views/layouts/admin_nav.php'; ?>

  <main class="admin-main">
    <div class="admin-topbar">
      <div>
        <div class="breadcrumb" style="margin-bottom:6px">
          <a href="courses.php">Kelola Kursus</a> / <?= $pageTitle ?>
        </div>
        <h1 class="admin-page-title"><?= $pageTitle ?></h1>
      </div>
      <a href="courses.php" class="btn-admin-secondary">← Kembali</a>
    </div>

    <?php require APP_PATH . '/views/layouts/flash.php'; ?>

    <form method="POST" action="<?= $formAction ?>" id="courseForm" novalidate>
      <input type="hidden" name="action" value="<?= $actionValue ?>">
      <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
      <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= $course['id'] ?>">
      <?php endif; ?>

      <div class="form-grid-2col">

        <!-- Nama -->
        <div class="form-group form-group--full">
          <label>Nama Kursus *</label>
          <input type="text" name="nama" value="<?= e($v['nama']) ?>"
                 placeholder="contoh: English for Beginners" required
                 oninput="autoSlug(this.value)"/>
        </div>

        <!-- Slug -->
        <div class="form-group form-group--full">
          <label>Slug URL <span class="label-hint">(kosongkan untuk auto-generate)</span></label>
          <input type="text" name="slug" id="slugField" value="<?= e($v['slug']) ?>"
                 placeholder="english-for-beginners"/>
          <div class="field-hint">Digunakan di URL: detail.php?slug=<strong id="slugPreview"><?= e($v['slug']) ?></strong></div>
        </div>

        <!-- Emoji -->
        <div class="form-group">
          <label>Emoji Kursus</label>
          <input type="text" name="emoji" value="<?= e($v['emoji']) ?>"
                 placeholder="📚" maxlength="5"/>
        </div>

        <!-- Warna Thumbnail -->
        <div class="form-group">
          <label>Warna Thumbnail</label>
          <div class="thumb-picker">
            <?php foreach ($thumbClasses as $tc): ?>
            <label class="thumb-option">
              <input type="radio" name="thumb_class" value="<?= $tc ?>"
                     <?= $v['thumb_class'] === $tc ? 'checked' : '' ?>>
              <span class="thumb-swatch course-thumb <?= $tc ?>"></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Level -->
        <div class="form-group">
          <label>Level *</label>
          <select name="level" required>
            <?php foreach ($levels as $lvl): ?>
            <option value="<?= $lvl ?>" <?= $v['level'] === $lvl ? 'selected' : '' ?>><?= $lvl ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Durasi -->
        <div class="form-group">
          <label>Durasi *</label>
          <input type="text" name="durasi" value="<?= e($v['durasi']) ?>"
                 placeholder="contoh: 8 Minggu" required/>
        </div>

        <!-- Jumlah Materi -->
        <div class="form-group">
          <label>Jumlah Materi *</label>
          <input type="number" name="materi" value="<?= e($v['materi']) ?>"
                 min="1" max="200" placeholder="24" required/>
        </div>

        <!-- Rating -->
        <div class="form-group">
          <label>Rating</label>
          <input type="number" name="rating" value="<?= e($v['rating']) ?>"
                 min="1" max="5" step="0.1" placeholder="4.5"/>
        </div>

        <!-- Harga -->
        <div class="form-group">
          <label>Harga (Rp) *</label>
          <input type="number" name="harga" value="<?= e($v['harga']) ?>"
                 min="0" placeholder="299000" required/>
        </div>

        <!-- Jumlah Siswa -->
        <div class="form-group">
          <label>Jumlah Siswa <span class="label-hint">(awal)</span></label>
          <input type="number" name="siswa" value="<?= e($v['siswa']) ?>"
                 min="0" placeholder="0"/>
        </div>

        <!-- Deskripsi Singkat -->
        <div class="form-group form-group--full">
          <label>Deskripsi Singkat *</label>
          <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat yang muncul di kartu kursus..." required><?= e($v['deskripsi']) ?></textarea>
        </div>

        <!-- Deskripsi Panjang -->
        <div class="form-group form-group--full">
          <label>Deskripsi Lengkap <span class="label-hint">(halaman detail)</span></label>
          <textarea name="deskripsi_panjang" rows="10"
                    placeholder="Tulis deskripsi lengkap di sini. Gunakan • di awal baris untuk bullet point."><?= e($v['deskripsi_panjang']) ?></textarea>
          <div class="field-hint">Awali baris dengan <code>•</code> untuk tampil sebagai bullet.</div>
        </div>

      </div><!-- end form-grid-2col -->

      <div class="form-actions">
        <a href="courses.php" class="btn-admin-secondary">Batal</a>
        <button type="submit" class="btn-admin-primary"><?= $btnLabel ?></button>
      </div>

    </form>
  </main>

  <script src="../js/admin.js"></script>
</body>
</html>
