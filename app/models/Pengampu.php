<?php

namespace App\Models;

use App\Core\Model;

/**
 * F04: guru pengampu (guru, kelas, mapel).
 *
 * Pengampu adalah konteks semua konten. Dua method di bawah menjadi
 * penjaga kepemilikan data di lapisan controller:
 *   - cariMilikGuru()   : guru hanya boleh menyentuh pengampunya sendiri
 *   - cariUntukKelas()  : siswa hanya boleh melihat pengampu kelasnya
 */
final class Pengampu extends Model
{
    private const KOLOM = 'p.id, p.guru_id, p.kelas_id, p.mapel_id,
                           g.nama AS guru_nama, k.nama AS kelas_nama, k.tingkat,
                           m.kode AS mapel_kode, m.nama AS mapel_nama,
                           t.nama AS tahun_nama, t.aktif AS tahun_aktif';

    private const JOIN = 'FROM pengampu p
                          JOIN users g        ON g.id = p.guru_id
                          JOIN kelas k        ON k.id = p.kelas_id
                          JOIN tahun_ajaran t ON t.id = k.tahun_ajaran_id
                          JOIN mapel m        ON m.id = p.mapel_id';

    /** @return list<array> */
    public function daftar(): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
           ORDER BY t.aktif DESC, k.tingkat ASC, k.nama ASC, m.nama ASC'
        );
    }

    /**
     * Semua pengampu milik satu guru. Dipakai dropdown dan daftar konten.
     *
     * @return list<array>
     */
    public function milikGuru(int $guruId): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
              WHERE p.guru_id = ?
           ORDER BY t.aktif DESC, k.tingkat ASC, k.nama ASC, m.nama ASC',
            [$guruId]
        );
    }

    /**
     * Ambil pengampu HANYA kalau benar milik guru tersebut.
     * Mengembalikan null kalau bukan miliknya -> controller membalas 403.
     */
    public function cariMilikGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
              WHERE p.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /**
     * Semua mapel yang diajarkan di satu kelas. Dipakai halaman siswa.
     *
     * @return list<array>
     */
    public function untukKelas(int $kelasId): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ',
                    (SELECT COUNT(*) FROM materi mt WHERE mt.pengampu_id = p.id) AS jumlah_materi
               ' . self::JOIN . '
              WHERE p.kelas_id = ?
           ORDER BY m.nama ASC',
            [$kelasId]
        );
    }

    /**
     * Ambil pengampu HANYA kalau ada di kelas tersebut.
     * Mengembalikan null kalau beda kelas -> controller membalas 403.
     */
    public function cariUntukKelas(int $id, int $kelasId): ?array
    {
        return $this->satu(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
              WHERE p.id = ? AND p.kelas_id = ?
              LIMIT 1',
            [$id, $kelasId]
        );
    }

    public function cariDenganId(int $id): ?array
    {
        return $this->satu(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . ' WHERE p.id = ? LIMIT 1',
            [$id]
        );
    }

    public function dipakai(int $kelasId, int $mapelId, ?int $kecualiId = null): bool
    {
        if ($kecualiId === null) {
            return (bool) $this->nilai(
                'SELECT 1 FROM pengampu WHERE kelas_id = ? AND mapel_id = ? LIMIT 1',
                [$kelasId, $mapelId]
            );
        }

        return (bool) $this->nilai(
            'SELECT 1 FROM pengampu WHERE kelas_id = ? AND mapel_id = ? AND id <> ? LIMIT 1',
            [$kelasId, $mapelId, $kecualiId]
        );
    }

    public function buat(int $guruId, int $kelasId, int $mapelId): int
    {
        return $this->sisip(
            'INSERT INTO pengampu (guru_id, kelas_id, mapel_id) VALUES (?, ?, ?)',
            [$guruId, $kelasId, $mapelId]
        );
    }

    public function ubah(int $id, int $guruId, int $kelasId, int $mapelId): void
    {
        $this->jalankan(
            'UPDATE pengampu SET guru_id = ?, kelas_id = ?, mapel_id = ? WHERE id = ?',
            [$guruId, $kelasId, $mapelId, $id]
        );
    }

    /**
     * Konten yang masih menempel pada pengampu ini.
     */
    public function jumlahRelasi(int $id): int
    {
        return (int) $this->nilai(
            'SELECT (SELECT COUNT(*) FROM materi     WHERE pengampu_id = ?)
                  + (SELECT COUNT(*) FROM tugas      WHERE pengampu_id = ?)
                  + (SELECT COUNT(*) FROM kuis       WHERE pengampu_id = ?)
                  + (SELECT COUNT(*) FROM pengumuman WHERE pengampu_id = ?)',
            [$id, $id, $id, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM pengampu WHERE id = ?', [$id]);
    }

    /** @return list<array> */
    public function semuaAktif(): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . '
               WHERE t.aktif = 1
            ORDER BY k.nama ASC, m.nama ASC'
        );
    }

    public function hitung(): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM pengampu');
    }
}
