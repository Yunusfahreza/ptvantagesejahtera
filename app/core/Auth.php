<?php

namespace App\Core;

use App\Models\User;

/**
 * Sesi pengguna: login, logout, cek peran, dan rate limit login.
 */
final class Auth
{
    private const KUNCI_USER = '_user_id';
    private const KUNCI_GAGAL = '_login_gagal';

    private static ?array $cache = null;

    public static function mulaiSesi(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'secure'   => (bool) Config::get('app.https', false),
            'samesite' => 'Lax',
        ]);
        session_name('LMSSESSID');
        session_start();
    }

    /**
     * Coba login. Mengembalikan pesan error, atau null kalau berhasil.
     */
    public static function login(string $username, string $password): ?string
    {
        if (($sisa = self::sisaTahanan()) > 0) {
            return sprintf(
                'Terlalu banyak percobaan gagal. Coba lagi dalam %d detik.',
                $sisa
            );
        }

        $user = (new User())->cariAktifDenganUsername($username);

        // Pesan gagal sengaja generik: jangan bocorkan username mana yang ada.
        if ($user === null || !password_verify($password, $user['password_hash'])) {
            self::catatGagal();

            return 'Username atau password salah.';
        }

        self::resetGagal();

        // Cegah session fixation.
        session_regenerate_id(true);
        $_SESSION[self::KUNCI_USER] = (int) $user['id'];
        self::$cache = null;

        return null;
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires'  => time() - 42000,
                'path'     => $p['path'],
                'domain'   => $p['domain'],
                'secure'   => $p['secure'],
                'httponly' => $p['httponly'],
                'samesite' => $p['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
        self::$cache = null;
    }

    public static function sudahLogin(): bool
    {
        return self::user() !== null;
    }

    /**
     * Data pengguna yang sedang login, dibaca ulang dari DB sekali per request
     * supaya akun yang baru dinonaktifkan langsung terputus.
     */
    public static function user(): ?array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $id = $_SESSION[self::KUNCI_USER] ?? null;

        if (!is_int($id) && !is_numeric($id)) {
            return null;
        }

        $user = (new User())->cariAktifDenganId((int) $id);

        if ($user === null) {
            // Akun hilang atau dinonaktifkan di tengah sesi.
            unset($_SESSION[self::KUNCI_USER]);

            return null;
        }

        return self::$cache = $user;
    }

    public static function id(): ?int
    {
        $user = self::user();

        return $user === null ? null : (int) $user['id'];
    }

    public static function peran(): ?string
    {
        $user = self::user();

        return $user === null ? null : (string) $user['role'];
    }

    public static function kelasId(): ?int
    {
        $user = self::user();

        if ($user === null || $user['kelas_id'] === null) {
            return null;
        }

        return (int) $user['kelas_id'];
    }

    public static function punyaPeran(string ...$peran): bool
    {
        $aktif = self::peran();

        return $aktif !== null && in_array($aktif, $peran, true);
    }

    public static function dashboardPath(): string
    {
        return match (self::peran()) {
            'admin' => '/admin',
            'guru'  => '/guru',
            'siswa' => '/siswa',
            default => '/login',
        };
    }

    /**
     * Segarkan cache setelah data pengguna berubah (mis. ganti password).
     */
    public static function segarkan(): void
    {
        self::$cache = null;
    }

    // --- Rate limit login (berbasis sesi, PRD F01) ---

    private static function catatGagal(): void
    {
        $data = $_SESSION[self::KUNCI_GAGAL] ?? ['jumlah' => 0, 'sampai' => 0];
        $data['jumlah']++;

        if ($data['jumlah'] >= (int) Config::get('login.max_gagal', 5)) {
            $data['sampai'] = time() + (int) Config::get('login.tahan_detik', 300);
            $data['jumlah'] = 0;
        }

        $_SESSION[self::KUNCI_GAGAL] = $data;
    }

    private static function resetGagal(): void
    {
        unset($_SESSION[self::KUNCI_GAGAL]);
    }

    public static function sisaTahanan(): int
    {
        $sampai = (int) ($_SESSION[self::KUNCI_GAGAL]['sampai'] ?? 0);

        return max(0, $sampai - time());
    }
}
