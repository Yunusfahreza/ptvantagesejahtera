<?php
$kartu = [
    ['label' => 'Total Guru',          'nilai' => $jumlah['guru'],  'sub' => 'Tenaga Pengajar Aktif', 'path' => '/admin/pengguna?role=guru', 'icon' => 'blue', 'svg' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/>'],
    ['label' => 'Total Siswa',         'nilai' => $jumlah['siswa'], 'sub' => 'Peserta Didik Terdaftar', 'path' => '/admin/pengguna?role=siswa', 'icon' => 'green', 'svg' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
    ['label' => 'Rombel Kelas',        'nilai' => $kelas,           'sub' => 'Kelas Aktif Sekolah', 'path' => '/admin/kelas', 'icon' => 'amber', 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
    ['label' => 'Mata Pelajaran',      'nilai' => $mapel,           'sub' => 'Kurikulum Nasional', 'path' => '/admin/mapel', 'icon' => 'purple', 'svg' => '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>'],
    ['label' => 'Guru Pengampu',       'nilai' => $pengampu,        'sub' => 'Plotting Penugasan', 'path' => '/admin/pengampu', 'icon' => 'rose', 'svg' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
];
?>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Administrator Sekolah</span>
            <span>&bull;</span>
            <span>Pusat Kontrol Sistem</span>
        </div>
        <h1 class="page-title">Dashboard Administrator</h1>
        <p class="page-subtitle">Ringkasan operasional data induk, penugasan kurikulum, dan konfigurasi LMS SMAN 10 Garut</p>
    </div>

    <div class="d-flex align-items-center gap-2 p-2 px-3.5 rounded-pill" style="background: rgba(37, 99, 235, 0.08); border: 1.5px solid rgba(37, 99, 235, 0.25);">
        <span class="badge rounded-circle p-1" style="background: var(--accent-sapphire); width: 8px; height: 8px;"></span>
        <span class="small fw-bold" style="color: var(--slate-secondary);">Tahun Ajaran:</span>
        <?php if ($tahun === null): ?>
            <span class="badge bg-warning-subtle text-warning border px-2 py-0.5">Belum Diatur</span>
            <a class="small fw-bold text-decoration-none ms-1" style="color: var(--accent-sapphire);" href="<?= e(url('/admin/tahun')) ?>">Atur Sekarang &rarr;</a>
        <?php else: ?>
            <strong style="color: var(--slate-primary);"><?= e($tahun['nama']) ?> &bull; Semester <?= e($tahun['semester']) ?></strong>
        <?php endif; ?>
    </div>
</div>

<!-- 5 Bento Stat Cards Grid -->
<div class="row g-3 mb-4">
    <?php foreach ($kartu as $k): ?>
        <div class="col-6 col-md-4 col-xl">
            <a class="text-decoration-none" href="<?= e(url($k['path'])) ?>">
                <div class="card h-100 bento-stat-card border-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="bento-stat-label"><?= e($k['label']) ?></span>
                        <div class="squircle-icon squircle-icon-<?= $k['icon'] ?>" style="width: 36px; height: 36px; border-radius: 9px;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <?= $k['svg'] ?>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="bento-stat-number stat-counter" data-counter="<?= e($k['nilai']) ?>">
                            <?= e($k['nilai']) ?>
                        </div>
                        <div class="bento-stat-subtext"><?= e($k['sub']) ?></div>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<!-- Onboarding Roadmap & Setup Wizard -->
<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-blue" style="width: 30px; height: 30px; border-radius: 8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Panduan Konfigurasi & Setup Sekolah</span>
                </div>
            </div>

            <div class="card-body p-4">
                <p class="small mb-4" style="color: var(--slate-tertiary); line-height: 1.5;">
                    Ikuti tahapan di bawah ini untuk memastikan data master akademik telah terhubung dengan benar dan siap digunakan:
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge rounded-circle p-1 fw-bold fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: rgba(37, 99, 235, 0.1); color: var(--accent-sapphire);">1</div>
                            <div>
                                <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;">Atur Tahun Ajaran & Semester Aktif</div>
                                <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">Pastikan terdapat minimal 1 tahun ajaran dengan status 'Aktif'</div>
                            </div>
                        </div>
                        <a href="<?= e(url('/admin/tahun')) ?>" class="btn btn-sm btn-outline-primary px-3 py-1.5 fw-semibold">Kelola Tahun</a>
                    </div>

                    <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge rounded-circle p-1 fw-bold fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: rgba(5, 150, 105, 0.1); color: var(--accent-emerald);">2</div>
                            <div>
                                <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;">Rombongan Belajar (Kelas)</div>
                                <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">Bentuk kelas (misal: X-MIPA 1, XI-IPA) pada tahun ajaran yang sedang aktif</div>
                            </div>
                        </div>
                        <a href="<?= e(url('/admin/kelas')) ?>" class="btn btn-sm btn-outline-primary px-3 py-1.5 fw-semibold">Kelola Kelas</a>
                    </div>

                    <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge rounded-circle p-1 fw-bold fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: rgba(217, 119, 6, 0.1); color: var(--accent-amber);">3</div>
                            <div>
                                <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;">Mata Pelajaran Kurikulum</div>
                                <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">Daftarkan seluruh kode mapel dan nama mata pelajaran wajib & peminatan</div>
                            </div>
                        </div>
                        <a href="<?= e(url('/admin/mapel')) ?>" class="btn btn-sm btn-outline-primary px-3 py-1.5 fw-semibold">Kelola Mapel</a>
                    </div>

                    <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge rounded-circle p-1 fw-bold fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: rgba(147, 51, 234, 0.1); color: #9333ea;">4</div>
                            <div>
                                <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;">Data Pengguna (Guru & Siswa)</div>
                                <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">Tambah akun guru dan daftarkan siswa ke kelasnya masing-masing (dapat melalui impor CSV)</div>
                            </div>
                        </div>
                        <a href="<?= e(url('/admin/pengguna')) ?>" class="btn btn-sm btn-outline-primary px-3 py-1.5 fw-semibold">Kelola Akun</a>
                    </div>

                    <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge rounded-circle p-1 fw-bold fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: rgba(225, 29, 72, 0.1); color: var(--accent-rose);">5</div>
                            <div>
                                <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;">Penugasan Guru Pengampu</div>
                                <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">Hubungkan guru ke kelas dan mata pelajaran yang diajarkan</div>
                            </div>
                        </div>
                        <a href="<?= e(url('/admin/pengampu')) ?>" class="btn btn-sm btn-outline-primary px-3 py-1.5 fw-semibold">Plotting Guru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Actions -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3">
                <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Tindakan Cepat</span>
            </div>
            <div class="card-body p-4 d-flex flex-column gap-2.5">
                <a href="<?= e(url('/admin/pengguna/impor')) ?>" class="btn btn-outline-primary py-2.5 d-flex align-items-center justify-content-between text-start fw-semibold">
                    <span class="d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <span>Impor Massal Pengguna CSV</span>
                    </span>
                    <span>&rarr;</span>
                </a>

                <a href="<?= e(url('/admin/jadwal')) ?>" class="btn btn-outline-secondary py-2.5 d-flex align-items-center justify-content-between text-start fw-semibold">
                    <span class="d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>Jadwal Pelajaran Sekolah</span>
                    </span>
                    <span>&rarr;</span>
                </a>

                <a href="<?= e(url('/perpustakaan')) ?>" class="btn btn-outline-secondary py-2.5 d-flex align-items-center justify-content-between text-start fw-semibold">
                    <span class="d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                        </svg>
                        <span>Katalog E-Perpustakaan</span>
                    </span>
                    <span>&rarr;</span>
                </a>

                <a href="<?= e(url('/kalender')) ?>" class="btn btn-outline-secondary py-2.5 d-flex align-items-center justify-content-between text-start fw-semibold">
                    <span class="d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        </svg>
                        <span>Kalender Agenda Pendidikan</span>
                    </span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>
