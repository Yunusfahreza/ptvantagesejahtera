<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pengampu;
use App\Models\TahunAjaran;
use App\Models\User;

/**
 * F14: dashboard admin.
 */
final class AdminController extends Controller
{
    public function dashboard(): void
    {
        $this->render('admin/dashboard', [
            'judul'    => 'Dashboard Admin',
            'jumlah'   => (new User())->hitungPerRole(),
            'kelas'    => (new Kelas())->hitung(),
            'mapel'    => (new Mapel())->hitung(),
            'pengampu' => (new Pengampu())->hitung(),
            'tahun'    => (new TahunAjaran())->aktif(),
        ]);
    }
}
