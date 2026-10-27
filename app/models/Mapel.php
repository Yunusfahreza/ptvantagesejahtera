<?php

namespace App\Models;

use App\Core\Model;

/**
 * F03: mata pelajaran dengan kode unik.
 */
final class Mapel extends Model
{
    /** @return list<array> */
    public function daftar(): array
    {
        return $this->semua(
            'SELECT m.id, m.kode, m.nama,
                    (SELECT COUNT(*) FROM pengampu p WHERE p.mapel_id = m.id) AS jumlah_pengampu
               FROM mapel m
           ORDER BY m.nama ASC'
        );
    }

    public function cariDenganId(int $id): ?array
    {
        return $this->satu('SELECT id, kode, nama FROM mapel WHERE id = ?', [$id]);
    }

    public function kodeDipakai(string $kode, ?int $kecualiId = null): bool
    {
        if ($kecualiId === null) {
            return (bool) $this->nilai('SELECT 1 FROM mapel WHERE kode = ? LIMIT 1', [$kode]);
        }

        return (bool) $this->nilai(
            'SELECT 1 FROM mapel WHERE kode = ? AND id <> ? LIMIT 1',
            [$kode, $kecualiId]
        );
    }

    public function buat(string $kode, string $nama): int
    {
        return $this->sisip('INSERT INTO mapel (kode, nama) VALUES (?, ?)', [$kode, $nama]);
    }

    public function ubah(int $id, string $kode, string $nama): void
    {
        $this->jalankan('UPDATE mapel SET kode = ?, nama = ? WHERE id = ?', [$kode, $nama, $id]);
    }

    public function jumlahRelasi(int $id): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM pengampu WHERE mapel_id = ?', [$id]);
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM mapel WHERE id = ?', [$id]);
    }

    /** @return list<array> */
    public function semuaAktif(): array
    {
        return $this->semua('SELECT id, kode, nama FROM mapel ORDER BY nama ASC');
    }

    public function hitung(): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM mapel');
    }
}
