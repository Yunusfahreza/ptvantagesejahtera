<?php

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Singleton koneksi PDO. Satu-satunya tempat kredensial DB dipakai.
 */
final class Database
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host    = (string) Config::get('db.host', '127.0.0.1');
        $port    = (int) Config::get('db.port', 3306);
        $nama    = (string) Config::get('db.name', '');
        $charset = (string) Config::get('db.charset', 'utf8mb4');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $nama, $charset);

        try {
            self::$pdo = new PDO(
                $dsn,
                (string) Config::get('db.user', ''),
                (string) Config::get('db.pass', ''),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_STRINGIFY_FETCHES  => false,
                ]
            );
        } catch (PDOException $e) {
            throw new RuntimeException('Koneksi database gagal: ' . $e->getMessage(), 0, $e);
        }

        // Samakan zona waktu MySQL dengan PHP supaya NOW() konsisten.
        self::$pdo->exec("SET time_zone = '" . self::offsetZonaWaktu() . "'");

        return self::$pdo;
    }

    /**
     * Ubah zona waktu PHP (Asia/Jakarta) menjadi offset yang dimengerti MySQL (+07:00).
     */
    private static function offsetZonaWaktu(): string
    {
        $offset = (new \DateTimeZone(date_default_timezone_get()))
            ->getOffset(new \DateTimeImmutable('now'));

        $tanda = $offset < 0 ? '-' : '+';
        $offset = abs($offset);

        return sprintf('%s%02d:%02d', $tanda, intdiv($offset, 3600), intdiv($offset % 3600, 60));
    }
}
