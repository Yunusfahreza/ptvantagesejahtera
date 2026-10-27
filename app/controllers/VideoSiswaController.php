<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FileDownloader;
use App\Core\HttpException;
use App\Models\Pengampu;
use App\Models\Video;

/**
 * F15: siswa menonton video pembelajaran.
 */
final class VideoSiswaController extends Controller
{
    public function index(string $id): void
    {
        $kelasId  = $this->kelasSiswa();
        $pengampu = (new Pengampu())->cariUntukKelas($this->idWajib($id), $kelasId);

        if ($pengampu === null) {
            throw HttpException::terlarang('Mata pelajaran ini bukan untuk kelas Anda.');
        }

        $this->render('siswa/video/index', [
            'judul'    => 'Video ' . $pengampu['mapel_nama'],
            'pengampu' => $pengampu,
            'data'     => (new Video())->untukPengampu((int) $pengampu['id']),
        ]);
    }

    public function tonton(string $id): void
    {
        $kelasId = $this->kelasSiswa();
        $video   = (new Video())->cariUntukKelas($this->idWajib($id), $kelasId);

        if ($video === null) {
            throw HttpException::terlarang('Video ini bukan untuk kelas Anda.');
        }

        $this->render('siswa/video/tonton', [
            'judul' => $video['judul'],
            'video' => $video,
        ]);
    }

    public function stream(string $id): void
    {
        $video = (new Video())->cariUntukKelas($this->idWajib($id), $this->kelasSiswa());

        if ($video === null || $video['tipe'] !== 'upload') {
            throw HttpException::tidakAda('Video tidak ditemukan.');
        }

        (new FileDownloader())->kirim((string) $video['file'], (string) $video['file_asli']);
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
