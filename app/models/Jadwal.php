<?php

namespace App\Models;

use App\Core\Model;

/**
 * F18: jadwal pelajaran kelas.
 */
final class Jadwal extends Model
{
    private const KOLOM = 'j.id, j.pengampu_id, j.hari, j.jam_mulai, j.jam_selesai, j.ruangan,
                           k.nama AS kelas_nama, m.nama AS mapel_nama, m.kode AS mapel_kode,
                           g.nama AS guru_nama';

    private const JOIN = 'FROM jadwal j
                          JOIN pengampu p ON p.id = j.pengampu_id
                          JOIN kelas k    ON k.id = p.kelas_id
                          JOIN mapel m    ON m.id = p.mapel_id
                          JOIN users g    ON g.id = p.guru_id';

    /**
     * Semua jadwal (admin view).
     *
     * @return list<array>
     */
    public function daftar(): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . "
           ORDER BY FIELD(j.hari, 'senin','selasa','rabu','kamis','jumat','sabtu'), j.jam_mulai ASC"
        );
    }

    /**
     * Jadwal untuk satu kelas.
     *
     * @return list<array>
     */
    public function untukKelas(int $kelasId): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . "
              WHERE p.kelas_id = ?
           ORDER BY FIELD(j.hari, 'senin','selasa','rabu','kamis','jumat','sabtu'), j.jam_mulai ASC",
            [$kelasId]
        );
    }

    /**
     * Jadwal mengajar guru.
     *
     * @return list<array>
     */
    public function untukGuru(int $guruId): array
    {
        return $this->semua(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . "
              WHERE p.guru_id = ?
           ORDER BY FIELD(j.hari, 'senin','selasa','rabu','kamis','jumat','sabtu'), j.jam_mulai ASC",
            [$guruId]
        );
    }

    public function cari(int $id): ?array
    {
        return $this->satu(
            'SELECT ' . self::KOLOM . ' ' . self::JOIN . ' WHERE j.id = ? LIMIT 1',
            [$id]
        );
    }

    public function buat(int $pengampuId, string $hari, string $jamMulai, string $jamSelesai, ?string $ruangan): int
    {
        return $this->sisip(
            'INSERT INTO jadwal (pengampu_id, hari, jam_mulai, jam_selesai, ruangan)
             VALUES (?, ?, ?, ?, ?)',
            [$pengampuId, $hari, $jamMulai, $jamSelesai, $ruangan]
        );
    }

    public function ubah(int $id, int $pengampuId, string $hari, string $jamMulai, string $jamSelesai, ?string $ruangan): void
    {
        $this->jalankan(
            'UPDATE jadwal SET pengampu_id = ?, hari = ?, jam_mulai = ?, jam_selesai = ?, ruangan = ? WHERE id = ?',
            [$pengampuId, $hari, $jamMulai, $jamSelesai, $ruangan, $id]
        );
    }

    public function hapus(int $id): void
    {
        $this->jalankan('DELETE FROM jadwal WHERE id = ?', [$id]);
    }
}
