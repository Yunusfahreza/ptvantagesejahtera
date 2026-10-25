<?php

namespace App\Models;

use App\Core\Model;

/**
 * F03: tahun ajaran. Hanya satu baris boleh berstatus aktif.
 */
final class TahunAjaran extends Model
{
    /** @return list<array> */
    public function daftar(): array
    {
        return $this->semua(
            'SELECT t.id, t.nama, t.semester, t.aktif,
                    (SELECT COUNT(*) FROM kelas k WHERE k.tahun_ajaran_id = t.id) AS jumlah_kelas
               FROM tahun_ajaran t
           ORDER BY t.aktif DESC, t.nama DESC, t.semester ASC'
        );
    }

    public function aktif(): ?array
    {
        return $this->satu('SELECT id, nama, semester FROM tahun_ajaran WHERE aktif = 1 LIMIT 1');
    }

    public function cariDenganId(int $id): ?array
    {
        return $this->satu('SELECT id, nama, semester, aktif FROM tahun_ajaran WHERE id = ?', [$id]);
    }

    public function dipakai(string $nama, string $semester, ?int $kecualiId = null): bool
    {
        if ($kecualiId === null) {
            return (bool) $this->nilai(
                'SELECT 1 FROM tahun_ajaran WHERE nama = ? AND semester = ? LIMIT 1',
                [$nama, $semester]
            );
        }

        return (bool) $this->nilai(
            'SELECT 1 FROM tahun_ajaran WHERE nama = ? AND semester = ? AND id <> ? LIMIT 1',
            [$nama, $semester, $kecualiId]
        );
    }

    public function buat(string $nama, string $semester): int
    {
        return $this->sisip(
            'INSERT INTO tahun_ajaran (nama, semester, aktif) VALUES (?, ?, 0)',
            [$nama, $semester]
        );
    }

    public function ubah(int $id, string $nama, string $semester): void
    {
        $this->jalankan(
            'UPDATE tahun_ajaran SET nama = ?, semester = ? WHERE id = ?',
            [$nama, $semester, $id]
        );
    }

    /**
     * Aktifkan satu tahun ajaran dan nonaktifkan sisanya dalam satu transaksi.
     */
    public function aktifkan(int $id): void
    {
        $this->db->beginTransaction();

        try {
            $this->jalankan('UPDATE tahun_ajaran SET aktif = 0 WHERE aktif = 1');
            $this->jalankan('UPDATE tahun_ajaran SET aktif = 1 WHERE id = ?', [$id]);
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function jumlahKelas(int $id): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM kelas WHERE tahun_ajaran_id = ?', [$id]);
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM tahun_ajaran WHERE id = ?', [$id]);
    }
}
