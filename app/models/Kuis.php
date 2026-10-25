<?php

namespace App\Models;

use App\Core\Model;

/**
 * F10, F11: kuis pilihan ganda.
 *
 * Semua perhitungan waktu memakai NOW() dari MySQL dan batas pengerjaan
 * dihitung sebagai MIN(mulai_at + durasi_menit, kuis.selesai), sesuai PRD.
 */
final class Kuis extends Model
{
    /** @return list<array> */
    public function milikGuru(int $guruId): array
    {
        return $this->semua(
            'SELECT q.id, q.judul, q.durasi_menit, q.mulai, q.selesai, q.pengampu_id,
                    k.nama AS kelas_nama, m.nama AS mapel_nama, m.kode AS mapel_kode,
                    (SELECT COUNT(*) FROM soal s WHERE s.kuis_id = q.id) AS jumlah_soal,
                    (SELECT COUNT(*) FROM hasil_kuis h WHERE h.kuis_id = q.id) AS jumlah_peserta
               FROM kuis q
               JOIN pengampu p ON p.id = q.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.guru_id = ?
           ORDER BY q.mulai DESC',
            [$guruId]
        );
    }

    public function cariMilikGuru(int $id, int $guruId): ?array
    {
        return $this->satu(
            'SELECT q.id, q.judul, q.durasi_menit, q.mulai, q.selesai, q.pengampu_id,
                    p.kelas_id, k.nama AS kelas_nama, m.nama AS mapel_nama,
                    (SELECT COUNT(*) FROM soal s WHERE s.kuis_id = q.id) AS jumlah_soal,
                    (SELECT COUNT(*) FROM hasil_kuis h WHERE h.kuis_id = q.id) AS jumlah_peserta
               FROM kuis q
               JOIN pengampu p ON p.id = q.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE q.id = ? AND p.guru_id = ?
              LIMIT 1',
            [$id, $guruId]
        );
    }

    /**
     * Daftar kuis untuk siswa, dengan status jendela waktu dan hasilnya.
     *
     * @return list<array>
     */
    public function untukSiswa(int $siswaId, int $kelasId): array
    {
        return $this->semua(
            'SELECT q.id, q.judul, q.durasi_menit, q.mulai, q.selesai,
                    m.nama AS mapel_nama, m.kode AS mapel_kode,
                    (SELECT COUNT(*) FROM soal s WHERE s.kuis_id = q.id) AS jumlah_soal,
                    h.id AS hasil_id, h.nilai, h.mulai_at, h.selesai_at,
                    NOW() BETWEEN q.mulai AND q.selesai AS sedang_dibuka,
                    NOW() < q.mulai AS belum_dibuka
               FROM kuis q
               JOIN pengampu p ON p.id = q.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
          LEFT JOIN hasil_kuis h ON h.kuis_id = q.id AND h.siswa_id = ?
              WHERE p.kelas_id = ?
           ORDER BY q.mulai DESC',
            [$siswaId, $kelasId]
        );
    }

    /**
     * Satu kuis untuk siswa beserta batas waktu efektif yang dihitung server.
     */
    public function cariUntukSiswa(int $id, int $siswaId, int $kelasId): ?array
    {
        return $this->satu(
            'SELECT q.id, q.judul, q.durasi_menit, q.mulai, q.selesai,
                    m.nama AS mapel_nama, g.nama AS guru_nama,
                    (SELECT COUNT(*) FROM soal s WHERE s.kuis_id = q.id) AS jumlah_soal,
                    h.id AS hasil_id, h.nilai, h.mulai_at, h.selesai_at,
                    NOW() AS waktu_server,
                    NOW() BETWEEN q.mulai AND q.selesai AS sedang_dibuka,
                    LEAST(
                        DATE_ADD(h.mulai_at, INTERVAL q.durasi_menit MINUTE),
                        q.selesai
                    ) AS batas_waktu
               FROM kuis q
               JOIN pengampu p ON p.id = q.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
               JOIN users g    ON g.id = p.guru_id
          LEFT JOIN hasil_kuis h ON h.kuis_id = q.id AND h.siswa_id = ?
              WHERE q.id = ? AND p.kelas_id = ?
              LIMIT 1',
            [$siswaId, $id, $kelasId]
        );
    }

    /** @return list<array> */
    public function terbukaUntukSiswa(int $siswaId, int $kelasId, int $batas = 5): array
    {
        return $this->semua(
            'SELECT q.id, q.judul, q.selesai, m.nama AS mapel_nama,
                    h.id AS hasil_id, h.selesai_at
               FROM kuis q
               JOIN pengampu p ON p.id = q.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
          LEFT JOIN hasil_kuis h ON h.kuis_id = q.id AND h.siswa_id = ?
              WHERE p.kelas_id = ? AND NOW() BETWEEN q.mulai AND q.selesai
           ORDER BY q.selesai ASC
              LIMIT ' . (int) $batas,
            [$siswaId, $kelasId]
        );
    }

    /** @return list<array> */
    public function mendatangMilikGuru(int $guruId, int $batas = 5): array
    {
        return $this->semua(
            'SELECT q.id, q.judul, q.mulai, q.selesai,
                    k.nama AS kelas_nama, m.nama AS mapel_nama
               FROM kuis q
               JOIN pengampu p ON p.id = q.pengampu_id
               JOIN kelas k    ON k.id = p.kelas_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE p.guru_id = ? AND q.selesai >= NOW()
           ORDER BY q.mulai ASC
              LIMIT ' . (int) $batas,
            [$guruId]
        );
    }

    /** @return list<array> Kuis satu pengampu, dipakai rekap nilai. */
    public function untukPengampu(int $pengampuId): array
    {
        return $this->semua(
            'SELECT id, judul, mulai FROM kuis
              WHERE pengampu_id = ?
           ORDER BY mulai ASC, id ASC',
            [$pengampuId]
        );
    }

    public function buat(int $pengampuId, string $judul, int $durasi, string $mulai, string $selesai): int
    {
        return $this->sisip(
            'INSERT INTO kuis (pengampu_id, judul, durasi_menit, mulai, selesai)
             VALUES (?, ?, ?, ?, ?)',
            [$pengampuId, $judul, $durasi, $mulai, $selesai]
        );
    }

    public function ubah(int $id, int $pengampuId, string $judul, int $durasi, string $mulai, string $selesai): void
    {
        $this->jalankan(
            'UPDATE kuis SET pengampu_id = ?, judul = ?, durasi_menit = ?, mulai = ?, selesai = ?
              WHERE id = ?',
            [$pengampuId, $judul, $durasi, $mulai, $selesai, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM kuis WHERE id = ?', [$id]);
    }

    public function jumlahHasil(int $id): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM hasil_kuis WHERE kuis_id = ?', [$id]);
    }
}
