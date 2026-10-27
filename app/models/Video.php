<?php

namespace App\Models;

use App\Core\Model;

/**
 * F15: video pembelajaran yang digantungkan pada satu pengampu.
 */
final class Video extends Model
{
    /**
     * Semua video milik satu guru (lewat pengampunya).
     *
     * @return list<array>
     */
    public function milikGuru(int $guruId): array
    {
        return $this->semua(
            'SELECT v.id, v.judul, v.deskripsi, v.tipe, v.file, v.file_asli,
                    v.url_embed, v.durasi, v.created_at, v.pengampu_id,
                    k.nama AS kelas_nama, m.nama AS mapel_nama, m.kode AS mapel_kode
               FROM video_pembelajaran v
               JOIN pengampu p ON p.id = v.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.guru_id = ?
           ORDER BY v.created_at DESC',
            [$guruId]
        );
    }

    /**
     * Ambil video HANYA kalau pengampunya milik guru tersebut.
     */
    public function cariMilikGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT v.id, v.judul, v.deskripsi, v.tipe, v.file, v.file_asli,
                    v.url_embed, v.durasi, v.pengampu_id,
                    k.nama AS kelas_nama, m.nama AS mapel_nama
               FROM video_pembelajaran v
               JOIN pengampu p ON p.id = v.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE v.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /**
     * Video satu mapel untuk satu kelas. Dipakai halaman siswa.
     *
     * @return list<array>
     */
    public function untukPengampu(int $pengampuId): array
    {
        return $this->semua(
            'SELECT id, judul, deskripsi, tipe, file, file_asli, url_embed, durasi, created_at
               FROM video_pembelajaran
              WHERE pengampu_id = ?
           ORDER BY created_at DESC',
            [$pengampuId]
        );
    }

    /**
     * Ambil video HANYA kalau pengampunya ada di kelas siswa tersebut.
     */
    public function cariUntukKelas(int $id, int $kelasId): ?array
    {
        return $this->satu(
            'SELECT v.id, v.judul, v.deskripsi, v.tipe, v.file, v.file_asli,
                    v.url_embed, v.durasi, v.pengampu_id,
                    k.nama AS kelas_nama, m.nama AS mapel_nama, g.nama AS guru_nama
               FROM video_pembelajaran v
               JOIN pengampu p ON p.id = v.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
               JOIN users g    ON g.id = p.guru_id
              WHERE v.id = ? AND p.kelas_id = ?
              LIMIT 1',
            [$id, $kelasId]
        );
    }

    public function buat(int $pengampuId, string $judul, string $deskripsi, string $tipe, ?string $file, ?string $fileAsli, ?string $urlEmbed, ?string $durasi): int
    {
        return $this->sisip(
            'INSERT INTO video_pembelajaran (pengampu_id, judul, deskripsi, tipe, file, file_asli, url_embed, durasi)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [$pengampuId, $judul, $deskripsi, $tipe, $file, $fileAsli, $urlEmbed, $durasi]
        );
    }

    public function ubah(int $id, int $pengampuId, string $judul, string $deskripsi, ?string $urlEmbed, ?string $durasi): void
    {
        $this->jalankan(
            'UPDATE video_pembelajaran SET pengampu_id = ?, judul = ?, deskripsi = ?, url_embed = ?, durasi = ? WHERE id = ?',
            [$pengampuId, $judul, $deskripsi, $urlEmbed, $durasi, $id]
        );
    }

    public function gantiFile(int $id, string $file, string $fileAsli): void
    {
        $this->jalankan(
            'UPDATE video_pembelajaran SET file = ?, file_asli = ? WHERE id = ?',
            [$file, $fileAsli, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM video_pembelajaran WHERE id = ?', [$id]);
    }

    public function hitungMilikGuru(int $guruId): int
    {
        return (int) $this->nilai(
            'SELECT COUNT(*) FROM video_pembelajaran v
               JOIN pengampu p ON p.id = v.pengampu_id
              WHERE p.guru_id = ?',
            [$guruId]
        );
    }

    public function hitungUntukKelas(int $kelasId): int
    {
        return (int) $this->nilai(
            'SELECT COUNT(*) FROM video_pembelajaran v
               JOIN pengampu p ON p.id = v.pengampu_id
              WHERE p.kelas_id = ?',
            [$kelasId]
        );
    }
}
