<?php

/**
 * Daftar rute. Argumen ketiga = peran yang berhak:
 *   ['*'] publik, ['@'] semua pengguna login, atau daftar peran eksplisit.
 *
 * Pola: /{peran}/{modul}/{aksi}/{id?}
 *
 * Variabel $router disediakan oleh public/index.php.
 */

// --- Publik & umum -------------------------------------------------------

$router->get('/',          'HomeController@index',         ['*']);
$router->get('/login',     'AuthController@formLogin',     ['*']);
$router->post('/login',    'AuthController@login',         ['*']);
$router->post('/logout',   'AuthController@logout',        ['@']);
$router->get('/profil',    'AuthController@profil',        ['@']);
$router->get('/password',  'AuthController@formPassword',  ['@']);
$router->post('/password', 'AuthController@gantiPassword', ['@']);

// --- Admin ---------------------------------------------------------------

$router->get('/admin', 'AdminController@dashboard', ['admin']);

$router->get('/admin/tahun',           'TahunAjaranController@index',    ['admin']);
$router->post('/admin/tahun/simpan',   'TahunAjaranController@simpan',   ['admin']);
$router->post('/admin/tahun/aktifkan', 'TahunAjaranController@aktifkan', ['admin']);
$router->post('/admin/tahun/hapus',    'TahunAjaranController@hapus',    ['admin']);

$router->get('/admin/kelas',         'KelasController@index',  ['admin']);
$router->post('/admin/kelas/simpan', 'KelasController@simpan', ['admin']);
$router->post('/admin/kelas/hapus',  'KelasController@hapus',  ['admin']);

$router->get('/admin/mapel',         'MapelController@index',  ['admin']);
$router->post('/admin/mapel/simpan', 'MapelController@simpan', ['admin']);
$router->post('/admin/mapel/hapus',  'MapelController@hapus',  ['admin']);

$router->get('/admin/pengguna',            'PenggunaController@index',         ['admin']);
$router->get('/admin/pengguna/form',       'PenggunaController@form',          ['admin']);
$router->get('/admin/pengguna/form/{id}',  'PenggunaController@form',          ['admin']);
$router->post('/admin/pengguna/simpan',    'PenggunaController@simpan',        ['admin']);
$router->post('/admin/pengguna/status',    'PenggunaController@ubahStatus',    ['admin']);
$router->post('/admin/pengguna/reset',     'PenggunaController@resetPassword', ['admin']);
$router->get('/admin/pengguna/impor',      'PenggunaController@formImpor',     ['admin']);
$router->post('/admin/pengguna/impor',     'PenggunaController@impor',         ['admin']);
$router->get('/admin/pengguna/contoh-csv', 'PenggunaController@contohCsv',     ['admin']);

$router->get('/admin/pengampu',         'PengampuController@index',  ['admin']);
$router->post('/admin/pengampu/simpan', 'PengampuController@simpan', ['admin']);
$router->post('/admin/pengampu/hapus',  'PengampuController@hapus',  ['admin']);

// --- Guru ----------------------------------------------------------------

$router->get('/guru',       'GuruController@dashboard',   ['guru']);
$router->get('/guru/kelas', 'GuruController@kelasDiampu', ['guru']);

$router->get('/guru/materi',            'MateriGuruController@index',  ['guru']);
$router->get('/guru/materi/form',       'MateriGuruController@form',   ['guru']);
$router->get('/guru/materi/form/{id}',  'MateriGuruController@form',   ['guru']);
$router->post('/guru/materi/simpan',    'MateriGuruController@simpan', ['guru']);
$router->post('/guru/materi/hapus',     'MateriGuruController@hapus',  ['guru']);
$router->get('/guru/materi/unduh/{id}', 'MateriGuruController@unduh',  ['guru']);

$router->get('/guru/tugas',                  'TugasGuruController@index',            ['guru']);
$router->get('/guru/tugas/form',             'TugasGuruController@form',             ['guru']);
$router->get('/guru/tugas/form/{id}',        'TugasGuruController@form',             ['guru']);
$router->post('/guru/tugas/simpan',          'TugasGuruController@simpan',           ['guru']);
$router->post('/guru/tugas/hapus',           'TugasGuruController@hapus',            ['guru']);
$router->get('/guru/tugas/nilai/{id}',       'TugasGuruController@nilai',            ['guru']);
$router->post('/guru/tugas/nilai',           'TugasGuruController@simpanNilai',      ['guru']);
$router->get('/guru/tugas/lampiran/{id}',    'TugasGuruController@unduhLampiran',    ['guru']);
$router->get('/guru/tugas/pengumpulan/{id}', 'TugasGuruController@unduhPengumpulan', ['guru']);

$router->get('/guru/kuis',              'KuisGuruController@index',      ['guru']);
$router->get('/guru/kuis/form',         'KuisGuruController@form',       ['guru']);
$router->get('/guru/kuis/form/{id}',    'KuisGuruController@form',       ['guru']);
$router->post('/guru/kuis/simpan',      'KuisGuruController@simpan',     ['guru']);
$router->post('/guru/kuis/hapus',       'KuisGuruController@hapus',      ['guru']);
$router->get('/guru/kuis/soal/{id}',    'KuisGuruController@soal',       ['guru']);
$router->post('/guru/kuis/soal/simpan', 'KuisGuruController@simpanSoal', ['guru']);
$router->post('/guru/kuis/soal/hapus',  'KuisGuruController@hapusSoal',  ['guru']);
$router->get('/guru/kuis/hasil/{id}',   'KuisGuruController@hasil',      ['guru']);

$router->get('/guru/rekap',                    'RekapController@index',       ['guru']);
$router->get('/guru/rekap/kelas/{id}',         'RekapController@perPengampu', ['guru']);
$router->get('/guru/rekap/siswa/{id}/{siswa}', 'RekapController@perSiswa',    ['guru']);
$router->get('/guru/rekap/csv/{id}',           'RekapController@eksporCsv',   ['guru']);

$router->get('/guru/pengumuman',           'PengumumanGuruController@index',  ['guru']);
$router->get('/guru/pengumuman/form',      'PengumumanGuruController@form',   ['guru']);
$router->get('/guru/pengumuman/form/{id}', 'PengumumanGuruController@form',   ['guru']);
$router->post('/guru/pengumuman/simpan',   'PengumumanGuruController@simpan', ['guru']);
$router->post('/guru/pengumuman/hapus',    'PengumumanGuruController@hapus',  ['guru']);

// --- Siswa ---------------------------------------------------------------

$router->get('/siswa',       'SiswaController@dashboard', ['siswa']);
$router->get('/siswa/mapel', 'SiswaController@mapel',     ['siswa']);

$router->get('/siswa/materi/{id}',       'MateriSiswaController@index', ['siswa']);
$router->get('/siswa/materi/unduh/{id}', 'MateriSiswaController@unduh', ['siswa']);

$router->get('/siswa/tugas',               'TugasSiswaController@index',             ['siswa']);
$router->get('/siswa/tugas/detail/{id}',   'TugasSiswaController@detail',            ['siswa']);
$router->post('/siswa/tugas/kumpul',       'TugasSiswaController@kumpul',            ['siswa']);
$router->get('/siswa/tugas/lampiran/{id}', 'TugasSiswaController@unduhLampiran',     ['siswa']);
$router->get('/siswa/tugas/berkas/{id}',   'TugasSiswaController@unduhBerkasSendiri', ['siswa']);

$router->get('/siswa/kuis',               'KuisSiswaController@index',    ['siswa']);
$router->post('/siswa/kuis/mulai',        'KuisSiswaController@mulai',    ['siswa']);
$router->get('/siswa/kuis/kerjakan/{id}', 'KuisSiswaController@kerjakan', ['siswa']);
$router->post('/siswa/kuis/jawab',        'KuisSiswaController@jawab',    ['siswa']);
$router->post('/siswa/kuis/selesai',      'KuisSiswaController@selesai',  ['siswa']);
$router->get('/siswa/kuis/hasil/{id}',    'KuisSiswaController@hasil',    ['siswa']);

$router->get('/siswa/nilai',      'NilaiSiswaController@index',      ['siswa']);
$router->get('/siswa/pengumuman', 'PengumumanSiswaController@index', ['siswa']);
