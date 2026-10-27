<?php

namespace App\Core;

/**
 * Token CSRF per sesi. Dipakai semua form POST dan request AJAX.
 */
final class Csrf
{
    private const KUNCI_SESI = '_csrf_token';
    public  const NAMA_FIELD = '_token';
    public  const NAMA_HEADER = 'HTTP_X_CSRF_TOKEN';

    public static function token(): string
    {
        if (empty($_SESSION[self::KUNCI_SESI])) {
            $_SESSION[self::KUNCI_SESI] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::KUNCI_SESI];
    }

    public static function valid(?string $token): bool
    {
        $asli = $_SESSION[self::KUNCI_SESI] ?? '';

        return $asli !== '' && is_string($token) && hash_equals($asli, $token);
    }

    /**
     * Ambil token dari body POST atau header AJAX, lalu verifikasi.
     */
    public static function validDariRequest(): bool
    {
        $token = $_POST[self::NAMA_FIELD] ?? $_SERVER[self::NAMA_HEADER] ?? null;

        return self::valid(is_string($token) ? $token : null);
    }

    public static function field(): string
    {
        return sprintf(
            '<input type="hidden" name="%s" value="%s">',
            self::NAMA_FIELD,
            htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8')
        );
    }
}
