<?php

namespace App\Models;

use App\Core\Model;

/**
 * F19: absensi / catatan kehadiran siswa.
 */
final class Absensi extends Model
{
    /**
     * Ambil data absensi per pengampu + tanggal.
     * Semua siswa di kelas ditampilkan, baik sudah dicatat maupun belum.
     *
     * @return list<array>
     */
    public function daftarPerPengampuTanggal(int $pengampuId, string $tanggal, int $kelasId): array
    {
        return $this->semua(
            "SELECT u.id AS siswa_id, u.nama, u.username,
                    a.status, a.keterangan, a.id AS absensi_id
               FROM users u
          LEFT JOIN absensi a ON a.siswa_id = u.id
                             AND a.pengampu_id = ?
                             AND a.tanggal = ?
              WHERE u.kelas_id = ?
                AND u.role = 'siswa'
                AND u.status = 'aktif'
           ORDER BY u.nama ASC",
            [$pengampuId, $tanggal, $kelasId]
        );
    }

    /**
     * Simpan atau perbarui absensi (upsert).
     */
    public function simpan(int $pengampuId, int $siswaId, string $tanggal, string $status, ?string $keterangan): void
    {
        $ada = $this->satu(
            'SELECT id FROM absensi WHERE pengampu_id = ? AND siswa_id = ? AND tanggal = ? LIMIT 1',
            [$pengampuId, $siswaId, $tanggal]
        );

        if ($ada !== null) {
            $this->jalankan(
                'UPDATE absensi SET status = ?, keterangan = ? WHERE id = ?',
                [$status, $keterangan, (int) $ada['id']]
            );
        } else {
            $this->sisip(
                'INSERT INTO absensi (pengampu_id, siswa_id, tanggal, status, keterangan) VALUES (?, ?, ?, ?, ?)',
                [$pengampuId, $siswaId, $tanggal, $status, $keterangan]
            );
        }
    }

    /**
     * Rekap kehadiran siswa per pengampu.
     */
    public function rekapSiswa(int $siswaId, int $pengampuId): array
    {
        $data = $this->satu(
            "SELECT
                COUNT(*) AS total,
                SUM(status = 'hadir') AS hadir,
                SUM(status = 'izin')  AS izin,
                SUM(status = 'sakit') AS sakit,
                SUM(status = 'alpha') AS alpha
               FROM absensi
              WHERE siswa_id = ? AND pengampu_id = ?",
            [$siswaId, $pengampuId]
        );

        return $data ?? ['total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
    }

    /**
     * Rekap seluruh kehadiran satu siswa di semua mapel.
     *
     * @return list<array>
     */
    public function rekapLengkapSiswa(int $siswaId): array
    {
        return $this->semua(
            "SELECT m.nama AS mapel_nama, m.kode AS mapel_kode,
                    COUNT(*) AS total,
                    SUM(a.status = 'hadir') AS hadir,
                    SUM(a.status = 'izin')  AS izin,
                    SUM(a.status = 'sakit') AS sakit,
                    SUM(a.status = 'alpha') AS alpha
               FROM absensi a
               JOIN pengampu p ON p.id = a.pengampu_id
               JOIN mapel m    ON m.id = p.mapel_id
              WHERE a.siswa_id = ?
           GROUP BY p.mapel_id, m.nama, m.kode
           ORDER BY m.nama ASC",
            [$siswaId]
        );
    }

    /**
     * Daftar tanggal yang sudah ada absensinya untuk satu pengampu.
     *
     * @return list<string>
     */
    public function tanggalTercatat(int $pengampuId): array
    {
        $rows = $this->semua(
            'SELECT DISTINCT tanggal FROM absensi WHERE pengampu_id = ? ORDER BY tanggal DESC LIMIT 30',
            [$pengampuId]
        );

        return array_column($rows, 'tanggal');
    }
}
