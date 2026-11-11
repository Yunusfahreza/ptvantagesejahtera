<?php

namespace App\Models;

use App\Core\Model;

/**
 * Fitur Google Meet: sesi tatap muka daring per pengampu (guru, kelas, mapel).
 */
final class GoogleMeet extends Model
{
    private const KOLOM = 'gm.id, gm.pengampu_id, gm.judul, gm.deskripsi, gm.link_meet,
                           gm.tanggal, gm.jam_mulai, gm.jam_selesai, gm.status, gm.created_at,
                           k.nama AS kelas_nama, k.tingkat,
                           m.kode AS mapel_kode, m.nama AS mapel_nama,
                           g.nama AS guru_nama, g.id AS guru_id';

    private const JOIN = 'FROM google_meet gm
                          JOIN pengampu p ON p.id = gm.pengampu_id
                          JOIN kelas k    ON k.id = p.kelas_id
                          JOIN mapel m    ON m.id = p.mapel_id
                          JOIN users g    ON g.id = p.guru_id';

    private const ORDER_PRIORITAS = "ORDER BY FIELD(gm.status, 'berlangsung', 'terjadwal', 'selesai'), gm.tanggal DESC, gm.jam_mulai ASC";

    /**
     * Semua sesi Google Meet lintas sekolah untuk monitoring Admin.
     *
     * @return list<array>
     */
    public function semuaUntukAdmin(): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . ' ' . self::ORDER_PRIORITAS
        );
    }

    /**
     * Semua sesi Google Meet milik satu guru.
     *
     * @return list<array>
     */
    public function milikGuru(int $guruId): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
              WHERE p.guru_id = ? ' . self::ORDER_PRIORITAS,
            [$guruId]
        );
    }

    /**
     * Sesi Google Meet aktif (berlangsung/terjadwal) milik guru untuk Dashboard Guru.
     *
     * @return list<array>
     */
    public function aktifMilikGuru(int $guruId, int $limit = 3): array
    {
        $limit = max(1, $limit);

        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . "
              WHERE p.guru_id = ? AND gm.status IN ('berlangsung', 'terjadwal')
           ORDER BY FIELD(gm.status, 'berlangsung', 'terjadwal'), gm.tanggal ASC, gm.jam_mulai ASC
              LIMIT " . $limit,
            [$guruId]
        );
    }

    public function cariMilikGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
              WHERE gm.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /**
     * Semua sesi Google Meet untuk kelas siswa tertentu.
     *
     * @return list<array>
     */
    public function untukKelas(int $kelasId): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
              WHERE p.kelas_id = ? ' . self::ORDER_PRIORITAS,
            [$kelasId]
        );
    }

    /**
     * Sesi Google Meet yang sedang live / terjadwal untuk ditampilkan di Dashboard Siswa.
     *
     * @return list<array>
     */
    public function aktifUntukKelas(int $kelasId, int $limit = 3): array
    {
        $limit = max(1, $limit);

        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . "
              WHERE p.kelas_id = ? AND gm.status IN ('berlangsung', 'terjadwal')
           ORDER BY FIELD(gm.status, 'berlangsung', 'terjadwal'), gm.tanggal ASC, gm.jam_mulai ASC
              LIMIT " . $limit,
            [$kelasId]
        );
    }

    public function cariDenganId(int $id): ?array
    {
        return $this->satu(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . ' WHERE gm.id = ? LIMIT 1',
            [$id]
        );
    }

    public function buat(
        int $pengampuId,
        string $judul,
        string $deskripsi,
        string $linkMeet,
        string $tanggal,
        string $jamMulai,
        string $jamSelesai,
        string $status = 'terjadwal'
    ): int {
        return $this->sisip(
            'INSERT INTO google_meet (pengampu_id, judul, deskripsi, link_meet, tanggal, jam_mulai, jam_selesai, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [$pengampuId, $judul, $deskripsi, $linkMeet, $tanggal, $jamMulai, $jamSelesai, $status]
        );
    }

    public function ubah(
        int $id,
        int $pengampuId,
        string $judul,
        string $deskripsi,
        string $linkMeet,
        string $tanggal,
        string $jamMulai,
        string $jamSelesai,
        string $status
    ): void {
        $this->jalankan(
            'UPDATE google_meet
                SET pengampu_id = ?, judul = ?, deskripsi = ?, link_meet = ?,
                    tanggal = ?, jam_mulai = ?, jam_selesai = ?, status = ?
              WHERE id = ?',
            [$pengampuId, $judul, $deskripsi, $linkMeet, $tanggal, $jamMulai, $jamSelesai, $status, $id]
        );
    }

    public function ubahStatus(int $id, string $status): void
    {
        $this->jalankan(
            'UPDATE google_meet SET status = ? WHERE id = ?',
            [$status, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM google_meet WHERE id = ?', [$id]);
    }

    /**
     * Normalisasi tautan atau kode room Google Meet.
     * Mendukung:
     *   - https://meet.google.com/abc-defg-hij
     *   - meet.google.com/abc-defg-hij
     *   - abc-defg-hij (kode 3 segmen huruf)
     */
    public static function normalisasiLink(string $input): ?string
    {
        $input = trim($input);

        if ($input === '') {
            return null;
        }

        // Jika hanya kode room seperti abc-defg-hij
        if (preg_match('/^[a-zA-Z]{3}-[a-zA-Z]{4}-[a-zA-Z]{3}$/', $input)) {
            return 'https://meet.google.com/' . strtolower($input);
        }

        // Jika tanpa skema http/https
        if (!preg_match('#^https?://#i', $input)) {
            $input = 'https://' . $input;
        }

        if (filter_var($input, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $skema = strtolower((string) parse_url($input, PHP_URL_SCHEME));
        $host  = strtolower((string) parse_url($input, PHP_URL_HOST));

        if (!in_array($skema, ['http', 'https'], true) || $host === '') {
            return null;
        }

        // Pastikan benar domain Google Meet (meet.google.com / g.co)
        if ($host !== 'meet.google.com' && $host !== 'g.co' && !str_ends_with($host, '.meet.google.com')) {
            return null;
        }

        return $input;
    }

    /**
     * Ambil kode ruang ringkas dari tautan Meet untuk tampilan UI.
     */
    public static function ekstrakKode(string $url): string
    {
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        return $path !== '' ? $path : 'meet.google.com';
    }
}
