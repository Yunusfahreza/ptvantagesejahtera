<?php

namespace App\Models;

use App\Core\Model;

/**
 * F20: catatan pribadi siswa.
 */
final class Catatan extends Model
{
    /**
     * Semua catatan milik satu siswa.
     *
     * @return list<array>
     */
    public function milikSiswa(int $siswaId): array
    {
        return $this->semua(
            'SELECT c.*, m.nama AS mapel_nama, m.kode AS mapel_kode
               FROM catatan_siswa c
          LEFT JOIN mapel m ON m.id = c.mapel_id
              WHERE c.siswa_id = ?
           ORDER BY c.updated_at DESC',
            [$siswaId]
        );
    }

    /**
     * Ambil catatan HANYA milik siswa tersebut.
     */
    public function cariMilikSiswa(int $id, int $siswaId): ?array
    {
        return $this->satu(
            'SELECT c.*, m.nama AS mapel_nama, m.kode AS mapel_kode
               FROM catatan_siswa c
          LEFT JOIN mapel m ON m.id = c.mapel_id
              WHERE c.id = ? AND c.siswa_id = ?
              LIMIT 1',
            [$id, $siswaId]
        );
    }

    public function buat(int $siswaId, ?int $mapelId, string $judul, string $isi): int
    {
        return $this->sisip(
            'INSERT INTO catatan_siswa (siswa_id, mapel_id, judul, isi) VALUES (?, ?, ?, ?)',
            [$siswaId, $mapelId, $judul, $isi]
        );
    }

    public function ubah(int $id, ?int $mapelId, string $judul, string $isi): void
    {
        $this->jalankan(
            'UPDATE catatan_siswa SET mapel_id = ?, judul = ?, isi = ? WHERE id = ?',
            [$mapelId, $judul, $isi, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM catatan_siswa WHERE id = ?', [$id]);
    }

    public function hitung(int $siswaId): int
    {
        return (int) $this->nilai(
            'SELECT COUNT(*) FROM catatan_siswa WHERE siswa_id = ?',
            [$siswaId]
        );
    }
}
