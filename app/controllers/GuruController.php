<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Kuis;
use App\Models\Materi;
use App\Models\Pengampu;
use App\Models\Tugas;

/**
 * F14: dashboard guru dan daftar kelas yang diampu.
 */
final class GuruController extends Controller
{
    public function dashboard(): void
    {
        $guruId = (int) Auth::id();

        $this->render('guru/dashboard', [
            'judul'          => 'Dashboard Guru',
            'pengampu'       => (new Pengampu())->milikGuru($guruId),
            'jumlah_materi'  => (new Materi())->hitungMilikGuru($guruId),
            'belum_dinilai'  => (new Tugas())->pengumpulanBelumDinilai($guruId),
            'tugas_mendatang'=> (new Tugas())->mendatangMilikGuru($guruId),
            'kuis_mendatang' => (new Kuis())->mendatangMilikGuru($guruId),
        ]);
    }

    public function kelasDiampu(): void
    {
        $this->render('guru/kelas', [
            'judul' => 'Kelas yang Saya Ampu',
            'data'  => (new Pengampu())->milikGuru((int) Auth::id()),
        ]);
    }
}
