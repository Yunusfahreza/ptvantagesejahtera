<?php

namespace App\Core;

use App\Models\Kelas;
use App\Models\User;

/**
 * F02: impor siswa dari CSV.
 *
 * Format kolom: nama,username,password,kelas
 * Kolom "kelas" diisi nama kelas yang SUDAH ada pada tahun ajaran aktif.
 *
 * Baris yang valid tetap masuk; baris gagal dilaporkan beserta nomor baris
 * dan alasannya.
 */
final class ImporCsvSiswa
{
    private const KOLOM = ['nama', 'username', 'password', 'kelas'];
    private const PASSWORD_MIN = 8;
    private const MAKS_BARIS = 2000;

    public function __construct(
        private User $user = new User(),
        private Kelas $kelas = new Kelas(),
    ) {
    }

    /**
     * @return array{berhasil:int, gagal:list<array{baris:int,alasan:string,isi:string}>}
     */
    public function dariBerkas(string $path): array
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            return ['berhasil' => 0, 'gagal' => [['baris' => 0, 'alasan' => 'Berkas tidak bisa dibaca.', 'isi' => '']]];
        }

        try {
            return $this->proses($handle);
        } finally {
            fclose($handle);
        }
    }

    /**
     * @param resource $handle
     * @return array{berhasil:int, gagal:list<array{baris:int,alasan:string,isi:string}>}
     */
    private function proses($handle): array
    {
        $pemisah = $this->tebakPemisah($handle);

        $berhasil = 0;
        $gagal    = [];
        $nomor    = 0;
        $header   = null;
        $usernameDiBerkas = [];

        while (($kolom = fgetcsv($handle, 0, $pemisah)) !== false) {
            $nomor++;

            if ($nomor > self::MAKS_BARIS) {
                $gagal[] = [
                    'baris'  => $nomor,
                    'alasan' => 'Melebihi batas ' . self::MAKS_BARIS . ' baris per berkas.',
                    'isi'    => '',
                ];
                break;
            }

            // Baris kosong dilewati tanpa dilaporkan sebagai gagal.
            if ($kolom === [null] || $this->barisKosong($kolom)) {
                continue;
            }

            if ($header === null) {
                $header = $this->bacaHeader($kolom);

                if ($header === null) {
                    return [
                        'berhasil' => 0,
                        'gagal'    => [$this->barisGagal(
                            $nomor,
                            'Header CSV harus berisi kolom: ' . implode(', ', self::KOLOM) . '.',
                            $kolom
                        )],
                    ];
                }

                continue;
            }

            $data   = $this->ambilKolom($kolom, $header);
            $alasan = $this->validasi($data, $usernameDiBerkas);

            if ($alasan !== null) {
                $gagal[] = $this->barisGagal($nomor, $alasan, $kolom);
                continue;
            }

            $this->simpanSiswa($data);
            $usernameDiBerkas[$data['username']] = true;
            $berhasil++;
        }

        if ($header === null) {
            return [
                'berhasil' => 0,
                'gagal'    => [['baris' => 0, 'alasan' => 'Berkas CSV kosong.', 'isi' => '']],
            ];
        }

        return ['berhasil' => $berhasil, 'gagal' => $gagal];
    }

    /**
     * Satu entri laporan baris gagal.
     *
     * @return array{baris:int,alasan:string,isi:string}
     */
    private function barisGagal(int $nomor, string $alasan, array $kolom): array
    {
        return [
            'baris'  => $nomor,
            'alasan' => $alasan,
            'isi'    => implode(',', array_map('strval', $kolom)),
        ];
    }

    /**
     * Simpan satu baris yang sudah lolos validasi.
     *
     * @param array<string,string> $data
     */
    private function simpanSiswa(array $data): void
    {
        // Kelas dipastikan ada oleh validasi, jadi aman diambil langsung.
        $kelas = $this->kelas->cariDenganNamaDiTahunAktif($data['kelas']);

        $this->user->buat(
            $data['nama'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            'siswa',
            (int) $kelas['id']
        );
    }

    /**
     * Excel versi Indonesia sering menulis CSV dengan titik koma.
     *
     * @param resource $handle
     */
    private function tebakPemisah($handle): string
    {
        $baris = (string) fgets($handle);
        rewind($handle);

        // Buang BOM UTF-8 kalau ada.
        $baris = preg_replace('#^\xEF\xBB\xBF#', '', $baris) ?? $baris;

        return substr_count($baris, ';') > substr_count($baris, ',') ? ';' : ',';
    }

    private function barisKosong(array $kolom): bool
    {
        foreach ($kolom as $nilai) {
            if (trim((string) $nilai) !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * Petakan nama kolom header ke indeksnya.
     *
     * @return array<string,int>|null null kalau ada kolom wajib yang hilang.
     */
    private function bacaHeader(array $kolom): ?array
    {
        $peta = [];

        foreach ($kolom as $i => $nama) {
            $nama = strtolower(trim((string) $nama));
            $nama = (string) preg_replace('#^\xEF\xBB\xBF#', '', $nama);

            if (in_array($nama, self::KOLOM, true)) {
                $peta[$nama] = $i;
            }
        }

        foreach (self::KOLOM as $wajib) {
            if (!isset($peta[$wajib])) {
                return null;
            }
        }

        return $peta;
    }

    /**
     * @param array<string,int> $header
     * @return array<string,string>
     */
    private function ambilKolom(array $kolom, array $header): array
    {
        $data = [];

        foreach ($header as $nama => $i) {
            $data[$nama] = trim((string) ($kolom[$i] ?? ''));
        }

        // Username selalu disimpan huruf kecil supaya konsisten dengan form manual.
        $data['username'] = mb_strtolower($data['username'] ?? '');

        return $data;
    }

    /**
     * @param array<string,string> $data
     * @param array<string,bool>   $usernameDiBerkas
     */
    private function validasi(array $data, array $usernameDiBerkas): ?string
    {
        return match (true) {
            $data['nama'] === ''
                => 'Kolom nama kosong.',
            mb_strlen($data['nama']) > 100
                => 'Nama lebih dari 100 karakter.',
            preg_match('#^[a-z0-9._-]{3,50}$#', $data['username']) !== 1
                => 'Username tidak valid (3 sampai 50 karakter: huruf kecil, angka, titik, garis bawah, strip).',
            isset($usernameDiBerkas[$data['username']])
                => 'Username duplikat di dalam berkas yang sama.',
            $this->user->usernameDipakai($data['username'])
                => 'Username sudah terdaftar di sistem.',
            mb_strlen($data['password']) < self::PASSWORD_MIN
                => 'Password kurang dari ' . self::PASSWORD_MIN . ' karakter.',
            $data['kelas'] === ''
                => 'Kolom kelas kosong.',
            $this->kelas->cariDenganNamaDiTahunAktif($data['kelas']) === null
                => 'Kelas "' . $data['kelas'] . '" tidak ada di tahun ajaran aktif.',
            default => null,
        };
    }
}
