<?php

namespace App\Core;

/**
 * Pesan sekali tampil yang disimpan di sesi.
 */
final class Flash
{
    private const KUNCI = '_flash';

    public static function tambah(string $tipe, string $pesan): void
    {
        $_SESSION[self::KUNCI][] = ['tipe' => $tipe, 'pesan' => $pesan];
    }

    public static function sukses(string $pesan): void
    {
        self::tambah('success', $pesan);
    }

    public static function error(string $pesan): void
    {
        self::tambah('danger', $pesan);
    }

    public static function info(string $pesan): void
    {
        self::tambah('info', $pesan);
    }

    public static function peringatan(string $pesan): void
    {
        self::tambah('warning', $pesan);
    }

    /** @return list<array{tipe:string,pesan:string}> */
    public static function ambilSemua(): array
    {
        $pesan = $_SESSION[self::KUNCI] ?? [];
        unset($_SESSION[self::KUNCI]);

        return $pesan;
    }
}
