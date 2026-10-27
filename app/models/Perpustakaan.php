<?php

namespace App\Models;

use App\Core\Model;

/**
 * F17: perpustakaan digital — bank e-book dan modul referensi.
 */
final class Perpustakaan extends Model
{
    /**
     * Semua buku, opsional filter per mapel dan tingkat.
     *
     * @return list<array>
     */
    public function daftar(?int $mapelId = null, ?string $tingkat = null, string $cari = ''): array
    {
        $where  = [];
        $param  = [];

        if ($mapelId !== null) {
            $where[] = 'pb.mapel_id = ?';
            $param[] = $mapelId;
        }

        if ($tingkat !== null && $tingkat !== '' && $tingkat !== 'semua') {
            $where[] = "(pb.tingkat = ? OR pb.tingkat = 'semua')";
            $param[] = $tingkat;
        }

        if ($cari !== '') {
            $where[] = '(pb.judul LIKE ? OR pb.penulis LIKE ?)';
            $param[] = '%' . $cari . '%';
            $param[] = '%' . $cari . '%';
        }

        $sql = 'SELECT pb.*, m.nama AS mapel_nama, m.kode AS mapel_kode, u.nama AS uploader_nama
                  FROM perpustakaan pb
             LEFT JOIN mapel m ON m.id = pb.mapel_id
             LEFT JOIN users u ON u.id = pb.uploader_id';

        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY pb.created_at DESC';

        return $this->semua($sql, $param);
    }

    public function cari(int $id): ?array
    {
        return $this->satu(
            'SELECT pb.*, m.nama AS mapel_nama, m.kode AS mapel_kode, u.nama AS uploader_nama
               FROM perpustakaan pb
          LEFT JOIN mapel m ON m.id = pb.mapel_id
          LEFT JOIN users u ON u.id = pb.uploader_id
              WHERE pb.id = ?
              LIMIT 1',
            [$id]
        );
    }

    public function buat(string $judul, ?string $penulis, ?string $deskripsi, string $kategori, ?int $mapelId, string $tingkat, string $file, string $fileAsli, ?string $cover, int $uploaderId): int
    {
        return $this->sisip(
            'INSERT INTO perpustakaan (judul, penulis, deskripsi, kategori, mapel_id, tingkat, file, file_asli, cover, uploader_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$judul, $penulis, $deskripsi, $kategori, $mapelId, $tingkat, $file, $fileAsli, $cover, $uploaderId]
        );
    }

    public function ubah(int $id, string $judul, ?string $penulis, ?string $deskripsi, string $kategori, ?int $mapelId, string $tingkat): void
    {
        $this->jalankan(
            'UPDATE perpustakaan SET judul = ?, penulis = ?, deskripsi = ?, kategori = ?, mapel_id = ?, tingkat = ? WHERE id = ?',
            [$judul, $penulis, $deskripsi, $kategori, $mapelId, $tingkat, $id]
        );
    }

    public function gantiFile(int $id, string $file, string $fileAsli): void
    {
        $this->jalankan(
            'UPDATE perpustakaan SET file = ?, file_asli = ? WHERE id = ?',
            [$file, $fileAsli, $id]
        );
    }

    public function gantiCover(int $id, string $cover): void
    {
        $this->jalankan(
            'UPDATE perpustakaan SET cover = ? WHERE id = ?',
            [$cover, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM perpustakaan WHERE id = ?', [$id]);
    }

    public function hitung(): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM perpustakaan');
    }
}
