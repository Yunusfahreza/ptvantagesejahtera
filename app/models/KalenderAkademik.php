<?php

namespace App\Models;

use App\Core\Model;

/**
 * F23: kalender akademik sekolah.
 */
final class KalenderAkademik extends Model
{
    /**
     * Semua event di satu tahun ajaran.
     *
     * @return list<array>
     */
    public function daftar(int $tahunAjaranId): array
    {
        return $this->semua(
            'SELECT ka.*, t.nama AS tahun_nama
               FROM kalender_akademik ka
               JOIN tahun_ajaran t ON t.id = ka.tahun_ajaran_id
              WHERE ka.tahun_ajaran_id = ?
           ORDER BY ka.tanggal_mulai ASC',
            [$tahunAjaranId]
        );
    }

    /**
     * Event mendatang (mulai dari hari ini).
     *
     * @return list<array>
     */
    public function mendatang(int $limit = 5): array
    {
        return $this->semua(
            'SELECT ka.*, t.nama AS tahun_nama
               FROM kalender_akademik ka
               JOIN tahun_ajaran t ON t.id = ka.tahun_ajaran_id
              WHERE ka.tanggal_selesai >= CURDATE()
                AND t.aktif = 1
           ORDER BY ka.tanggal_mulai ASC
              LIMIT ' . (int) $limit
        );
    }

    /**
     * Event dalam satu bulan tertentu.
     *
     * @return list<array>
     */
    public function bulanIni(int $tahun, int $bulan): array
    {
        $awal  = sprintf('%04d-%02d-01', $tahun, $bulan);
        $akhir = date('Y-m-t', strtotime($awal));

        return $this->semua(
            'SELECT ka.*, t.nama AS tahun_nama
               FROM kalender_akademik ka
               JOIN tahun_ajaran t ON t.id = ka.tahun_ajaran_id
              WHERE ka.tanggal_mulai <= ? AND ka.tanggal_selesai >= ?
                AND t.aktif = 1
           ORDER BY ka.tanggal_mulai ASC',
            [$akhir, $awal]
        );
    }

    public function cari(int $id): ?array
    {
        return $this->satu(
            'SELECT * FROM kalender_akademik WHERE id = ? LIMIT 1',
            [$id]
        );
    }

    public function buat(string $judul, ?string $deskripsi, string $tipe, string $tanggalMulai, string $tanggalSelesai, int $tahunAjaranId): int
    {
        return $this->sisip(
            'INSERT INTO kalender_akademik (judul, deskripsi, tipe, tanggal_mulai, tanggal_selesai, tahun_ajaran_id)
             VALUES (?, ?, ?, ?, ?, ?)',
            [$judul, $deskripsi, $tipe, $tanggalMulai, $tanggalSelesai, $tahunAjaranId]
        );
    }

    public function ubah(int $id, string $judul, ?string $deskripsi, string $tipe, string $tanggalMulai, string $tanggalSelesai, int $tahunAjaranId): void
    {
        $this->jalankan(
            'UPDATE kalender_akademik SET judul = ?, deskripsi = ?, tipe = ?, tanggal_mulai = ?, tanggal_selesai = ?, tahun_ajaran_id = ? WHERE id = ?',
            [$judul, $deskripsi, $tipe, $tanggalMulai, $tanggalSelesai, $tahunAjaranId, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM kalender_akademik WHERE id = ?', [$id]);
    }
}
