<?php
// ============================================================
// app/controllers/AdminController.php
// Dashboard, Kursus CRUD, User management, Pendaftaran, Kontak
// ============================================================

require_once APP_PATH . '/models/CourseModel.php';
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/models/RegistrationModel.php';
require_once APP_PATH . '/models/KontakModel.php';

define('ADMIN_PER_PAGE', 10);

class AdminController
{
    private CourseModel       $courseModel;
    private UserModel         $userModel;
    private RegistrationModel $registrationModel;
    private KontakModel       $kontakModel;

    public function __construct()
    {
        requireAdmin();
        $this->courseModel       = new CourseModel();
        $this->userModel         = new UserModel();
        $this->registrationModel = new RegistrationModel();
        $this->kontakModel       = new KontakModel();
    }

    // ---- Dashboard ----
    public function dashboard(): void
    {
        $stats = [
            'total_kursus'      => $this->courseModel->count(),
            'total_users'       => $this->userModel->count(),
            'total_pendaftaran' => $this->registrationModel->count(),
            'total_pesan'       => $this->kontakModel->count(),
            'pesan_unread'      => $this->kontakModel->countUnread(),
            'course_stats'      => $this->courseModel->getStats(),
        ];
        $recent_registrations = $this->registrationModel->getAll(5, 0);
        $flash = getFlash();
        require APP_PATH . '/views/admin/dashboard.php';
    }

    // ---- KURSUS: Index (dengan pagination) ----
    public function courseIndex(): void
    {
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $offset  = ($page - 1) * ADMIN_PER_PAGE;
        $total   = $this->courseModel->count();
        $courses = $this->courseModel->getAll(ADMIN_PER_PAGE, $offset);
        $pages   = (int) ceil($total / ADMIN_PER_PAGE);
        $flash   = getFlash();
        require APP_PATH . '/views/admin/courses/index.php';
    }

    // ---- KURSUS: Create Form ----
    public function courseCreate(): void
    {
        $flash = getFlash();
        require APP_PATH . '/views/admin/courses/create.php';
    }

    // ---- KURSUS: Store ----
    public function courseStore(): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect('courses.php');
        }

        $data   = $this->extractCourseData($_POST);
        $errors = $this->validateCourseData($data);

        if ($errors) {
            setFlash('error', implode('<br>', $errors));
            redirect('courses.php?action=create');
        }

        // Auto-generate slug jika kosong
        if (empty($data['slug'])) {
            $data['slug'] = $this->courseModel->generateSlug($data['nama']);
        } elseif ($this->courseModel->slugExists($data['slug'])) {
            setFlash('error', 'Slug sudah digunakan.');
            redirect('courses.php?action=create');
        }

        $this->courseModel->create($data);
        setFlash('success', 'Kursus berhasil ditambahkan!');
        redirect('courses.php');
    }

    // ---- KURSUS: Edit Form ----
    public function courseEdit(int $id): void
    {
        $course = $this->courseModel->getById($id);
        if (!$course) {
            setFlash('error', 'Kursus tidak ditemukan.');
            redirect('courses.php');
        }
        $flash = getFlash();
        require APP_PATH . '/views/admin/courses/edit.php';
    }

    // ---- KURSUS: Update ----
    public function courseUpdate(int $id): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect('courses.php');
        }

        $course = $this->courseModel->getById($id);
        if (!$course) {
            setFlash('error', 'Kursus tidak ditemukan.');
            redirect('courses.php');
        }

        $data   = $this->extractCourseData($_POST);
        $errors = $this->validateCourseData($data);

        if ($errors) {
            setFlash('error', implode('<br>', $errors));
            redirect("courses.php?action=edit&id=$id");
        }

        if (empty($data['slug'])) {
            $data['slug'] = $this->courseModel->generateSlug($data['nama']);
        } elseif ($this->courseModel->slugExists($data['slug'], $id)) {
            setFlash('error', 'Slug sudah digunakan oleh kursus lain.');
            redirect("courses.php?action=edit&id=$id");
        }

        $this->courseModel->update($id, $data);
        setFlash('success', 'Kursus berhasil diperbarui!');
        redirect('courses.php');
    }

    // ---- KURSUS: Delete ----
    public function courseDelete(int $id): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect('courses.php');
        }

        $deleted = $this->courseModel->delete($id);
        setFlash(
            $deleted ? 'success' : 'error',
            $deleted ? 'Kursus berhasil dihapus.' : 'Kursus tidak ditemukan.'
        );
        redirect('courses.php');
    }

    // ---- USERS: Index (dengan pagination) ----
    public function userIndex(): void
    {
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * ADMIN_PER_PAGE;
        $total  = $this->userModel->count();
        $users  = $this->userModel->getAll(ADMIN_PER_PAGE, $offset);
        $pages  = (int) ceil($total / ADMIN_PER_PAGE);
        $flash  = getFlash();
        require APP_PATH . '/views/admin/users/index.php';
    }

    // ---- USERS: Delete ----
    public function userDelete(int $id): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect('users.php');
        }

        $deleted = $this->userModel->delete($id);
        setFlash(
            $deleted ? 'success' : 'error',
            $deleted ? 'User berhasil dihapus.' : 'Tidak dapat menghapus user ini.'
        );
        redirect('users.php');
    }

    // ---- PENDAFTARAN: Index (dengan pagination) ----
    public function registrationIndex(): void
    {
        $page          = max(1, (int) ($_GET['page'] ?? 1));
        $offset        = ($page - 1) * ADMIN_PER_PAGE;
        $total         = $this->registrationModel->count();
        $registrations = $this->registrationModel->getAll(ADMIN_PER_PAGE, $offset);
        $pages         = (int) ceil($total / ADMIN_PER_PAGE);
        $flash         = getFlash();
        require APP_PATH . '/views/admin/registrations/index.php';
    }

    // ---- PENDAFTARAN: Delete ----
    public function registrationDelete(int $id): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect('registrations.php');
        }

        $deleted = $this->registrationModel->delete($id);
        setFlash(
            $deleted ? 'success' : 'error',
            $deleted ? 'Data pendaftaran dihapus.' : 'Data tidak ditemukan.'
        );
        redirect('registrations.php');
    }

    // ---- KONTAK: Index (dengan pagination) ----
    public function kontakIndex(): void
    {
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * ADMIN_PER_PAGE;
        $total  = $this->kontakModel->count();
        $unread = $this->kontakModel->countUnread();
        $pesans = $this->kontakModel->getAll(ADMIN_PER_PAGE, $offset);
        $pages  = (int) ceil($total / ADMIN_PER_PAGE);
        $flash  = getFlash();
        require APP_PATH . '/views/admin/kontak/index.php';
    }

    // ---- KONTAK: Mark Read ----
    public function kontakRead(int $id): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect('kontak.php');
        }
        $this->kontakModel->markRead($id);
        setFlash('success', 'Pesan ditandai sudah dibaca.');
        redirect('kontak.php');
    }

    // ---- KONTAK: Delete ----
    public function kontakDelete(int $id): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token tidak valid.');
            redirect('kontak.php');
        }

        $deleted = $this->kontakModel->delete($id);
        setFlash(
            $deleted ? 'success' : 'error',
            $deleted ? 'Pesan berhasil dihapus.' : 'Pesan tidak ditemukan.'
        );
        redirect('kontak.php');
    }

    // ---- Private Helpers ----
    private function extractCourseData(array $post): array
    {
        return [
            'slug'              => trim($post['slug'] ?? ''),
            'emoji'             => trim($post['emoji'] ?? '📚'),
            'nama'              => trim($post['nama'] ?? ''),
            'level'             => trim($post['level'] ?? ''),
            'durasi'            => trim($post['durasi'] ?? ''),
            'materi'            => $post['materi'] ?? 0,
            'rating'            => $post['rating'] ?? 4.5,
            'siswa'             => $post['siswa'] ?? 0,
            'harga'             => preg_replace('/[^0-9]/', '', $post['harga'] ?? '0'),
            'deskripsi'         => trim($post['deskripsi'] ?? ''),
            'deskripsi_panjang' => trim($post['deskripsi_panjang'] ?? ''),
            'thumb_class'       => $post['thumb_class'] ?? 'c1',
        ];
    }

    private function validateCourseData(array $data): array
    {
        $errors = [];
        $levels = ['Pemula', 'Menengah', 'Lanjutan', 'Sertifikasi'];

        if (empty($data['nama']))                     $errors[] = 'Nama kursus wajib diisi.';
        if (!in_array($data['level'], $levels, true)) $errors[] = 'Level tidak valid.';
        if (empty($data['durasi']))                   $errors[] = 'Durasi wajib diisi.';
        if ((int) $data['materi'] < 1)                $errors[] = 'Jumlah materi minimal 1.';
        if ((int) $data['harga'] < 0)                 $errors[] = 'Harga tidak valid.';
        if (empty($data['deskripsi']))                $errors[] = 'Deskripsi singkat wajib diisi.';

        return $errors;
    }
}
