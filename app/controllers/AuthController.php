<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Models\User;

/**
 * F01: login, logout, profil, ganti password.
 */
final class AuthController extends Controller
{
    private const PANJANG_PASSWORD_MIN = 8;

    public function formLogin(): void
    {
        if (Auth::sudahLogin()) {
            redirect(Auth::dashboardPath());
        }

        $this->render('auth/login', [
            'judul'    => 'Masuk',
            'username' => '',
        ], 'layouts/kosong');
    }

    public function login(): void
    {
        $this->wajibPost();

        if (Auth::sudahLogin()) {
            redirect(Auth::dashboardPath());
        }

        $username = post('username');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->render('auth/login', [
                'judul'    => 'Masuk',
                'error'    => 'Username dan password wajib diisi.',
                'username' => $username,
            ], 'layouts/kosong');

            return;
        }

        $error = Auth::login($username, (string) $password);

        if ($error !== null) {
            $this->render('auth/login', [
                'judul'    => 'Masuk',
                'error'    => $error,
                'username' => $username,
            ], 'layouts/kosong');

            return;
        }

        // Kembalikan ke halaman yang tadi diminta, kalau ada dan masih internal.
        $tujuan = $_SESSION['_tujuan'] ?? null;
        unset($_SESSION['_tujuan']);

        Flash::sukses('Selamat datang, ' . (Auth::user()['nama'] ?? '') . '.');

        if (is_string($tujuan) && $this->tujuanInternal($tujuan)) {
            header('Location: ' . $tujuan, true, 302);
            exit;
        }

        redirect(Auth::dashboardPath());
    }

    public function logout(): void
    {
        $this->wajibPost();
        Auth::logout();

        Auth::mulaiSesi();
        Flash::info('Anda sudah keluar dari sistem.');

        redirect('/login');
    }

    public function profil(): void
    {
        $user = (new User())->cariDenganId((int) Auth::id());

        $this->render('auth/profil', [
            'judul' => 'Profil',
            'user'  => $user,
        ]);
    }

    public function updateProfil(): void
    {
        $this->wajibPost();

        $id           = (int) Auth::id();
        $nomorInduk   = post('nomor_induk');
        $tempatLahir  = post('tempat_lahir');
        $tanggalLahir = post('tanggal_lahir');
        $alamat       = post('alamat');
        $noHp         = post('no_hp');
        $noHpOrtu     = post('no_hp_ortu');

        $model = new User();
        $model->ubahProfilLengkap(
            $id,
            $nomorInduk !== '' ? $nomorInduk : null,
            $tempatLahir !== '' ? $tempatLahir : null,
            $tanggalLahir !== '' ? $tanggalLahir : null,
            $alamat !== '' ? $alamat : null,
            $noHp !== '' ? $noHp : null,
            $noHpOrtu !== '' ? $noHpOrtu : null
        );

        if (($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $uploader = new \App\Core\FileUploader(['jpg', 'jpeg', 'png', 'webp'], 5 * 1024 * 1024);
            try {
                $berkas = $uploader->simpan($_FILES['foto']);
                $model->gantiFoto($id, $berkas['nama']);
            } catch (\App\Core\BerkasTidakValid $e) {
                Flash::error('Foto gagal diunggah: ' . $e->getMessage());
                redirect('/profil');
            }
        }

        Auth::segarkan();
        Flash::sukses('Profil berhasil diperbarui.');
        redirect('/profil');
    }

    public function foto(string $id): void
    {
        $user = (new User())->cariDenganId($this->idWajib($id));
        if ($user === null || ($user['foto'] ?? '') === '') {
            throw HttpException::tidakAda('Foto tidak ditemukan.');
        }

        (new \App\Core\FileDownloader())->tampil((string) $user['foto'], 'foto.jpg');
    }

    public function formPassword(): void
    {
        $this->render('auth/password', ['judul' => 'Ganti Password']);
    }

    public function gantiPassword(): void
    {
        $this->wajibPost();

        $lama      = (string) ($_POST['password_lama'] ?? '');
        $baru      = (string) ($_POST['password_baru'] ?? '');
        $konfirmasi = (string) ($_POST['password_konfirmasi'] ?? '');

        $model = new User();
        $id    = (int) Auth::id();
        $hash  = $model->hashSaatIni($id);

        $error = match (true) {
            $hash === null || !password_verify($lama, $hash)
                => 'Password lama tidak cocok.',
            mb_strlen($baru) < self::PANJANG_PASSWORD_MIN
                => 'Password baru minimal ' . self::PANJANG_PASSWORD_MIN . ' karakter.',
            $baru !== $konfirmasi
                => 'Konfirmasi password tidak sama dengan password baru.',
            password_verify($baru, $hash)
                => 'Password baru harus berbeda dari password lama.',
            default => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect('/password');
        }

        $model->ubahPassword($id, password_hash($baru, PASSWORD_DEFAULT));
        Auth::segarkan();

        // Ganti id sesi setelah kredensial berubah.
        session_regenerate_id(true);

        Flash::sukses('Password berhasil diubah.');
        redirect('/profil');
    }

    /**
     * Cegah open redirect: hanya izinkan path relatif di host yang sama.
     */
    private function tujuanInternal(string $tujuan): bool
    {
        if (!str_starts_with($tujuan, '/')) {
            return false;
        }

        // "//host" dibaca browser sebagai URL absolut.
        return !str_starts_with($tujuan, '//');
    }
}
