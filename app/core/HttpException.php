<?php

namespace App\Core;

use RuntimeException;

/**
 * Error yang punya status HTTP. Ditangkap di front controller lalu dirender
 * sebagai halaman 403/404 yang rapi.
 */
class HttpException extends RuntimeException
{
    public function __construct(private int $status, string $pesan = '')
    {
        parent::__construct($pesan);
    }

    public function status(): int
    {
        return $this->status;
    }

    public static function terlarang(string $pesan = 'Anda tidak berhak mengakses halaman ini.'): self
    {
        return new self(403, $pesan);
    }

    public static function tidakAda(string $pesan = 'Halaman yang Anda cari tidak ditemukan.'): self
    {
        return new self(404, $pesan);
    }
}
