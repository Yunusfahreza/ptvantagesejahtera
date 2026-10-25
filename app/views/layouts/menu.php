<?php

use App\Core\Auth;

/**
 * Menu navbar per peran. Menyembunyikan menu hanya kenyamanan UI;
 * penolakan akses yang sebenarnya ada di Router dan Controller.
 */

return match (Auth::peran()) {
    'admin' => [
        ['label' => 'Dashboard',     'path' => '/admin'],
        ['label' => 'Pengguna',      'path' => '/admin/pengguna'],
        ['label' => 'Tahun Ajaran',  'path' => '/admin/tahun'],
        ['label' => 'Kelas',         'path' => '/admin/kelas'],
        ['label' => 'Mata Pelajaran','path' => '/admin/mapel'],
        ['label' => 'Guru Pengampu', 'path' => '/admin/pengampu'],
    ],
    'guru' => [
        ['label' => 'Dashboard',   'path' => '/guru'],
        ['label' => 'Kelas Saya',  'path' => '/guru/kelas'],
        ['label' => 'Materi',      'path' => '/guru/materi'],
        ['label' => 'Tugas',       'path' => '/guru/tugas'],
        ['label' => 'Kuis',        'path' => '/guru/kuis'],
        ['label' => 'Rekap Nilai', 'path' => '/guru/rekap'],
        ['label' => 'Pengumuman',  'path' => '/guru/pengumuman'],
    ],
    'siswa' => [
        ['label' => 'Dashboard',   'path' => '/siswa'],
        ['label' => 'Mata Pelajaran', 'path' => '/siswa/mapel'],
        ['label' => 'Tugas',       'path' => '/siswa/tugas'],
        ['label' => 'Kuis',        'path' => '/siswa/kuis'],
        ['label' => 'Nilai Saya',  'path' => '/siswa/nilai'],
        ['label' => 'Pengumuman',  'path' => '/siswa/pengumuman'],
    ],
    default => [],
};
