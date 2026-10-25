<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Models\Pengumuman;

/**
 * F13: siswa melihat pengumuman kelasnya, terbaru di atas.
 */
final class PengumumanSiswaController extends Controller
{
    public function index(): void
    {
        $kelasId = Auth::kelasId();

        if ($kelasId === null) {
            throw HttpException::terlarang(
                'Akun Anda belum ditempatkan di kelas. Hubungi administrator sekolah.'
            );
        }

        $this->render('siswa/pengumuman', [
            'judul' => 'Pengumuman',
            'data'  => (new Pengumuman())->untukKelas($kelasId),
        ]);
    }
}
