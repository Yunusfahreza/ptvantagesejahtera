<?php

namespace App\Core;

/**
 * Streaming berkas unggahan. Satu-satunya jalan keluar berkas dari uploads/,
 * dan hanya dipanggil setelah controller memeriksa hak akses.
 */
final class FileDownloader
{
    public function __construct(private FileUploader $uploader = new FileUploader())
    {
    }

    /**
     * @throws HttpException 404 kalau berkas sudah tidak ada di disk.
     */
    public function kirim(string $namaDisk, string $namaAsli): never
    {
        $path = $this->uploader->path($namaDisk);

        if ($path === null) {
            throw HttpException::tidakAda('Berkas sudah tidak tersedia di server.');
        }

        // Bersihkan buffer apa pun supaya isi berkas tidak tercampur HTML.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="' . $this->namaHeader($namaAsli) . '"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store');

        readfile($path);
        exit;
    }

    /**
     * Tampilkan berkas langsung di peramban (inline), misalnya PDF atau gambar.
     */
    public function tampil(string $namaDisk, string $namaAsli, ?string $mime = null): never
    {
        $path = $this->uploader->path($namaDisk);

        if ($path === null) {
            throw HttpException::tidakAda('Berkas sudah tidak tersedia di server.');
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if ($mime === null) {
            $ext = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'pdf'  => 'application/pdf',
                'jpg', 'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
                'webp' => 'image/webp',
                'mp4'  => 'video/mp4',
                'webm' => 'video/webm',
                default => 'application/octet-stream',
            };
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline; filename="' . $this->namaHeader($namaAsli) . '"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, max-age=3600');

        readfile($path);
        exit;
    }

    /**
     * Buang karakter yang bisa memecah header HTTP.
     */
    private function namaHeader(string $nama): string
    {
        $nama = str_replace(['"', chr(92), "\r", "\n"], '_', $nama);

        return $nama === '' ? 'berkas' : $nama;
    }
}
