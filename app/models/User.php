<?php

namespace App\Models;

use App\Core\Model;

/**
 * Pengguna sistem: admin, guru, siswa.
 */
final class User extends Model
{
    private const KOLOM = 'u.id, u.nama, u.username, u.role, u.kelas_id, u.status, u.created_at';

    public function cariAktifDenganUsername(string $username): ?array
    {
        return $this->satu(
            'SELECT id, nama, username, password_hash, role, kelas_id, status
               FROM users
              WHERE username = ? AND status = ?
              LIMIT 1',
            [$username, 'aktif']
        );
    }

    public function cariAktifDenganId(int $id): ?array
    {
        return $this->satu(
            'SELECT id, nama, username, password_hash, role, kelas_id, status
               FROM users
              WHERE id = ? AND status = ?
              LIMIT 1',
            [$id, 'aktif']
        );
    }

    public function cariDenganId(int $id): ?array
    {
        return $this->satu(
            'SELECT u.id, u.nama, u.username, u.role, u.kelas_id, u.status, u.created_at,
                    k.nama AS kelas_nama
               FROM users u
          LEFT JOIN kelas k ON k.id = u.kelas_id
              WHERE u.id = ?
              LIMIT 1',
            [$id]
        );
    }

    public function usernameDipakai(string $username, ?int $kecualiId = null): bool
    {
        if ($kecualiId === null) {
            return (bool) $this->nilai('SELECT 1 FROM users WHERE username = ? LIMIT 1', [$username]);
        }

        return (bool) $this->nilai(
            'SELECT 1 FROM users WHERE username = ? AND id <> ? LIMIT 1',
            [$username, $kecualiId]
        );
    }

    /**
     * Daftar pengguna dengan filter dan paginasi.
     *
     * @return array{data: list<array>, total: int}
     */
    public function daftar(string $cari, string $role, ?int $kelasId, string $status, int $halaman, int $perHalaman): array
    {
        [$where, $param] = $this->filter($cari, $role, $kelasId, $status);

        $total = (int) $this->nilai(
            'SELECT COUNT(*) FROM users u ' . $where,
            $param
        );

        $offset = max(0, ($halaman - 1) * $perHalaman);

        // LIMIT/OFFSET diinterpolasi setelah dipaksa jadi integer; bukan input mentah.
        $data = $this->semua(
            'SELECT ' . self::KOLOM . ', k.nama AS kelas_nama
               FROM users u
          LEFT JOIN kelas k ON k.id = u.kelas_id ' . $where . '
           ORDER BY u.role ASC, u.nama ASC
              LIMIT ' . (int) $perHalaman . ' OFFSET ' . (int) $offset,
            $param
        );

        return ['data' => $data, 'total' => $total];
    }

    /**
     * @return array{0:string,1:list<mixed>}
     */
    private function filter(string $cari, string $role, ?int $kelasId, string $status): array
    {
        $kondisi = [];
        $param   = [];

        if ($cari !== '') {
            $kondisi[] = '(u.nama LIKE ? OR u.username LIKE ?)';
            $param[]   = '%' . $cari . '%';
            $param[]   = '%' . $cari . '%';
        }

        if (in_array($role, ['admin', 'guru', 'siswa'], true)) {
            $kondisi[] = 'u.role = ?';
            $param[]   = $role;
        }

        if ($kelasId !== null && $kelasId > 0) {
            $kondisi[] = 'u.kelas_id = ?';
            $param[]   = $kelasId;
        }

        if (in_array($status, ['aktif', 'nonaktif'], true)) {
            $kondisi[] = 'u.status = ?';
            $param[]   = $status;
        }

        $where = $kondisi === [] ? '' : 'WHERE ' . implode(' AND ', $kondisi);

        return [$where, $param];
    }

    /** @return list<array> */
    public function daftarGuruAktif(): array
    {
        return $this->semua(
            "SELECT id, nama, username FROM users
              WHERE role = 'guru' AND status = 'aktif'
           ORDER BY nama ASC"
        );
    }

    /** @return list<array> */
    public function siswaDiKelas(int $kelasId): array
    {
        return $this->semua(
            "SELECT id, nama, username FROM users
              WHERE role = 'siswa' AND kelas_id = ? AND status = 'aktif'
           ORDER BY nama ASC",
            [$kelasId]
        );
    }

    public function buat(string $nama, string $username, string $passwordHash, string $role, ?int $kelasId): int
    {
        return $this->sisip(
            'INSERT INTO users (nama, username, password_hash, role, kelas_id, status)
             VALUES (?, ?, ?, ?, ?, ?)',
            [$nama, $username, $passwordHash, $role, $kelasId, 'aktif']
        );
    }

    public function ubah(int $id, string $nama, string $username, string $role, ?int $kelasId): void
    {
        $this->jalankan(
            'UPDATE users SET nama = ?, username = ?, role = ?, kelas_id = ? WHERE id = ?',
            [$nama, $username, $role, $kelasId, $id]
        );
    }

    public function ubahStatus(int $id, string $status): void
    {
        $this->jalankan('UPDATE users SET status = ? WHERE id = ?', [$status, $id]);
    }

    public function ubahPassword(int $id, string $passwordHash): void
    {
        $this->jalankan('UPDATE users SET password_hash = ? WHERE id = ?', [$passwordHash, $id]);
    }

    public function ubahProfilLengkap(int $id, ?string $nomorInduk, ?string $tempatLahir, ?string $tanggalLahir, ?string $alamat, ?string $noHp, ?string $noHpOrtu): void
    {
        $this->jalankan(
            'UPDATE users SET nomor_induk = ?, tempat_lahir = ?, tanggal_lahir = ?, alamat = ?, no_hp = ?, no_hp_ortu = ? WHERE id = ?',
            [$nomorInduk, $tempatLahir, $tanggalLahir, $alamat, $noHp, $noHpOrtu, $id]
        );
    }

    public function gantiFoto(int $id, string $foto): void
    {
        $this->jalankan('UPDATE users SET foto = ? WHERE id = ?', [$foto, $id]);
    }

    public function hashSaatIni(int $id): ?string
    {
        $hash = $this->nilai('SELECT password_hash FROM users WHERE id = ?', [$id]);

        return $hash === false ? null : (string) $hash;
    }

    /** @return array<string,int> */
    public function hitungPerRole(): array
    {
        $baris = $this->semua('SELECT role, COUNT(*) AS jumlah FROM users GROUP BY role');
        $hasil = ['admin' => 0, 'guru' => 0, 'siswa' => 0];

        foreach ($baris as $b) {
            $hasil[$b['role']] = (int) $b['jumlah'];
        }

        return $hasil;
    }

    /**
     * Guru yang masih terdaftar sebagai pengampu tidak boleh dihapus/diubah peran.
     */
    public function jumlahPengampu(int $guruId): int
    {
        return (int) $this->nilai('SELECT COUNT(*) FROM pengampu WHERE guru_id = ?', [$guruId]);
    }

    public function jumlahDataSiswa(int $siswaId): int
    {
        return (int) $this->nilai(
            'SELECT (SELECT COUNT(*) FROM pengumpulan WHERE siswa_id = ?)
                  + (SELECT COUNT(*) FROM hasil_kuis  WHERE siswa_id = ?)',
            [$siswaId, $siswaId]
        );
    }
}
