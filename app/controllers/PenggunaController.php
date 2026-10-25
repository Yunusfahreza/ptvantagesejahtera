<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Core\ImporCsvSiswa;
use App\Models\Kelas;
use App\Models\User;

/**
 * F02: kelola pengguna, termasuk impor CSV siswa.
 */
final class PenggunaController extends Controller
{
    private const PER_HALAMAN = 15;
    private const PASSWORD_MIN = 8;
    private const ROLE = ['admin', 'guru', 'siswa'];
    private const IMPOR_MAKS_BYTE = 2 * 1024 * 1024;

    public function index(): void
    {
        $model   = new User();
        $halaman = max(1, query_int('hal', 1));

        $cari    = query('q');
        $role    = query('role');
        $kelasId = query_int('kelas');
        $status  = query('status');

        $hasil = $model->daftar($cari, $role, $kelasId > 0 ? $kelasId : null, $status, $halaman, self::PER_HALAMAN);

        $this->render('admin/pengguna/index', [
            'judul'        => 'Pengguna',
            'data'         => $hasil['data'],
            'total'        => $hasil['total'],
            'halaman'      => $halaman,
            'per_halaman'  => self::PER_HALAMAN,
            'total_halaman'=> max(1, (int) ceil($hasil['total'] / self::PER_HALAMAN)),
            'kelas'        => (new Kelas())->daftarRingkas(),
            'filter'       => ['q' => $cari, 'role' => $role, 'kelas' => $kelasId, 'status' => $status],
        ]);
    }

    public function form(?string $id = null): void
    {
        $model = new User();
        $user  = null;

        if ($id !== null) {
            $user = $model->cariDenganId($this->idWajib($id));

            if ($user === null) {
                throw HttpException::tidakAda('Pengguna tidak ditemukan.');
            }
        }

        $this->render('admin/pengguna/form', [
            'judul' => $user === null ? 'Tambah Pengguna' : 'Ubah Pengguna',
            'user'  => $user,
            'kelas' => (new Kelas())->daftarRingkas(),
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $id       = (int) post('id', '0');
        $nama     = post('nama');
        $username = mb_strtolower(post('username'));
        $role     = post('role');
        $kelasId  = (int) post('kelas_id', '0');
        $password = (string) ($_POST['password'] ?? '');

        $model = new User();
        $lama  = $id > 0 ? $model->cariDenganId($id) : null;

        if ($id > 0 && $lama === null) {
            throw HttpException::tidakAda('Pengguna tidak ditemukan.');
        }

        // Siswa wajib punya kelas; admin dan guru tidak boleh punya kelas.
        $kelasFinal = $role === 'siswa' ? ($kelasId > 0 ? $kelasId : null) : null;

        $error = match (true) {
            $nama === ''
                => 'Nama wajib diisi.',
            mb_strlen($nama) > 100
                => 'Nama maksimal 100 karakter.',
            preg_match('#^[a-z0-9._-]{3,50}$#', $username) !== 1
                => 'Username 3 sampai 50 karakter, hanya huruf kecil, angka, titik, garis bawah, atau strip.',
            !in_array($role, self::ROLE, true)
                => 'Peran tidak valid.',
            $role === 'siswa' && $kelasFinal === null
                => 'Siswa wajib ditempatkan di satu kelas.',
            $kelasFinal !== null && (new Kelas())->cariDenganId($kelasFinal) === null
                => 'Kelas tidak valid.',
            $model->usernameDipakai($username, $id > 0 ? $id : null)
                => 'Username sudah dipakai.',
            $id === 0 && mb_strlen($password) < self::PASSWORD_MIN
                => 'Password minimal ' . self::PASSWORD_MIN . ' karakter.',
            $id > 0 && $password !== '' && mb_strlen($password) < self::PASSWORD_MIN
                => 'Password baru minimal ' . self::PASSWORD_MIN . ' karakter.',
            $this->ubahPeranDiblokir($lama, $role, $model)
                => 'Guru ini masih terdaftar sebagai pengampu. Lepas penugasannya sebelum mengubah peran.',
            default => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/admin/pengguna/form/' . $id : '/admin/pengguna/form');
        }

        if ($id > 0) {
            $model->ubah($id, $nama, $username, $role, $kelasFinal);

            if ($password !== '') {
                $model->ubahPassword($id, password_hash($password, PASSWORD_DEFAULT));
            }

            Flash::sukses('Data pengguna berhasil diubah.');
        } else {
            $model->buat($nama, $username, password_hash($password, PASSWORD_DEFAULT), $role, $kelasFinal);
            Flash::sukses('Pengguna berhasil ditambahkan.');
        }

        redirect('/admin/pengguna');
    }

    /**
     * Guru yang masih memegang pengampu tidak boleh diubah perannya,
     * karena seluruh konten menggantung pada pengampu tersebut.
     */
    private function ubahPeranDiblokir(?array $lama, string $roleBaru, User $model): bool
    {
        if ($lama === null || $lama['role'] !== 'guru' || $roleBaru === 'guru') {
            return false;
        }

        return $model->jumlahPengampu((int) $lama['id']) > 0;
    }

    public function ubahStatus(): void
    {
        $this->wajibPost();

        $id     = (int) post('id', '0');
        $status = post('status');
        $model  = new User();

        if ($id <= 0 || $model->cariDenganId($id) === null) {
            Flash::error('Pengguna tidak ditemukan.');
            redirect('/admin/pengguna');
        }

        if (!in_array($status, ['aktif', 'nonaktif'], true)) {
            Flash::error('Status tidak valid.');
            redirect('/admin/pengguna');
        }

        // Admin tidak boleh menonaktifkan akunnya sendiri.
        if ($id === (int) \App\Core\Auth::id() && $status === 'nonaktif') {
            Flash::error('Anda tidak bisa menonaktifkan akun Anda sendiri.');
            redirect('/admin/pengguna');
        }

        $model->ubahStatus($id, $status);
        Flash::sukses('Status pengguna diubah menjadi ' . $status . '.');
        redirect('/admin/pengguna');
    }

    public function resetPassword(): void
    {
        $this->wajibPost();

        $id       = (int) post('id', '0');
        $password = (string) ($_POST['password'] ?? '');
        $model    = new User();

        if ($id <= 0 || $model->cariDenganId($id) === null) {
            Flash::error('Pengguna tidak ditemukan.');
            redirect('/admin/pengguna');
        }

        if (mb_strlen($password) < self::PASSWORD_MIN) {
            Flash::error('Password minimal ' . self::PASSWORD_MIN . ' karakter.');
            redirect('/admin/pengguna');
        }

        $model->ubahPassword($id, password_hash($password, PASSWORD_DEFAULT));
        Flash::sukses('Password pengguna berhasil direset.');
        redirect('/admin/pengguna');
    }

    // --- Impor CSV (F02) -------------------------------------------------

    public function formImpor(): void
    {
        $this->render('admin/pengguna/impor', [
            'judul' => 'Impor Siswa dari CSV',
            'kelas' => (new Kelas())->daftarTahunAktif(),
        ]);
    }

    public function impor(): void
    {
        $this->wajibPost();

        $berkas = $_FILES['berkas'] ?? null;

        if (!is_array($berkas) || ($berkas['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            Flash::error($this->pesanGagalUnggah($berkas['error'] ?? UPLOAD_ERR_NO_FILE));
            redirect('/admin/pengguna/impor');
        }

        if (!is_uploaded_file((string) $berkas['tmp_name'])) {
            throw HttpException::terlarang('Berkas unggahan tidak sah.');
        }

        $ekstensi = strtolower(pathinfo((string) $berkas['name'], PATHINFO_EXTENSION));

        if (!in_array($ekstensi, ['csv', 'txt'], true)) {
            Flash::error('Berkas harus berekstensi .csv.');
            redirect('/admin/pengguna/impor');
        }

        if ((int) $berkas['size'] > self::IMPOR_MAKS_BYTE) {
            Flash::error('Berkas CSV maksimal ' . ukuran_file(self::IMPOR_MAKS_BYTE) . '.');
            redirect('/admin/pengguna/impor');
        }

        $hasil = (new ImporCsvSiswa())->dariBerkas((string) $berkas['tmp_name']);

        $this->render('admin/pengguna/impor', [
            'judul' => 'Hasil Impor Siswa',
            'kelas' => (new Kelas())->daftarTahunAktif(),
            'hasil' => $hasil,
        ]);
    }

    /**
     * Berkas contoh supaya admin tahu format yang diharapkan.
     */
    public function contohCsv(): void
    {
        $kelas = (new Kelas())->daftarTahunAktif();
        $nama  = $kelas === [] ? 'X-1' : (string) $kelas[0]['nama'];

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="contoh-impor-siswa.csv"');

        $keluaran = fopen('php://output', 'wb');

        // BOM supaya Excel membaca UTF-8 dengan benar.
        fwrite($keluaran, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($keluaran, ['nama', 'username', 'password', 'kelas']);
        fputcsv($keluaran, ['Nama Siswa Satu', 'siswa.satu', 'rahasia123', $nama]);
        fputcsv($keluaran, ['Nama Siswa Dua', 'siswa.dua', 'rahasia123', $nama]);
        fclose($keluaran);
    }

    private function pesanGagalUnggah(int $kode): string
    {
        return match ($kode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Berkas terlalu besar.',
            UPLOAD_ERR_PARTIAL                        => 'Berkas hanya terunggah sebagian. Coba lagi.',
            UPLOAD_ERR_NO_FILE                        => 'Pilih berkas CSV terlebih dahulu.',
            UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE => 'Server gagal menyimpan berkas sementara.',
            default                                   => 'Berkas gagal diunggah.',
        };
    }
}
