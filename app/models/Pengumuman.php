<?php

namespace App\Models;

use App\Core\Model;

/**
 * F13: pengumuman kelas, digantungkan pada satu pengampu.
 */
final class Pengumuman extends Model
{
    /** @return list<array> */
    public function milikGuru(int $guruId): array
    {
        return $this->semua(
            'SELECT pm.id, pm.judul, pm.isi, pm.created_at, pm.pengampu_id,
                    k.nama AS kelas_nama, m.nama AS mapel_nama
               FROM pengumuman pm
               JOIN pengampu p ON p.id = pm.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.guru_id = ?
           ORDER BY pm.created_at DESC',
            [$guruId]
        );
    }

    public function cariMilikGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT pm.id, pm.judul, pm.isi, pm.pengampu_id
               FROM pengumuman pm
               JOIN pengampu p ON p.id = pm.pengampu_id
              WHERE pm.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /** @return list<array> */
    public function untukKelas(int $kelasId): array
    {
        return $this->semua(
            'SELECT pm.id, pm.judul, pm.isi, pm.created_at,
                    m.nama AS mapel_nama, m.kode AS mapel_kode, g.nama AS guru_nama
               FROM pengumuman pm
               JOIN pengampu p ON p.id = pm.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
               JOIN users g    ON g.id = p.guru_id
              WHERE p.kelas_id = ?
           ORDER BY pm.created_at DESC',
            [$kelasId]
        );
    }

    /** @return list<array> */
    public function terbaruUntukKelas(int $kelasId, int $batas = 5): array
    {
        return $this->semua(
            'SELECT pm.id, pm.judul, pm.created_at, m.nama AS mapel_nama
               FROM pengumuman pm
               JOIN pengampu p ON p.id = pm.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.kelas_id = ?
           ORDER BY pm.created_at DESC
              LIMIT ' . (int) $batas,
            [$kelasId]
        );
    }

    public function buat(int $pengampuId, string $judul, string $isi): int
    {
        return $this->sisip(
            'INSERT INTO pengumuman (pengampu_id, judul, isi) VALUES (?, ?, ?)',
            [$pengampuId, $judul, $isi]
        );
    }

    public function ubah(int $id, int $pengampuId, string $judul, string $isi): void
    {
        $this->jalankan(
            'UPDATE pengumuman SET pengampu_id = ?, judul = ?, isi = ? WHERE id = ?',
            [$pengampuId, $judul, $isi, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM pengumuman WHERE id = ?', [$id]);
    }
}
