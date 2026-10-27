<?php

namespace App\Core;

/**
 * Autoloader PSR-4 manual untuk namespace "App". Tidak butuh Composer.
 *
 * Catatan: pemisah namespace diambil lewat chr(92) agar file ini bebas
 * dari karakter escape yang mudah rusak saat disalin antar alat.
 */
final class Autoloader
{
    private const PREFIX_DIR = __DIR__ . '/../';

    public static function daftar(): void
    {
        spl_autoload_register(static function (string $class): void {
            $pemisah = chr(92);
            $prefix  = 'App' . $pemisah;

            if (!str_starts_with($class, $prefix)) {
                return;
            }

            $relatif = substr($class, strlen($prefix));
            $file    = self::PREFIX_DIR . str_replace($pemisah, '/', $relatif) . '.php';

            // Nama folder di disk memakai huruf kecil (core, models, controllers).
            if (!is_file($file)) {
                $bagian = explode('/', str_replace($pemisah, '/', $relatif));
                $nama   = array_pop($bagian);
                $bagian = array_map('strtolower', $bagian);
                $file   = self::PREFIX_DIR . implode('/', [...$bagian, $nama]) . '.php';
            }

            if (is_file($file)) {
                require $file;
            }
        });
    }
}
