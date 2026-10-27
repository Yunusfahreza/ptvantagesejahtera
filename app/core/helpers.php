<?php

use App\Core\Config;
use App\Core\Csrf;

/**
 * Helper global. Satu-satunya file di core yang tidak berisi kelas.
 */

if (!function_exists('e')) {
    /**
     * Escape untuk konteks HTML. WAJIB dipakai pada semua output dinamis.
     */
    function e(mixed $nilai): string
    {
        return htmlspecialchars((string) ($nilai ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('base_url')) {
    /**
     * Base URL aplikasi. Diambil dari config kalau diisi; kalau kosong
     * dideteksi dari request supaya nyaman dipakai di XAMPP maupun
     * server bawaan PHP. Di produksi config sebaiknya diisi eksplisit.
     */
    function base_url(): string
    {
        static $base = null;

        if ($base !== null) {
            return $base;
        }

        $dariConfig = rtrim((string) Config::get('base_url', ''), '/');

        if ($dariConfig !== '') {
            return $base = $dariConfig;
        }

        $skema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host  = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $dir   = rtrim(str_replace(chr(92), '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'))), '/');

        return $base = $skema . '://' . $host . ($dir === '/' ? '' : $dir);
    }
}

if (!function_exists('base_path')) {
    /**
     * Bagian path dari base URL, dipakai router untuk memotong sub-folder.
     */
    function base_path(): string
    {
        return rtrim(parse_url(base_url(), PHP_URL_PATH) ?: '', '/');
    }
}

if (!function_exists('url')) {
    /**
     * Bangun URL absolut dari path aplikasi.
     */
    function url(string $path = ''): string
    {
        return base_url() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('aset')) {
    function aset(string $path): string
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect ke path aplikasi lalu hentikan eksekusi.
     */
    function redirect(string $path): never
    {
        header('Location: ' . url($path), true, 302);
        exit;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('tanggal_id')) {
    /**
     * Format tanggal tampilan standar: d M Y H:i. Kosong kalau nilainya null.
     */
    function tanggal_id(?string $datetime, string $format = 'd M Y H:i'): string
    {
        if ($datetime === null || $datetime === '' || str_starts_with($datetime, '0000')) {
            return '-';
        }

        try {
            return (new DateTimeImmutable($datetime))->format($format);
        } catch (Exception) {
            return '-';
        }
    }
}

if (!function_exists('ukuran_file')) {
    function ukuran_file(int $byte): string
    {
        $satuan = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $n = (float) $byte;

        while ($n >= 1024 && $i < count($satuan) - 1) {
            $n /= 1024;
            $i++;
        }

        return round($n, $i === 0 ? 0 : 1) . ' ' . $satuan[$i];
    }
}

if (!function_exists('nilai_tampil')) {
    /**
     * Nilai NULL tampil sebagai "-" sesuai PRD F12, bukan 0.
     */
    function nilai_tampil(mixed $nilai): string
    {
        if ($nilai === null || $nilai === '') {
            return '-';
        }

        return rtrim(rtrim(number_format((float) $nilai, 2, ',', ''), '0'), ',');
    }
}

if (!function_exists('post')) {
    /**
     * Ambil input POST sebagai string yang sudah di-trim.
     */
    function post(string $kunci, string $bawaan = ''): string
    {
        $nilai = $_POST[$kunci] ?? $bawaan;

        return is_string($nilai) ? trim($nilai) : $bawaan;
    }
}

if (!function_exists('query')) {
    function query(string $kunci, string $bawaan = ''): string
    {
        $nilai = $_GET[$kunci] ?? $bawaan;

        return is_string($nilai) ? trim($nilai) : $bawaan;
    }
}

if (!function_exists('query_int')) {
    function query_int(string $kunci, int $bawaan = 0): int
    {
        $nilai = $_GET[$kunci] ?? null;

        return is_numeric($nilai) ? (int) $nilai : $bawaan;
    }
}

if (!function_exists('get')) {
    /**
     * Alias untuk query() mengambil nilai parameter URL $_GET.
     */
    function get(string $kunci, string $bawaan = ''): string
    {
        return query($kunci, $bawaan);
    }
}

if (!function_exists('get_int')) {
    /**
     * Alias untuk query_int() mengambil nilai integer parameter URL $_GET.
     */
    function get_int(string $kunci, int $bawaan = 0): int
    {
        return query_int($kunci, $bawaan);
    }
}

if (!function_exists('nilai_input')) {
    /**
     * Nilai DECIMAL untuk ditaruh di input number: buang nol berlebih,
     * dan kosong kalau belum dinilai.
     */
    function nilai_input(mixed $nilai): string
    {
        if ($nilai === null || $nilai === '') {
            return '';
        }

        $teks = (string) $nilai;

        return str_contains($teks, '.') ? rtrim(rtrim($teks, '0'), '.') : $teks;
    }
}
