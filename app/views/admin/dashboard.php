<?php
$kartu = [
    ['label' => 'Guru',          'nilai' => $jumlah['guru'],  'path' => '/admin/pengguna?role=guru'],
    ['label' => 'Siswa',         'nilai' => $jumlah['siswa'], 'path' => '/admin/pengguna?role=siswa'],
    ['label' => 'Kelas',         'nilai' => $kelas,           'path' => '/admin/kelas'],
    ['label' => 'Mata Pelajaran','nilai' => $mapel,           'path' => '/admin/mapel'],
    ['label' => 'Guru Pengampu', 'nilai' => $pengampu,        'path' => '/admin/pengampu'],
];
?>
<h1 class="h4 mb-1">Dashboard Admin</h1>
<p class="text-body-secondary">
    Tahun ajaran aktif:
    <?php if ($tahun === null): ?>
        <span class="badge text-bg-warning">belum ada</span>
        <a class="small ms-1" href="<?= e(url('/admin/tahun')) ?>">atur sekarang</a>
    <?php else: ?>
        <strong><?= e($tahun['nama']) ?></strong> &middot; semester <?= e($tahun['semester']) ?>
    <?php endif; ?>
</p>

<div class="row g-3 mb-4">
    <?php foreach ($kartu as $k): ?>
        <div class="col-6 col-md-4 col-lg">
            <a class="text-decoration-none" href="<?= e(url($k['path'])) ?>">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <p class="display-6 fw-bold mb-0 text-primary"><?= e($k['nilai']) ?></p>
                        <p class="text-body-secondary small mb-0"><?= e($k['label']) ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h2 class="h6 mb-3">Langkah penyiapan sekolah</h2>
        <ol class="mb-0 ps-3">
            <li><a href="<?= e(url('/admin/tahun')) ?>">Tahun ajaran</a> &mdash; pastikan ada satu yang aktif.</li>
            <li><a href="<?= e(url('/admin/kelas')) ?>">Kelas</a> &mdash; buat kelas untuk tahun ajaran aktif.</li>
            <li><a href="<?= e(url('/admin/mapel')) ?>">Mata pelajaran</a> &mdash; daftar mapel yang diajarkan.</li>
            <li><a href="<?= e(url('/admin/pengguna')) ?>">Pengguna</a> &mdash; tambah guru dan siswa (bisa impor CSV).</li>
            <li><a href="<?= e(url('/admin/pengampu')) ?>">Guru pengampu</a> &mdash; pasangkan guru dengan kelas dan mapel.</li>
        </ol>
    </div>
</div>
