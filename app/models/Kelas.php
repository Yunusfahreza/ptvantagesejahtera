<?php

namespace App\Models;

use App\Core\Model;

/**
 * F03: kelas, selalu terikat pada satu tahun ajaran.
 */
final class Kelas extends Model
{
    /** @return list<array> */
    public function daftar(?int $tahunAjaranId = null): array
    {
        $where = $tahunAjaranId === null ? '' : 'WHERE k.tahun_ajaran_id = ?';
        $param = $tahunAjaranId === null ? [] : [$tahunAjaranId];

        return $this->semua(
            "SELECT k.id, k.nama, k.tingkat, k.tahun_ajaran_id,
                    t.nama AS tahun_nama, t.semester, t.aktif AS tahun_aktif,
                    (SELECT COUNT(*) FROM users u
                      WHERE u.kelas_id = k.id AND u.role = 'siswa') AS jumlah_siswa,
                    (SELECT COUNT(*) FROM pengampu p WHERE p.kelas_id = k.id) AS jumlah_pengampu
               FROM kelas k
               JOIN tahun_ajaran t ON t.id = k.tahun_ajaran_id " . $where . '
           ORDER BY t.aktif DESC, t.nama DESC, k.tingkat ASC, k.nama ASC',
            $param
        );
    }

    /**
     * Kelas pada tahun ajaran aktif. Dipakai di dropdown form.
     *
     * @return list<array>
     */
    public function daftarTahunAktif(): array
    {
        return $this->semua(
            'SELECT k.id, k.nama, k.tingkat
               FROM kelas k
               JOIN tahun_ajaran t ON t.id = k.tahun_ajaran_id
              WHERE t.aktif = 1
           ORDER BY k.tingkat ASC, k.nama ASC'
        );
    }

    /** @return list<array> */
    public function daftarRingkas(): array
    {
        return $this->semua(
            'SELECT k.id, k.nama, k.tingkat, t.nama AS tahun_nama, t.aktif AS tahun_aktif
               FROM kelas k
               JOIN tahun_ajaran t ON t.id = k.tahun_ajaran_id
           ORDER BY t.aktif DESC, k.tingkat ASC, k.nama ASC'
        );
    }

    public function cariDenganId(int $id): ?array
    {
        return $this->satu(
            'SELECT k.id, k.nama, k.tingkat, k.tahun_ajaran_id, t.nama AS tahun_nama
               FROM kelas k
               JOIN tahun_ajaran t ON t.id = k.tahun_ajaran_id
              WHERE k.id = ?',
            [$id]
        );
    }

    public function cariDenganNamaDiTahunAktif(string $nama): ?array
    {
        return $this->satu(
            'SELECT k.id, k.nama
               FROM kelas k
               JOIN tahun_ajaran t ON t.id = k.tahun_ajaran_id
              WHERE k.nama = ? AND t.aktif = 1
              LIMIT 1',
            [$nama]
        );
    }

    public function dipakai(string $nama, int $tahunAjaranId, ?int $kecualiId = null): bool
    {
        if ($kecualiId === null) {
            return (bool) $this->nilai(
                'SELECT 1 FROM kelas WHERE nama = ? AND tahun_ajaran_id = ? LIMIT 1',
                [$nama, $tahunAjaranId]
            );
        }

        return (bool) $this->nilai(
            'SELECT 1 FROM kelas WHERE nama = ? AND tahun_ajaran_id = ? AND id <> ? LIMIT 1',
            [$nama, $tahunAjaranId, $kecualiId]
        );
    }

    public function buat(string $nama, string $tingkat, int $tahunAjaranId): int
    {
        return $this->sisip(
            'INSERT INTO kelas (nama, tingkat, tahun_ajaran_id) VALUES (?, ?, ?)',
            [$nama, $tingkat, $tahunAjaranId]
        );
    }

    public function ubah(int $id, string $nama, string $tingkat, int $tahunAjaranId): void
    {
        $this->jalankan(
            'UPDATE kelas SET nama = ?, tingkat = ?, tahun_ajaran_id = ? WHERE id = ?',
            [$nama, $tingkat, $tahunAjaranId, $id]
        );
    }

    /**
     * Jumlah data yang masih menunjuk kelas ini. Hapus ditolak kalau > 0.
     */
    public function jumlahRelasi(int $id): int
    {
        return (int) $this->nilai(
            'SELECT (SELECT COUNT(*) FROM users    WHERE kelas_id = ?)
                  + (SELECT COUNT(*) FROM pengampu WHERE kelas_id = ?)',
            [$id, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM kelas WHERE id = ?', [$id]);
    }

    public function hitung(): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM kelas');
    }
}
