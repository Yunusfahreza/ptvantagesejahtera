<!-- Apple Principal Design: Bento Grid Dashboard Guru (iOS 27 / visionOS Spatial Interface) -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 font-medium text-white tracking-tight" style="letter-spacing: -0.03em; color: rgba(255, 255, 255, 0.92);">
            Dashboard Guru
        </h1>
        <p class="mb-0 small" style="color: rgba(255, 255, 255, 0.5);">
            Tinjauan aktivitas mengajar, pengumpulan tugas, dan jadwal kuis
        </p>
    </div>
    <div class="d-none d-md-flex align-items-center gap-2">
        <span class="badge" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.65); padding: 0.45rem 0.85rem; font-size: 0.78rem;">
            Semester Ganjil 2026/2027
        </span>
    </div>
</div>

<!-- Bento Grid Top Row: 4 Uniform Squircle Glass Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Card 1: Kelas & Mapel -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/kelas')) ?>">
            <div class="card h-100 spatial-stat-card border-0">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="spatial-label">Kelas Diampu</span>
                        <div class="p-2 rounded-circle" style="background: rgba(255, 255, 255, 0.04);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(255, 255, 255, 0.45);">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                <path d="M6 6h10"/>
                                <path d="M6 10h10"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="display-6 spatial-number mb-1"><?= e(count($pengampu)) ?></div>
                        <div class="small" style="color: rgba(255, 255, 255, 0.45); font-size: 0.8rem;">
                            Rombel aktif semester ini
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 2: Materi Diunggah -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/materi')) ?>">
            <div class="card h-100 spatial-stat-card border-0">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="spatial-label">Materi Unggahan</span>
                        <div class="p-2 rounded-circle" style="background: rgba(255, 255, 255, 0.04);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(255, 255, 255, 0.45);">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <line x1="10" y1="9" x2="8" y2="9"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="display-6 spatial-number mb-1"><?= e($jumlah_materi) ?></div>
                        <div class="small" style="color: rgba(255, 255, 255, 0.45); font-size: 0.8rem;">
                            Dokumen terpublikasi
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 3: Belum Dinilai -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/tugas')) ?>">
            <div class="card h-100 spatial-stat-card border-0">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="spatial-label">Belum Dinilai</span>
                        <div class="p-2 rounded-circle" style="background: rgba(129, 140, 248, 0.08);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--spatial-indigo);">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="display-6 spatial-number mb-1" style="color: #FFFFFF;">
                            <?= e(array_sum(array_column($belum_dinilai, 'jumlah'))) ?>
                        </div>
                        <div class="small" style="color: rgba(255, 255, 255, 0.45); font-size: 0.8rem;">
                            Menunggu penilaian
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 4: Kuis Aktif / Mendatang -->
    <div class="col-6 col-lg-3">
        <a class="text-decoration-none" href="<?= e(url('/guru/kuis')) ?>">
            <div class="card h-100 spatial-stat-card border-0">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="spatial-label">Kuis Aktif</span>
                        <div class="p-2 rounded-circle" style="background: rgba(255, 255, 255, 0.04);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(255, 255, 255, 0.45);">
                                <path d="m9 11 3 3L22 4"/>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="display-6 spatial-number mb-1"><?= e(count($kuis_mendatang)) ?></div>
                        <div class="small" style="color: rgba(255, 255, 255, 0.45); font-size: 0.8rem;">
                            Jadwal evaluasi
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Bottom Row: Wider Liquid Glass Panels -->
<div class="row g-4">
    <!-- Panel 1: Menunggu Dinilai (Active Item illuminated inner border) -->
    <div class="col-12 col-lg-6">
        <div class="card h-100 spatial-panel border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--spatial-indigo);">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                    <span>Menunggu Dinilai</span>
                </div>
                <span class="small" style="color: rgba(255, 255, 255, 0.4); font-size: 0.78rem;">
                    <?= e(count($belum_dinilai)) ?> tugas aktif
                </span>
            </div>

            <div class="card-body p-3">
                <?php if ($belum_dinilai === []): ?>
                    <div class="text-center py-5" style="color: rgba(255, 255, 255, 0.4);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 opacity-50">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <p class="small mb-0">Semua pengumpulan tugas telah selesai diperiksa.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($belum_dinilai as $index => $b): ?>
                            <!-- Active item has illuminated inner border (border-l-2 border-l-indigo-400 bg-white/[0.06]) -->
                            <a class="text-decoration-none" href="<?= e(url('/guru/tugas/nilai/' . $b['id'])) ?>">
                                <div class="p-3 d-flex justify-content-between align-items-center transition-all <?= $index === 0 ? 'spatial-active-item' : 'rounded-3' ?>"
                                     style="<?= $index !== 0 ? 'background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04);' : '' ?>">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2 rounded-circle" style="background: <?= $index === 0 ? 'rgba(129, 140, 248, 0.15)' : 'rgba(255, 255, 255, 0.04)' ?>;">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: <?= $index === 0 ? 'var(--spatial-indigo)' : 'rgba(255, 255, 255, 0.5)' ?>;">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                                <line x1="16" y1="13" x2="8" y2="13"/>
                                                <line x1="16" y1="17" x2="8" y2="17"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="fw-medium text-white mb-0" style="font-size: 0.9rem; letter-spacing: -0.01em;">
                                                <?= e($b['judul']) ?>
                                            </div>
                                            <div class="small" style="color: rgba(255, 255, 255, 0.45); font-size: 0.78rem;">
                                                <?= e($b['kelas_nama']) ?> &middot; <?= e($b['mapel_nama']) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12); color: #FFFFFF; font-weight: 500; font-size: 0.78rem; padding: 0.35rem 0.65rem;">
                                            <?= e($b['jumlah']) ?> siswa
                                        </span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: rgba(255, 255, 255, 0.3);">
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

    <!-- Panel 2: Tenggat & Kuis Mendatang -->
    <div class="col-12 col-lg-6">
        <div class="card h-100 spatial-panel border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--spatial-blue);">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>Tenggat & Kuis Mendatang</span>
                </div>
                <span class="small" style="color: rgba(255, 255, 255, 0.4); font-size: 0.78rem;">
                    Agenda berjalan
                </span>
            </div>

            <div class="card-body p-3">
                <?php if ($tugas_mendatang === [] && $kuis_mendatang === []): ?>
                    <div class="text-center py-5" style="color: rgba(255, 255, 255, 0.4);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 opacity-50">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <p class="small mb-0">Belum ada tenggat tugas atau kuis dalam waktu dekat.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($tugas_mendatang as $t): ?>
                            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center"
                                 style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04);">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded-circle" style="background: rgba(56, 189, 248, 0.1);">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--spatial-blue);">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <polyline points="9 15 12 18 15 15"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-white mb-0" style="font-size: 0.9rem; letter-spacing: -0.01em;">
                                            <?= e($t['judul']) ?>
                                        </div>
                                        <div class="small" style="color: rgba(255, 255, 255, 0.45); font-size: 0.78rem;">
                                            <?= e($t['kelas_nama']) ?> &middot; <?= e($t['mapel_nama']) ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge" style="background: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.2); color: var(--spatial-blue); font-size: 0.76rem;">
                                    Tenggat <?= e(tanggal_id($t['tenggat'])) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach ($kuis_mendatang as $q): ?>
                            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center"
                                 style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04);">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded-circle" style="background: rgba(129, 140, 248, 0.1);">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--spatial-indigo);">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polygon points="10 8 16 12 10 16 10 8"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-white mb-0" style="font-size: 0.9rem; letter-spacing: -0.01em;">
                                            <?= e($q['judul']) ?>
                                        </div>
                                        <div class="small" style="color: rgba(255, 255, 255, 0.45); font-size: 0.78rem;">
                                            <?= e($q['kelas_nama']) ?> &middot; <?= e($q['mapel_nama']) ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge" style="background: rgba(129, 140, 248, 0.08); border: 1px solid rgba(129, 140, 248, 0.2); color: var(--spatial-indigo); font-size: 0.76rem;">
                                    <?= e(tanggal_id($q['mulai'])) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
