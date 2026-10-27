<?php

namespace App\Models;

use App\Core\Model;

/**
 * F10: soal pilihan ganda. Kunci jawaban tidak pernah dikirim ke siswa.
 */
final class Soal extends Model
{
    /** @return list<array> Termasuk kunci. Hanya untuk guru. */
    public function untukGuru(int $kuisId): array
    {
        return $this->semua(
            'SELECT id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci
               FROM soal WHERE kuis_id = ? ORDER BY id ASC',
            [$kuisId]
        );
    }

    /** @return list<array> Tanpa kolom kunci, dipakai halaman pengerjaan siswa. */
    public function untukSiswa(int $kuisId): array
    {
        return $this->semua(
            'SELECT id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e
               FROM soal WHERE kuis_id = ? ORDER BY id ASC',
            [$kuisId]
        );
    }

    public function cariDiKuis(int $id, int $kuisId): ?array
    {
        return $this->satu(
            'SELECT id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci
               FROM soal WHERE id = ? AND kuis_id = ? LIMIT 1',
            [$id, $kuisId]
        );
    }

    public function hitung(int $kuisId): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM soal WHERE kuis_id = ?', [$kuisId]);
    }

    /**
     * @param array<string,string|null> $opsi Kunci a..e.
     */
    public function buat(int $kuisId, string $pertanyaan, array $opsi, string $kunci): int
    {
        return $this->sisip(
            'INSERT INTO soal (kuis_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [$kuisId, $pertanyaan, $opsi['a'], $opsi['b'], $opsi['c'], $opsi['d'], $opsi['e'], $kunci]
        );
    }

    /**
     * @param array<string,string|null> $opsi
     */
    public function ubah(int $id, string $pertanyaan, array $opsi, string $kunci): void
    {
        $this->jalankan(
            'UPDATE soal SET pertanyaan = ?, opsi_a = ?, opsi_b = ?, opsi_c = ?,
                             opsi_d = ?, opsi_e = ?, kunci = ?
              WHERE id = ?',
            [$pertanyaan, $opsi['a'], $opsi['b'], $opsi['c'], $opsi['d'], $opsi['e'], $kunci, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM soal WHERE id = ?', [$id]);
    }
}
