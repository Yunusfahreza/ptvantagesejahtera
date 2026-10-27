<?php

namespace App\Core;

/**
 * Router sederhana dengan pola /{peran}/{modul}/{aksi}/{id?}.
 *
 * Setiap rute mendaftarkan peran yang berhak. Pemeriksaan peran terjadi di
 * sini (lapisan pertama); kepemilikan data tetap diperiksa di controller
 * (lapisan kedua), sesuai PRD bagian 5.
 */
final class Router
{
    /** @var list<array{metode:string,pola:string,aksi:string,peran:list<string>}> */
    private array $rute = [];

    /**
     * @param list<string> $peran Daftar peran yang boleh. ['*'] = publik,
     *                            ['@'] = semua pengguna yang sudah login.
     */
    public function get(string $pola, string $aksi, array $peran): void
    {
        $this->tambah('GET', $pola, $aksi, $peran);
    }

    public function post(string $pola, string $aksi, array $peran): void
    {
        $this->tambah('POST', $pola, $aksi, $peran);
    }

    private function tambah(string $metode, string $pola, string $aksi, array $peran): void
    {
        $this->rute[] = [
            'metode' => $metode,
            'pola'   => '/' . trim($pola, '/'),
            'aksi'   => $aksi,
            'peran'  => $peran,
        ];
    }

    public function jalankan(string $metode, string $path): void
    {
        $path = '/' . trim($path, '/');
        $adaPolaCocok = false;

        foreach ($this->rute as $rute) {
            $param = $this->cocok($rute['pola'], $path);

            if ($param === null) {
                continue;
            }

            $adaPolaCocok = true;

            if ($rute['metode'] !== $metode) {
                continue;
            }

            $this->jagaAkses($rute['peran']);
            $this->panggil($rute['aksi'], $param);

            return;
        }

        // Pola ada tapi metodenya beda -> tetap 404 agar tidak membocorkan rute.
        unset($adaPolaCocok);

        throw HttpException::tidakAda();
    }

    /**
     * Cocokkan pola terhadap path. Segmen {nama} menjadi parameter.
     *
     * @return list<string>|null null kalau tidak cocok.
     */
    private function cocok(string $pola, string $path): ?array
    {
        $segPola = $pola === '/' ? [] : explode('/', trim($pola, '/'));
        $segPath = $path === '/' ? [] : explode('/', trim($path, '/'));

        // Segmen opsional ditandai {nama?}
        $wajib = 0;
        foreach ($segPola as $s) {
            if (!str_ends_with($s, '?}')) {
                $wajib++;
            }
        }

        if (count($segPath) < $wajib || count($segPath) > count($segPola)) {
            return null;
        }

        $param = [];

        foreach ($segPola as $i => $s) {
            $nilai = $segPath[$i] ?? null;

            if (str_starts_with($s, '{')) {
                if ($nilai !== null) {
                    $param[] = $nilai;
                }
                continue;
            }

            if ($nilai !== $s) {
                return null;
            }
        }

        return $param;
    }

    /**
     * Lapisan pertama hak akses: cek sesi dan peran sebelum controller jalan.
     */
    private function jagaAkses(array $peran): void
    {
        if ($peran === ['*']) {
            return;
        }

        if (!Auth::sudahLogin()) {
            // Simpan tujuan supaya bisa dikembalikan setelah login.
            if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
                $_SESSION['_tujuan'] = $_SERVER['REQUEST_URI'] ?? null;
            }

            redirect('/login');
        }

        if ($peran === ['@']) {
            return;
        }

        if (!Auth::punyaPeran(...$peran)) {
            throw HttpException::terlarang();
        }
    }

    private function panggil(string $aksi, array $param): void
    {
        [$kelas, $metode] = explode('@', $aksi, 2);
        $fqcn = 'App' . chr(92) . 'Controllers' . chr(92) . $kelas;

        if (!class_exists($fqcn) || !method_exists($fqcn, $metode)) {
            throw HttpException::tidakAda();
        }

        (new $fqcn())->{$metode}(...$param);
    }
}
