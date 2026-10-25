<?php

namespace App\Core;

/**
 * Basis controller. Menyediakan render view, validasi CSRF, dan penjaga akses.
 */
abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'layouts/utama'): void
    {
        View::render($view, $data + ['judul' => $data['judul'] ?? Config::get('app.nama')], $layout);
    }

    /**
     * Wajib dipanggil di awal setiap aksi yang mengubah data.
     */
    protected function wajibPost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            throw HttpException::tidakAda('Metode request tidak diizinkan.');
        }

        if (!Csrf::validDariRequest()) {
            throw HttpException::terlarang('Token CSRF tidak valid atau sudah kedaluwarsa.');
        }
    }

    protected function wajibPeran(string ...$peran): void
    {
        if (!Auth::punyaPeran(...$peran)) {
            throw HttpException::terlarang();
        }
    }

    /**
     * Pastikan nilai id dari URL benar-benar angka positif.
     */
    protected function idWajib(mixed $id): int
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            throw HttpException::tidakAda('Data yang diminta tidak ditemukan.');
        }

        return (int) $id;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Validasi CSRF untuk endpoint AJAX; balas JSON, bukan halaman HTML.
     */
    protected function wajibPostJson(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->json(['ok' => false, 'pesan' => 'Metode tidak diizinkan.'], 405);
            exit;
        }

        if (!Csrf::validDariRequest()) {
            $this->json(['ok' => false, 'pesan' => 'Token CSRF tidak valid.'], 403);
            exit;
        }
    }
}
