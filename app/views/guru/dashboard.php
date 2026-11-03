<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Portal Pengajar</span>
            <span>&bull;</span>
            <span>Tahun Ajaran 2026/2027 Ganjil</span>
        </div>
        <h1 class="page-title">Dashboard Guru</h1>
        <p class="page-subtitle">Pusat kendali aktivitas belajar-mengajar, monitoring materi, dan penilaian tugas siswa</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <a href="<?= e(url('/guru/tugas')) ?>" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
            </svg>
            <span>Periksa Tugas Masuk</span>
        </a>
    </div>
</div>

<!-- Bento Grid Top Row: 4 Uniform Glass Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Card 1: Kelas & Mapel -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/kelas')) ?>">
            <div class="card h-100 bento-stat-card border-0">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="bento-stat-label">Kelas Diampu</span>
                    <div class="squircle-icon squircle-icon-blue" style="width: 38px; height: 38px; border-radius: 10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                            <path d="M6 6h10"/><path d="M6 10h10"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="bento-stat-number stat-counter" data-counter="<?= count($pengampu) ?>">
                        <?= count($pengampu) ?>
                    </div>
                    <div class="bento-stat-subtext">Rombel aktif semester ini</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 2: Materi Diunggah -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/materi')) ?>">
            <div class="card h-100 bento-stat-card border-0">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="bento-stat-label">Materi Unggahan</span>
                    <div class="squircle-icon squircle-icon-green" style="width: 38px; height: 38px; border-radius: 10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="bento-stat-number stat-counter" data-counter="<?= $jumlah_materi ?>">
                        <?= $jumlah_materi ?>
                    </div>
                    <div class="bento-stat-subtext">Dokumen modul terbit</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 3: Belum Dinilai -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/tugas')) ?>">
            <div class="card h-100 bento-stat-card border-0">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="bento-stat-label">Belum Dinilai</span>
                    <div class="squircle-icon squircle-icon-amber" style="width: 38px; height: 38px; border-radius: 10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="bento-stat-number stat-counter" data-counter="<?= array_sum(array_column($belum_dinilai, 'jumlah')) ?>">
                        <?= array_sum(array_column($belum_dinilai, 'jumlah')) ?>
                    </div>
                    <div class="bento-stat-subtext">Pengumpulan tugas siswa</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 4: Kuis Aktif -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/kuis')) ?>">
            <div class="card h-100 bento-stat-card border-0">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="bento-stat-label">Kuis Berjalan</span>
                    <div class="squircle-icon squircle-icon-purple" style="width: 38px; height: 38px; border-radius: 10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="bento-stat-number stat-counter" data-counter="<?= count($kuis_mendatang) ?>">
                        <?= count($kuis_mendatang) ?>
                    </div>
                    <div class="bento-stat-subtext">Jadwal asesmen aktif</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Panels Section -->
<div class="row g-4">
    <!-- Panel 1: Menunggu Dinilai -->
    <div class="col-12 col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-amber" style="width: 30px; height: 30px; border-radius: 8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Tugas Menunggu Pemeriksaan</span>
                </div>
                <span class="badge px-2.5 py-1" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-tertiary); font-size: 0.72rem; border-radius: 6px;">
                    <?= count($belum_dinilai) ?> Tugas
                </span>
            </div>

            <div class="card-body p-3.5">
                <?php if ($belum_dinilai === []): ?>
                    <div class="empty-state-box p-4 text-center">
                        <div class="empty-state-icon mx-auto" style="width: 44px; height: 44px; margin-bottom: 0.5rem;">
                            âœ“
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--slate-primary);">Semua Tugas Telah Dinilai</h6>
                        <p class="small text-muted mb-0">Tidak ada pengumpulan tugas siswa yang tertunda saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($belum_dinilai as $index => $b): ?>
                            <a class="text-decoration-none" href="<?= e(url('/guru/tugas/nilai/' . $b['id'])) ?>">
                                <div class="p-3 rounded-3 d-flex justify-content-between align-items-center transition-all"
                                     style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(226, 232, 240, 0.9); box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="squircle-icon squircle-icon-amber" style="width: 36px; height: 36px; border-radius: 9px;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-0.5" style="color: var(--slate-primary); font-size: 0.92rem;">
                                                <?= e($b['judul']) ?>
                                            </div>
                                            <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">
                                                <?= e($b['kelas_nama']) ?> &bull; <?= e($b['mapel_nama']) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fw-bold" style="font-size: 0.74rem;">
                                            <?= e($b['jumlah']) ?> Menunggu
                                        </span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--slate-muted);">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Panel 2: Agenda Tenggat & Kuis Mendatang -->
    <div class="col-12 col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-blue" style="width: 30px; height: 30px; border-radius: 8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Tenggat & Kuis Berjalan</span>
                </div>
                <span class="badge px-2.5 py-1" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-tertiary); font-size: 0.72rem; border-radius: 6px;">
                    Jadwal Terjadwal
                </span>
            </div>

            <div class="card-body p-3.5">
                <?php if ($tugas_mendatang === [] && $kuis_mendatang === []): ?>
                    <div class="empty-state-box p-4 text-center">
                        <div class="empty-state-icon mx-auto" style="width: 44px; height: 44px; margin-bottom: 0.5rem;">
                            â˜•
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--slate-primary);">Tidak Ada Jadwal Mendatang</h6>
                        <p class="small text-muted mb-0">Belum ada tenggat tugas atau kuis yang dijadwalkan dalam waktu dekat.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($tugas_mendatang as $t): ?>
                            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center"
                                 style="background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="squircle-icon squircle-icon-blue" style="width: 36px; height: 36px; border-radius: 9px;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-bold mb-0.5" style="color: var(--slate-primary); font-size: 0.92rem;">
                                            <?= e($t['judul']) ?>
                                        </div>
                                        <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">
                                            <?= e($t['kelas_nama']) ?> &bull; <?= e($t['mapel_nama']) ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1" style="font-size: 0.74rem;">
                                    Tenggat <?= e(tanggal_id($t['tenggat'])) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach ($kuis_mendatang as $q): ?>
                            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center"
                                 style="background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="squircle-icon squircle-icon-purple" style="width: 36px; height: 36px; border-radius: 9px;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-bold mb-0.5" style="color: var(--slate-primary); font-size: 0.92rem;">
                                            <?= e($q['judul']) ?>
                                        </div>
                                        <div class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">
                                            <?= e($q['kelas_nama']) ?> &bull; <?= e($q['mapel_nama']) ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-purple-subtle text-dark border border-secondary-subtle px-2.5 py-1" style="font-size: 0.74rem;">
                                    Buka <?= e(tanggal_id($q['mulai'])) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
