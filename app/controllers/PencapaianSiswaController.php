<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Models\Pencapaian;

/**
 * F21 & F22: Gamifikasi, Badge Pencapaian, dan Leaderboard Kelas Siswa.
 */
final class PencapaianSiswaController extends Controller
{
    public function index(): void
    {
        $siswaId = (int) Auth::id();
        $kelasId = Auth::kelasId();

        if ($kelasId === null) {
            throw HttpException::terlarang('Akun Anda belum ditempatkan di kelas.');
        }

        $pModel = new Pencapaian();
        // Cek evaluasi badge otomatis terbaru
        $pModel->evaluasi($siswaId);

        $badgeSaya = $pModel->milikSiswa($siswaId);

        // Ambil data leaderboard teman sekelas
        $leaderboard = $pModel->leaderboardKelas($kelasId);

        $this->render('siswa/pencapaian', [
            'judul'       => 'Prestasi & Peringkat Kelas',
            'badgeSaya'   => $badgeSaya,
            'leaderboard' => $leaderboard,
            'siswaId'     => $siswaId,
        ]);
    }
}
