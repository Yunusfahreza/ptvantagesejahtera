<?php

namespace App\Core;

use RuntimeException;
use Throwable;

/**
 * Render file view PHP di dalam layout. View hanya boleh menampilkan data.
 *
 * Catatan: variabel lokal di sertakan() memakai awalan ganda garis bawah
 * supaya tidak bertabrakan dengan kunci data yang di-extract ke dalam view.
 */
final class View
{
    private const DIR = __DIR__ . '/../views/';

    public static function render(string $view, array $data = [], string $layout = 'layouts/utama'): void
    {
        $isi = self::tangkap($view, $data);

        if ($layout === '') {
            echo $isi;

            return;
        }

        echo self::tangkap($layout, $data + ['isi' => $isi]);
    }

    public static function tangkap(string $view, array $data = []): string
    {
        // Hanya izinkan nama view yang aman: huruf, angka, garis bawah, strip, slash.
        if (preg_match('#^[A-Za-z0-9_-]+(/[A-Za-z0-9_-]+)*$#', $view) !== 1) {
            throw new RuntimeException('Nama view tidak valid: ' . $view);
        }

        $file = self::DIR . $view . '.php';

        if (!is_file($file)) {
            throw new RuntimeException('View tidak ditemukan: ' . $view);
        }

        return self::sertakan($file, $data);
    }

    private static function sertakan(string $__berkas, array $__data): string
    {
        unset($__data['__berkas'], $__data['__data']);
        extract($__data, EXTR_OVERWRITE);

        $__level = ob_get_level();
        ob_start();

        try {
            require $__berkas;

            return (string) ob_get_clean();
        } catch (Throwable $e) {
            // Buang buffer yang belum selesai supaya halaman error tidak tercampur
            // dengan potongan HTML view yang gagal.
            while (ob_get_level() > $__level) {
                ob_end_clean();
            }

            throw $e;
        }
    }
}
