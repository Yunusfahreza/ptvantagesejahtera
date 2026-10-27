<?php

namespace App\Core;

use RuntimeException;

/**
 * Pembaca konfigurasi dari config/config.php dengan akses bergaya "db.host".
 */
final class Config
{
    private static ?array $data = null;

    public static function muat(string $path): void
    {
        if (!is_file($path)) {
            throw new RuntimeException(
                'config/config.php tidak ditemukan. Salin config/config.example.php terlebih dahulu.'
            );
        }

        $data = require $path;

        if (!is_array($data)) {
            throw new RuntimeException('config/config.php harus mengembalikan array.');
        }

        self::$data = $data;
    }

    public static function get(string $kunci, mixed $bawaan = null): mixed
    {
        if (self::$data === null) {
            throw new RuntimeException('Config belum dimuat.');
        }

        $nilai = self::$data;

        foreach (explode('.', $kunci) as $bagian) {
            if (!is_array($nilai) || !array_key_exists($bagian, $nilai)) {
                return $bawaan;
            }
            $nilai = $nilai[$bagian];
        }

        return $nilai;
    }
}
