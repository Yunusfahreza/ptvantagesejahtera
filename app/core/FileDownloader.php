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
     * Buang karakter yang bisa memecah header HTTP.
     */
    private function namaHeader(string $nama): string
    {
        $nama = str_replace(['"', chr(92), "\r", "\n"], '_', $nama);

        return $nama === '' ? 'berkas' : $nama;
    }
}
