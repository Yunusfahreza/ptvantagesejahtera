<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FileDownloader;
use App\Core\HttpException;
use App\Models\Materi;
use App\Models\Pengampu;

/**
 * F06: siswa melihat dan mengunduh materi, hanya untuk kelasnya sendiri.
 */
final class MateriSiswaController extends Controller
{
    public function index(string $id): void
    {
        $kelasId = $this->kelasSiswa();

        // Pengampu harus berada di kelas siswa ini, kalau tidak -> 403 (T06).
        $pengampu = (new Pengampu())->cariUntukKelas($this->idWajib($id), $kelasId);

        if ($pengampu === null) {
            throw HttpException::terlarang('Mata pelajaran ini bukan untuk kelas Anda.');
        }

        $this->render('siswa/materi', [
            'judul'    => 'Materi ' . $pengampu['mapel_nama'],
            'pengampu' => $pengampu,
            'data'     => (new Materi())->untukPengampu((int) $pengampu['id']),
        ]);
    }

    public function unduh(string $id): void
    {
        $materi = (new Materi())->cariUntukKelas($this->idWajib($id), $this->kelasSiswa());

        if ($materi === null) {
            throw HttpException::terlarang('Materi ini bukan untuk kelas Anda.');
        }

        (new FileDownloader())->kirim((string) $materi['file'], (string) $materi['file_asli']);
    }

    public function lihat(string $id): void
    {
        $materi = (new Materi())->cariUntukKelas($this->idWajib($id), $this->kelasSiswa());

        if ($materi === null) {
            throw HttpException::terlarang('Materi ini bukan untuk kelas Anda.');
        }

        (new FileDownloader())->tampil((string) $materi['file'], (string) $materi['file_asli']);
    }

    private function kelasSiswa(): int
    {
        $kelasId = Auth::kelasId();

        if ($kelasId === null) {
            throw HttpException::terlarang(
                'Akun Anda belum ditempatkan di kelas. Hubungi administrator sekolah.'
            );
        }

        return $kelasId;
    }
}
