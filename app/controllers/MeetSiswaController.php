<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Models\GoogleMeet;

/**
 * Ruang Google Meet & Kelas Tatap Muka Daring untuk Siswa.
 */
final class MeetSiswaController extends Controller
{
    public function index(): void
    {
        $kelasId = $this->kelasSiswa();

        $this->render('siswa/meet/index', [
            'judul' => 'Google Meet & Kelas Live',
            'data'  => (new GoogleMeet())->untukKelas($kelasId),
        ]);
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
