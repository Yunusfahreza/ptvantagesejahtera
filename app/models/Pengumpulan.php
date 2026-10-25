<?php

namespace App\Models;

use App\Core\Model;

/**
 * F08, F09: pengumpulan tugas oleh siswa dan penilaiannya oleh guru.
 *
 * Status tepat_waktu / terlambat selalu dihitung di server dari NOW()
 * dibanding tenggat, bukan dari input klien.
 */
final class Pengumpulan extends Model
{
    /**
     * Daftar seluruh siswa kelas itu untuk satu tugas, termasuk yang belum
     * mengumpulkan (LEFT JOIN dari sisi users).
     *
     * @return list<array>
     */
    public function daftarUntukTugas(int $tugasId, int $kelasId): array
    {
        return $this->semua(
            "SELECT u.id AS siswa_id, u.nama AS siswa_nama, u.username,
                    pg.id AS pengumpulan_id, pg.file, pg.file_asli,
                    pg.dikumpulkan_at, pg.status, pg.nilai, pg.komentar, pg.dinilai_at
               FROM users u
          LEFT JOIN pengumpulan pg ON pg.siswa_id = u.id AND pg.tugas_id = ?
              WHERE u.kelas_id = ? AND u.role = 'siswa' AND u.status = 'aktif'
           ORDER BY u.nama ASC",
            [$tugasId, $kelasId]
        );
    }

    public function cariMilikSiswa(int $tugasId, int $siswaId): ?array
    {
        return $this->satu(
            'SELECT id, file, file_asli, dikumpulkan_at, status, nilai, komentar
               FROM pengumpulan
              WHERE tugas_id = ? AND siswa_id = ?
              LIMIT 1',
            [$tugasId, $siswaId]
        );
    }

    /**
     * Ambil satu pengumpulan lengkap dengan konteks kepemilikan guru.
     * Mengembalikan null kalau tugasnya bukan milik guru tersebut.
     */
    public function cariUntukGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT pg.id, pg.tugas_id, pg.siswa_id, pg.file, pg.file_asli, pg.nilai,
                    u.nama AS siswa_nama, t.judul AS tugas_judul
               FROM pengumpulan pg
               JOIN tugas t    ON t.id = pg.tugas_id
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN users u    ON u.id = pg.siswa_id
              WHERE pg.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /**
     * Simpan pengumpulan baru. Status dihitung di SQL dengan membandingkan
     * NOW() terhadap tenggat tugas, jadi jam klien tidak berpengaruh.
     */
    public function simpanBaru(int $tugasId, int $siswaId, string $file, string $fileAsli): int
    {
        return $this->sisip(
            "INSERT INTO pengumpulan (tugas_id, siswa_id, file, file_asli, dikumpulkan_at, status)
             SELECT t.id, ?, ?, ?, NOW(),
                    CASE WHEN NOW() <= t.tenggat THEN 'tepat_waktu' ELSE 'terlambat' END
               FROM tugas t
              WHERE t.id = ?",
            [$siswaId, $file, $fileAsli, $tugasId]
        );
    }

    /**
     * Kumpul ulang: ganti berkas, hitung ulang waktu dan status.
     */
    public function gantiBerkas(int $id, string $file, string $fileAsli): void
    {
        $this->jalankan(
            "UPDATE pengumpulan pg
               JOIN tugas t ON t.id = pg.tugas_id
                SET pg.file = ?, pg.file_asli = ?, pg.dikumpulkan_at = NOW(),
                    pg.status = CASE WHEN NOW() <= t.tenggat THEN 'tepat_waktu' ELSE 'terlambat' END
              WHERE pg.id = ?",
            [$file, $fileAsli, $id]
        );
    }

    public function simpanNilai(int $id, float $nilai, string $komentar): void
    {
        $this->jalankan(
            'UPDATE pengumpulan SET nilai = ?, komentar = ?, dinilai_at = NOW() WHERE id = ?',
            [$nilai, $komentar, $id]
        );
    }

    /**
     * Nilai tugas per siswa untuk satu pengampu. Dipakai rekap (F12).
     *
     * @return list<array{siswa_id:int,tugas_id:int,nilai:string|null}>
     */
    public function nilaiUntukPengampu(int $pengampuId): array
    {
        return $this->semua(
            'SELECT pg.siswa_id, pg.tugas_id, pg.nilai, pg.status
               FROM pengumpulan pg
               JOIN tugas t ON t.id = pg.tugas_id
              WHERE t.pengampu_id = ?',
            [$pengampuId]
        );
    }

    /**
     * Nilai tugas satu siswa di seluruh mapel. Dipakai halaman "Nilai Saya".
     *
     * @return list<array>
     */
    public function nilaiSiswa(int $siswaId): array
    {
        return $this->semua(
            'SELECT t.judul, t.tenggat, pg.dikumpulkan_at, pg.status, pg.nilai, pg.komentar,
                    m.nama AS mapel_nama, m.kode AS mapel_kode
               FROM pengumpulan pg
               JOIN tugas t    ON t.id = pg.tugas_id
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE pg.siswa_id = ?
           ORDER BY m.nama ASC, t.tenggat ASC',
            [$siswaId]
        );
    }
}
