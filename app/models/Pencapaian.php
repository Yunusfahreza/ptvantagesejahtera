<?php

namespace App\Models;

use App\Core\Model;

/**
 * F22: pencapaian / badge siswa.
 */
final class Pencapaian extends Model
{
    /**
     * Semua pencapaian satu siswa.
     *
     * @return list<array>
     */
    public function milikSiswa(int $siswaId): array
    {
        return $this->semua(
            'SELECT * FROM pencapaian WHERE siswa_id = ? ORDER BY diperoleh_at DESC',
            [$siswaId]
        );
    }

    /**
     * Cek apakah siswa sudah punya pencapaian tipe tertentu.
     */
    public function sudahPunya(int $siswaId, string $tipe): bool
    {
        return (bool) $this->nilai(
            'SELECT 1 FROM pencapaian WHERE siswa_id = ? AND tipe = ? LIMIT 1',
            [$siswaId, $tipe]
        );
    }

    /**
     * Berikan pencapaian baru.
     */
    public function berikan(int $siswaId, string $tipe, string $label, string $deskripsi, string $ikon = '🏆'): void
    {
        if ($this->sudahPunya($siswaId, $tipe)) {
            return;
        }

        $this->sisip(
            'INSERT INTO pencapaian (siswa_id, tipe, label, deskripsi, ikon) VALUES (?, ?, ?, ?, ?)',
            [$siswaId, $tipe, $label, $deskripsi, $ikon]
        );
    }

    /**
     * Hitung pencapaian satu siswa.
     */
    public function hitung(int $siswaId): int
    {
        return (int) $this->nilai(
            'SELECT COUNT(*) FROM pencapaian WHERE siswa_id = ?',
            [$siswaId]
        );
    }

    /**
     * Evaluasi dan berikan pencapaian otomatis berdasarkan data siswa.
     * Dipanggil setelah event tertentu (kumpul tugas, selesai kuis, dll).
     */
    public function evaluasi(int $siswaId): void
    {
        // Pengumpul tepat waktu: semua tugas dikumpulkan tepat waktu (min 3)
        $tepat = $this->satu(
            "SELECT COUNT(*) AS total,
                    SUM(status = 'tepat_waktu') AS tepat
               FROM pengumpulan WHERE siswa_id = ?",
            [$siswaId]
        );

        if ($tepat !== null && (int) $tepat['total'] >= 3 && (int) $tepat['total'] === (int) $tepat['tepat']) {
            $this->berikan($siswaId, 'tepat_waktu', 'Pengumpul Tepat Waktu', 'Semua tugas dikumpulkan tepat waktu', '⏰');
        }

        // Nilai sempurna: pernah dapat nilai 100 di tugas atau kuis
        $sempurna = $this->nilai(
            "SELECT 1 FROM pengumpulan WHERE siswa_id = ? AND nilai = 100 LIMIT 1",
            [$siswaId]
        );

        if (!$sempurna) {
            $sempurna = $this->nilai(
                "SELECT 1 FROM hasil_kuis WHERE siswa_id = ? AND nilai = 100 LIMIT 1",
                [$siswaId]
            );
        }

        if ($sempurna) {
            $this->berikan($siswaId, 'nilai_sempurna', 'Nilai Sempurna', 'Pernah mendapat nilai 100', '💯');
        }

        // Rajin belajar: sudah mengerjakan >= 5 kuis
        $jumlahKuis = (int) $this->nilai(
            'SELECT COUNT(*) FROM hasil_kuis WHERE siswa_id = ? AND selesai_at IS NOT NULL',
            [$siswaId]
        );

        if ($jumlahKuis >= 5) {
            $this->berikan($siswaId, 'rajin_kuis', 'Rajin Latihan', 'Telah mengerjakan 5 kuis atau lebih', '📝');
        }
    }

    /**
     * Ambil data leaderboard teman sekelas berdasarkan rerata nilai dan jumlah lencana.
     *
     * @return list<array>
     */
    public function leaderboardKelas(int $kelasId): array
    {
        return $this->semua(
            "SELECT u.id, u.nama, u.username,
                    ROUND(COALESCE(sub.rerata, 0), 1) AS nilai_rata,
                    COALESCE(sub_b.total_badge, 0) AS total_badge
               FROM users u
          LEFT JOIN (
                SELECT siswa_id, AVG(nilai) AS rerata
                  FROM (
                        SELECT siswa_id, nilai FROM pengumpulan WHERE nilai IS NOT NULL
                        UNION ALL
                        SELECT siswa_id, nilai FROM hasil_kuis WHERE selesai_at IS NOT NULL
                  ) gabung
                 GROUP BY siswa_id
          ) sub ON sub.siswa_id = u.id
          LEFT JOIN (
                SELECT siswa_id, COUNT(*) AS total_badge
                  FROM pencapaian
                 GROUP BY siswa_id
          ) sub_b ON sub_b.siswa_id = u.id
             WHERE u.kelas_id = ?
               AND u.role = 'siswa'
               AND u.status = 'aktif'
          ORDER BY nilai_rata DESC, total_badge DESC, u.nama ASC",
            [$kelasId]
        );
    }
}
