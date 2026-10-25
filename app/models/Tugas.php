<?php

namespace App\Models;

use App\Core\Model;

/**
 * F07, F09: tugas milik satu pengampu.
 */
final class Tugas extends Model
{
    /** @return list<array> */
    public function milikGuru(int $guruId): array
    {
        return $this->semua(
            "SELECT t.id, t.judul, t.tenggat, t.created_at, t.pengampu_id,
                    t.lampiran, t.lampiran_asli,
                    k.nama AS kelas_nama, m.nama AS mapel_nama, m.kode AS mapel_kode,
                    (SELECT COUNT(*) FROM pengumpulan pg WHERE pg.tugas_id = t.id) AS jumlah_kumpul,
                    (SELECT COUNT(*) FROM pengumpulan pg
                      WHERE pg.tugas_id = t.id AND pg.nilai IS NULL) AS belum_dinilai,
                    (SELECT COUNT(*) FROM users u
                      WHERE u.kelas_id = p.kelas_id AND u.role = 'siswa'
                        AND u.status = 'aktif') AS jumlah_siswa
               FROM tugas t
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.guru_id = ?
           ORDER BY t.tenggat DESC",
            [$guruId]
        );
    }

    public function cariMilikGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT t.id, t.judul, t.deskripsi, t.tenggat, t.pengampu_id,
                    t.lampiran, t.lampiran_asli,
                    p.kelas_id, k.nama AS kelas_nama, m.nama AS mapel_nama
               FROM tugas t
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE t.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /**
     * Daftar tugas untuk satu siswa, lengkap dengan status pengumpulannya.
     *
     * @return list<array>
     */
    public function untukSiswa(int $siswaId, int $kelasId): array
    {
        return $this->semua(
            'SELECT t.id, t.judul, t.tenggat, t.pengampu_id,
                    k.nama AS kelas_nama, m.nama AS mapel_nama, m.kode AS mapel_kode,
                    pg.id AS pengumpulan_id, pg.dikumpulkan_at, pg.status,
                    pg.nilai, pg.komentar
               FROM tugas t
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
          LEFT JOIN pengumpulan pg ON pg.tugas_id = t.id AND pg.siswa_id = ?
              WHERE p.kelas_id = ?
           ORDER BY t.tenggat DESC',
            [$siswaId, $kelasId]
        );
    }

    /**
     * Satu tugas untuk siswa. Mengembalikan null kalau bukan kelasnya.
     */
    public function cariUntukSiswa(int $id, int $siswaId, int $kelasId): ?array
    {
        return $this->satu(
            'SELECT t.id, t.judul, t.deskripsi, t.tenggat, t.pengampu_id,
                    t.lampiran, t.lampiran_asli,
                    k.nama AS kelas_nama, m.nama AS mapel_nama, g.nama AS guru_nama,
                    pg.id AS pengumpulan_id, pg.file AS pengumpulan_file,
                    pg.file_asli AS pengumpulan_file_asli, pg.dikumpulkan_at,
                    pg.status, pg.nilai, pg.komentar
               FROM tugas t
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
               JOIN users g    ON g.id = p.guru_id
          LEFT JOIN pengumpulan pg ON pg.tugas_id = t.id AND pg.siswa_id = ?
              WHERE t.id = ? AND p.kelas_id = ?
              LIMIT 1',
            [$siswaId, $id, $kelasId]
        );
    }

    /**
     * Tugas yang tenggatnya mendekat dan belum dikumpulkan siswa ini.
     *
     * @return list<array>
     */
    public function mendekatiTenggatUntukSiswa(int $siswaId, int $kelasId, int $batas = 5): array
    {
        return $this->semua(
            'SELECT t.id, t.judul, t.tenggat, m.nama AS mapel_nama
               FROM tugas t
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
          LEFT JOIN pengumpulan pg ON pg.tugas_id = t.id AND pg.siswa_id = ?
              WHERE p.kelas_id = ? AND pg.id IS NULL AND t.tenggat >= NOW()
           ORDER BY t.tenggat ASC
              LIMIT ' . (int) $batas,
            [$siswaId, $kelasId]
        );
    }

    /** @return list<array> */
    public function mendatangMilikGuru(int $guruId, int $batas = 5): array
    {
        return $this->semua(
            'SELECT t.id, t.judul, t.tenggat, k.nama AS kelas_nama, m.nama AS mapel_nama
               FROM tugas t
               JOIN pengampu p ON p.id = t.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.guru_id = ? AND t.tenggat >= NOW()
           ORDER BY t.tenggat ASC
              LIMIT ' . (int) $batas,
            [$guruId]
        );
    }

    /**
     * Tugas yang masih punya pengumpulan tanpa nilai.
     *
     * @return list<array>
     */
    public function pengumpulanBelumDinilai(int $guruId, int $batas = 10): array
    {
        return $this->semua(
            'SELECT t.id, t.judul, k.nama AS kelas_nama, m.nama AS mapel_nama,
                    COUNT(pg.id) AS jumlah
               FROM tugas t
               JOIN pengampu p    ON p.id = t.pengampu_id
               JOIN kelas k       ON k.id = p.kelas_id
               JOIN mapel m       ON m.id = p.mapel_id
               JOIN pengumpulan pg ON pg.tugas_id = t.id AND pg.nilai IS NULL
              WHERE p.guru_id = ?
           GROUP BY t.id, t.judul, k.nama, m.nama
           ORDER BY jumlah DESC
              LIMIT ' . (int) $batas,
            [$guruId]
        );
    }

    /** @return list<array> Tugas satu pengampu, dipakai rekap nilai. */
    public function untukPengampu(int $pengampuId): array
    {
        return $this->semua(
            'SELECT id, judul, tenggat FROM tugas
              WHERE pengampu_id = ?
           ORDER BY tenggat ASC, id ASC',
            [$pengampuId]
        );
    }

    public function buat(
        int $pengampuId,
        string $judul,
        string $deskripsi,
        string $tenggat,
        ?string $lampiran,
        ?string $lampiranAsli
    ): int {
        return $this->sisip(
            'INSERT INTO tugas (pengampu_id, judul, deskripsi, tenggat, lampiran, lampiran_asli)
             VALUES (?, ?, ?, ?, ?, ?)',
            [$pengampuId, $judul, $deskripsi, $tenggat, $lampiran, $lampiranAsli]
        );
    }

    public function ubah(int $id, int $pengampuId, string $judul, string $deskripsi, string $tenggat): void
    {
        $this->jalankan(
            'UPDATE tugas SET pengampu_id = ?, judul = ?, deskripsi = ?, tenggat = ? WHERE id = ?',
            [$pengampuId, $judul, $deskripsi, $tenggat, $id]
        );
    }

    public function gantiLampiran(int $id, ?string $lampiran, ?string $lampiranAsli): void
    {
        $this->jalankan(
            'UPDATE tugas SET lampiran = ?, lampiran_asli = ? WHERE id = ?',
            [$lampiran, $lampiranAsli, $id]
        );
    }

    public function jumlahPengumpulan(int $id): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM pengumpulan WHERE tugas_id = ?', [$id]);
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM tugas WHERE id = ?', [$id]);
    }
}
