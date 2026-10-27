<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Models\Absensi;

/**
 * F19: siswa melihat rekap presensi / kehadiran pribadinya.
 */
final class AbsensiSiswaController extends Controller
{
    public function index(): void
    {
        $siswaId = (int) Auth::id();
        $kelasId = Auth::kelasId();

        if ($kelasId === null) {
            throw HttpException::terlarang('Akun Anda belum ditempatkan di kelas.');
        }

        $rekap = (new Absensi())->rekapLengkapSiswa($siswaId);

        $this->render('siswa/absensi', [
            'judul' => 'Kehadiran & Presensi Saya',
            'rekap' => $rekap,
        ]);
    }
}
