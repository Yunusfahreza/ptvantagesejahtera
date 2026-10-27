<?php

namespace App\Models;

use App\Core\Model;

/**
 * F05, F06: materi pembelajaran yang digantungkan pada satu pengampu.
 */
final class Materi extends Model
{
    /**
     * Semua materi milik satu guru (lewat pengampunya).
     *
     * @return list<array>
     */
    public function milikGuru(int $guruId): array
    {
        return $this->semua(
            'SELECT mt.id, mt.judul, mt.deskripsi, mt.file, mt.file_asli, mt.created_at,
                    mt.pengampu_id, k.nama AS kelas_nama, m.nama AS mapel_nama, m.kode AS mapel_kode
               FROM materi mt
               JOIN pengampu p ON p.id = mt.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.guru_id = ?
           ORDER BY mt.created_at DESC',
            [$guruId]
        );
    }

    /**
     * Ambil materi HANYA kalau pengampunya milik guru tersebut.
     */
    public function cariMilikGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT mt.id, mt.judul, mt.deskripsi, mt.file, mt.file_asli, mt.pengampu_id,
                    k.nama AS kelas_nama, m.nama AS mapel_nama
               FROM materi mt
               JOIN pengampu p ON p.id = mt.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE mt.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /**
     * Materi satu mapel untuk satu kelas. Dipakai halaman siswa.
     *
     * @return list<array>
     */
    public function untukPengampu(int $pengampuId): array
    {
        return $this->semua(
            'SELECT id, judul, deskripsi, file, file_asli, created_at
               FROM materi
              WHERE pengampu_id = ?
           ORDER BY created_at DESC',
            [$pengampuId]
        );
    }

    /**
     * Ambil materi HANYA kalau pengampunya ada di kelas siswa tersebut.
     */
    public function cariUntukKelas(int $id, int $kelasId): ?array
    {
        return $this->satu(
            'SELECT mt.id, mt.judul, mt.file, mt.file_asli
               FROM materi mt
               JOIN pengampu p ON p.id = mt.pengampu_id
              WHERE mt.id = ? AND p.kelas_id = ?
              LIMIT 1',
            [$id, $kelasId]
        );
    }

    public function buat(int $pengampuId, string $judul, string $deskripsi, string $file, string $fileAsli): int
    {
        return $this->sisip(
            'INSERT INTO materi (pengampu_id, judul, deskripsi, file, file_asli)
             VALUES (?, ?, ?, ?, ?)',
            [$pengampuId, $judul, $deskripsi, $file, $fileAsli]
        );
    }

    public function ubah(int $id, int $pengampuId, string $judul, string $deskripsi): void
    {
        $this->jalankan(
            'UPDATE materi SET pengampu_id = ?, judul = ?, deskripsi = ? WHERE id = ?',
            [$pengampuId, $judul, $deskripsi, $id]
        );
    }

    public function gantiFile(int $id, string $file, string $fileAsli): void
    {
        $this->jalankan(
            'UPDATE materi SET file = ?, file_asli = ? WHERE id = ?',
            [$file, $fileAsli, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM materi WHERE id = ?', [$id]);
    }

    public function hitungMilikGuru(int $guruId): int
    {
        return (int) $this->nilai(
            'SELECT COUNT(*) FROM materi mt
               JOIN pengampu p ON p.id = mt.pengampu_id
              WHERE p.guru_id = ?',
            [$guruId]
        );
    }

    public function hitungUntukKelas(int $kelasId): int
    {
        return (int) $this->nilai(
            'SELECT COUNT(*) FROM materi mt
               JOIN pengampu p ON p.id = mt.pengampu_id
              WHERE p.kelas_id = ?',
            [$kelasId]
        );
    }
}
