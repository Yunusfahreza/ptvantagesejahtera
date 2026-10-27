<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Models\Kuis;
use App\Models\Materi;
use App\Models\Pengampu;
use App\Models\Pengumuman;
use App\Models\Tugas;

/**
 * F14: dashboard dan daftar mata pelajaran siswa.
 */
final class SiswaController extends Controller
{
    public function dashboard(): void
    {
        $kelasId = $this->kelasSiswa();
        $siswaId = (int) Auth::id();

        $this->render('siswa/dashboard', [
            'judul'          => 'Dashboard Siswa',
            'mapel'          => (new Pengampu())->untukKelas($kelasId),
            'tugas_mendekat' => (new Tugas())->mendekatiTenggatUntukSiswa($siswaId, $kelasId),
            'kuis_terbuka'   => (new Kuis())->terbukaUntukSiswa($siswaId, $kelasId),
            'pengumuman'     => (new Pengumuman())->terbaruUntukKelas($kelasId, 5),
            'jumlah_materi'  => (new Materi())->hitungUntukKelas($kelasId),
        ]);
    }

    public function mapel(): void
    {
        $this->render('siswa/mapel', [
            'judul' => 'Mata Pelajaran',
            'data'  => (new Pengampu())->untukKelas($this->kelasSiswa()),
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
