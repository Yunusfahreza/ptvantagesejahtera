<?php

namespace App\Models;

use App\Core\Model;
use PDOException;
use Throwable;

/**
 * F11: percobaan kuis siswa, penyimpanan jawaban, dan finalisasi nilai.
 *
 * Semua keputusan waktu diambil dari NOW() MySQL. Jam browser hanya untuk
 * tampilan countdown.
 */
final class HasilKuis extends Model
{
    /**
     * Mulai percobaan. UNIQUE(kuis_id, siswa_id) menjamin satu percobaan
     * saja walau ada dua request bersamaan; tabrakan dibaca sebagai
     * "sudah pernah mulai".
     *
     * @return array{id:int,baru:bool}|null null kalau di luar jendela waktu.
     */
    public function mulai(int $kuisId, int $siswaId): ?array
    {
        $ada = $this->percobaan($kuisId, $siswaId);

        if ($ada !== null) {
            return ['id' => $ada, 'baru' => false];
        }

        try {
            // INSERT ... SELECT dengan syarat jendela waktu: kalau kuis belum
            // dibuka atau sudah ditutup, tidak ada baris yang masuk.
            $jumlah = $this->jalankan(
                'INSERT INTO hasil_kuis (kuis_id, siswa_id, mulai_at)
                 SELECT q.id, ?, NOW()
                   FROM kuis q
                  WHERE q.id = ? AND NOW() BETWEEN q.mulai AND q.selesai',
                [$siswaId, $kuisId]
            );
        } catch (PDOException $e) {
            // 23000 = pelanggaran constraint unik: percobaan sudah ada.
            if ($e->getCode() !== '23000') {
                throw $e;
            }

            $ada = $this->percobaan($kuisId, $siswaId);

            return $ada === null ? null : ['id' => $ada, 'baru' => false];
        }

        if ($jumlah === 0) {
            return null;
        }

        return ['id' => (int) $this->db->lastInsertId(), 'baru' => true];
    }

    private function percobaan(int $kuisId, int $siswaId): ?int
    {
        $baris = $this->satu(
            'SELECT id FROM hasil_kuis WHERE kuis_id = ? AND siswa_id = ? LIMIT 1',
            [$kuisId, $siswaId]
        );

        return $baris === null ? null : (int) $baris['id'];
    }

    /**
     * Ambil percobaan milik siswa beserta status waktunya dari server.
     */
    public function cariMilikSiswa(int $kuisId, int $siswaId): ?array
    {
        return $this->satu(
            'SELECT h.id, h.kuis_id, h.siswa_id, h.nilai, h.mulai_at, h.selesai_at,
                    q.judul, q.durasi_menit, q.selesai AS kuis_selesai,
                    NOW() AS waktu_server,
                    LEAST(DATE_ADD(h.mulai_at, INTERVAL q.durasi_menit MINUTE), q.selesai) AS batas_waktu,
                    NOW() > LEAST(DATE_ADD(h.mulai_at, INTERVAL q.durasi_menit MINUTE), q.selesai)
                        AS waktu_habis
               FROM hasil_kuis h
               JOIN kuis q ON q.id = h.kuis_id
              WHERE h.kuis_id = ? AND h.siswa_id = ?
              LIMIT 1',
            [$kuisId, $siswaId]
        );
    }

    /**
     * Simpan satu jawaban. Upsert supaya siswa bisa mengubah pilihannya.
     * Ditolak kalau percobaan sudah final atau waktunya habis.
     */
    public function simpanJawaban(int $hasilId, int $soalId, string $jawaban): bool
    {
        // Pastikan soal benar-benar milik kuis percobaan ini, percobaan belum
        // final, dan batas waktu server belum terlampaui.
        $sah = (bool) $this->nilai(
            'SELECT 1
               FROM hasil_kuis h
               JOIN soal s ON s.kuis_id = h.kuis_id
               JOIN kuis q ON q.id = h.kuis_id
              WHERE h.id = ? AND s.id = ? AND h.selesai_at IS NULL
                AND NOW() <= LEAST(DATE_ADD(h.mulai_at, INTERVAL q.durasi_menit MINUTE), q.selesai)
              LIMIT 1',
            [$hasilId, $soalId]
        );

        if (!$sah) {
            return false;
        }

        $this->jalankan(
            'INSERT INTO jawaban_kuis (hasil_kuis_id, soal_id, jawaban)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE jawaban = VALUES(jawaban)',
            [$hasilId, $soalId, $jawaban]
        );

        return true;
    }

    /**
     * @return array<int,string> Peta soal_id => jawaban yang sudah tersimpan.
     */
    public function jawabanTersimpan(int $hasilId): array
    {
        $baris = $this->semua(
            'SELECT soal_id, jawaban FROM jawaban_kuis WHERE hasil_kuis_id = ?',
            [$hasilId]
        );

        $peta = [];

        foreach ($baris as $b) {
            if ($b['jawaban'] !== null) {
                $peta[(int) $b['soal_id']] = (string) $b['jawaban'];
            }
        }

        return $peta;
    }

    /**
     * Finalisasi: hitung nilai dari jawaban yang SUDAH tersimpan.
     *
     * Dipanggil saat submit manual maupun saat ada request setelah batas
     * waktu. Idempoten: percobaan yang sudah final tidak dihitung ulang.
     *
     * Nilai = benar / jumlah soal * 100, dibulatkan 2 desimal.
     * Soal yang tidak dijawab dihitung salah.
     */
    public function finalisasi(int $hasilId): void
    {
        $this->db->beginTransaction();

        try {
            // Kunci baris supaya dua request bersamaan tidak menilai dua kali.
            $hasil = $this->satu(
                'SELECT id, kuis_id, selesai_at FROM hasil_kuis WHERE id = ? FOR UPDATE',
                [$hasilId]
            );

            if ($hasil === null || $hasil['selesai_at'] !== null) {
                $this->db->commit();

                return;
            }

            $jumlahSoal = (int) $this->nilai(
                'SELECT COUNT(*) FROM soal WHERE kuis_id = ?',
                [(int) $hasil['kuis_id']]
            );

            $benar = (int) $this->nilai(
                'SELECT COUNT(*)
                   FROM jawaban_kuis j
                   JOIN soal s ON s.id = j.soal_id
                  WHERE j.hasil_kuis_id = ? AND j.jawaban IS NOT NULL AND j.jawaban = s.kunci',
                [$hasilId]
            );

            $nilai = $jumlahSoal === 0 ? 0.0 : round($benar / $jumlahSoal * 100, 2);

            $this->jalankan(
                'UPDATE hasil_kuis SET nilai = ?, selesai_at = NOW() WHERE id = ?',
                [$nilai, $hasilId]
            );

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();

            throw $e;
        }
    }

    /**
     * Finalisasi semua percobaan yang waktunya sudah habis tapi belum final.
     * Dipanggil saat guru atau siswa membuka halaman hasil, sesuai PRD F11.
     */
    public function finalisasiYangKedaluwarsa(int $kuisId): void
    {
        $tertunda = $this->semua(
            'SELECT h.id
               FROM hasil_kuis h
               JOIN kuis q ON q.id = h.kuis_id
              WHERE h.kuis_id = ? AND h.selesai_at IS NULL
                AND NOW() > LEAST(DATE_ADD(h.mulai_at, INTERVAL q.durasi_menit MINUTE), q.selesai)',
            [$kuisId]
        );

        foreach ($tertunda as $t) {
            $this->finalisasi((int) $t['id']);
        }
    }

    /**
     * Hasil semua siswa kelas itu untuk satu kuis, termasuk yang belum ikut.
     *
     * @return list<array>
     */
    public function daftarUntukKuis(int $kuisId, int $kelasId): array
    {
        return $this->semua(
            "SELECT u.id AS siswa_id, u.nama AS siswa_nama,
                    h.id AS hasil_id, h.nilai, h.mulai_at, h.selesai_at,
                    (SELECT COUNT(*) FROM jawaban_kuis j
                      WHERE j.hasil_kuis_id = h.id AND j.jawaban IS NOT NULL) AS jumlah_dijawab
               FROM users u
          LEFT JOIN hasil_kuis h ON h.siswa_id = u.id AND h.kuis_id = ?
              WHERE u.kelas_id = ? AND u.role = 'siswa' AND u.status = 'aktif'
           ORDER BY u.nama ASC",
            [$kuisId, $kelasId]
        );
    }

    /**
     * Nilai kuis per siswa untuk satu pengampu. Dipakai rekap (F12).
     *
     * @return list<array>
     */
    public function nilaiUntukPengampu(int $pengampuId): array
    {
        return $this->semua(
            'SELECT h.siswa_id, h.kuis_id, h.nilai
               FROM hasil_kuis h
               JOIN kuis q ON q.id = h.kuis_id
              WHERE q.pengampu_id = ?',
            [$pengampuId]
        );
    }

    /** @return list<array> Nilai kuis satu siswa di seluruh mapel. */
    public function nilaiSiswa(int $siswaId): array
    {
        return $this->semua(
            'SELECT q.judul, q.mulai, h.nilai, h.mulai_at, h.selesai_at,
                    m.nama AS mapel_nama, m.kode AS mapel_kode,
                    (SELECT COUNT(*) FROM soal s WHERE s.kuis_id = q.id) AS jumlah_soal
               FROM hasil_kuis h
               JOIN kuis q     ON q.id = h.kuis_id
               JOIN pengampu p ON p.id = q.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE h.siswa_id = ?
           ORDER BY m.nama ASC, q.mulai ASC',
            [$siswaId]
        );
    }
}
