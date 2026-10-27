<?php

namespace App\Core;

use RuntimeException;

/**
 * Validasi dan penyimpanan berkas unggahan. Dipakai materi, tugas, dan
 * pengumpulan sehingga aturannya hanya ditulis satu kali.
 *
 * Aturan (PRD F05):
 *   - ekstensi: pdf, docx, pptx, jpg, jpeg, png
 *   - MIME diperiksa dengan finfo (isi berkas, bukan nama)
 *   - ukuran maksimal 10 MB
 *   - disimpan dengan nama acak di uploads/
 */
final class FileUploader
{
    /** Ekstensi yang diizinkan dengan daftar MIME yang sah untuk masing-masing. */
    private const DIIZINKAN = [
        'pdf'  => ['application/pdf'],
        'docx' => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/zip',
        ],
        'pptx' => [
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/zip',
        ],
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
    ];

    private string $direktori;
    private int $maksByte;

    public function __construct(?string $direktori = null, ?int $maksByte = null)
    {
        $this->direktori = $direktori ?? (string) Config::get('app.upload_dir', __DIR__ . '/../../uploads');
        $this->maksByte  = $maksByte ?? (int) Config::get('app.upload_max', 10 * 1024 * 1024);
    }

    public function maksByte(): int
    {
        return $this->maksByte;
    }

    public function daftarEkstensi(): string
    {
        return implode(', ', array_keys(self::DIIZINKAN));
    }

    /**
     * Simpan berkas dari $_FILES.
     *
     * @param array<string,mixed>|null $berkas Satu entri dari $_FILES.
     * @return array{nama:string, asli:string} Nama acak di disk dan nama asli.
     * @throws BerkasTidakValid kalau validasi gagal.
     */
    public function simpan(?array $berkas): array
    {
        $this->pastikanTerunggah($berkas);

        $asli     = (string) $berkas['name'];
        $tmp      = (string) $berkas['tmp_name'];
        $ekstensi = strtolower(pathinfo($asli, PATHINFO_EXTENSION));

        if (!isset(self::DIIZINKAN[$ekstensi])) {
            throw new BerkasTidakValid(
                'Format berkas tidak diizinkan. Gunakan: ' . $this->daftarEkstensi() . '.'
            );
        }

        if ((int) $berkas['size'] > $this->maksByte) {
            throw new BerkasTidakValid(
                'Ukuran berkas melebihi batas ' . ukuran_file($this->maksByte) . '.'
            );
        }

        // Periksa tipe dari ISI berkas, bukan dari nama atau header klien.
        // Ini yang menolak file .php yang namanya diganti jadi .pdf (T08).
        $mime = $this->mime($tmp);

        if (!in_array($mime, self::DIIZINKAN[$ekstensi], true)) {
            throw new BerkasTidakValid(
                'Isi berkas tidak cocok dengan ekstensinya (terdeteksi: ' . $mime . ').'
            );
        }

        $this->pastikanDirektoriAda();

        $nama   = bin2hex(random_bytes(16)) . '.' . $ekstensi;
        $tujuan = $this->direktori . '/' . $nama;

        if (!move_uploaded_file($tmp, $tujuan)) {
            throw new RuntimeException('Berkas gagal disimpan di server.');
        }

        // Jangan pernah izinkan bit eksekusi pada berkas unggahan.
        @chmod($tujuan, 0644);

        return ['nama' => $nama, 'asli' => $this->bersihkanNama($asli)];
    }

    /**
     * Hapus berkas fisik. Aman dipanggil walau berkasnya sudah hilang.
     */
    public function hapus(?string $nama): void
    {
        if ($nama === null || $nama === '' || !$this->namaAman($nama)) {
            return;
        }

        $path = $this->direktori . '/' . $nama;

        if (is_file($path)) {
            @unlink($path);
        }
    }

    public function path(string $nama): ?string
    {
        if (!$this->namaAman($nama)) {
            return null;
        }

        $path = $this->direktori . '/' . $nama;

        return is_file($path) ? $path : null;
    }

    /**
     * Nama di disk selalu hex + ekstensi. Pola ini sekaligus menutup
     * percobaan path traversal lewat kolom database.
     */
    private function namaAman(string $nama): bool
    {
        return preg_match('#^[a-f0-9]{32}\.[a-z0-9]{3,4}$#', $nama) === 1;
    }

    private function pastikanTerunggah(?array $berkas): void
    {
        $kode = is_array($berkas) ? (int) ($berkas['error'] ?? UPLOAD_ERR_NO_FILE) : UPLOAD_ERR_NO_FILE;

        if ($kode !== UPLOAD_ERR_OK) {
            throw new BerkasTidakValid(match ($kode) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE
                    => 'Berkas melebihi batas ukuran ' . ukuran_file($this->maksByte) . '.',
                UPLOAD_ERR_PARTIAL   => 'Berkas hanya terunggah sebagian. Coba lagi.',
                UPLOAD_ERR_NO_FILE   => 'Tidak ada berkas yang dipilih.',
                UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE
                    => 'Server gagal menyimpan berkas sementara.',
                UPLOAD_ERR_EXTENSION => 'Unggahan diblokir oleh konfigurasi server.',
                default              => 'Berkas gagal diunggah.',
            });
        }

        if (!is_uploaded_file((string) $berkas['tmp_name'])) {
            throw new BerkasTidakValid('Berkas unggahan tidak sah.');
        }
    }

    private function mime(string $path): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            throw new RuntimeException('Ekstensi fileinfo tidak tersedia di server.');
        }

        try {
            $mime = finfo_file($finfo, $path);
        } finally {
            finfo_close($finfo);
        }

        return $mime === false ? 'application/octet-stream' : $mime;
    }

    private function pastikanDirektoriAda(): void
    {
        if (is_dir($this->direktori)) {
            return;
        }

        if (!mkdir($this->direktori, 0755, true) && !is_dir($this->direktori)) {
            throw new RuntimeException('Folder uploads tidak bisa dibuat.');
        }
    }

    /**
     * Nama asli hanya dipakai saat mengunduh. Buang path dan karakter aneh.
     */
    private function bersihkanNama(string $nama): string
    {
        $nama = basename(str_replace(chr(92), '/', $nama));
        $nama = (string) preg_replace('#[^\w\s.()-]#u', '_', $nama);
        $nama = trim((string) preg_replace('#\s+#u', ' ', $nama));

        if ($nama === '' || $nama === '.' || $nama === '..') {
            $nama = 'berkas';
        }

        return mb_substr($nama, 0, 255);
    }
}
