<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Pembelajaran</span>
            <span>&bull;</span>
            <span>Semester Ganjil</span>
        </div>
        <h1 class="page-title">Mata Pelajaran</h1>
        <p class="page-subtitle">Daftar mata pelajaran aktif semester ini yang terdaftar untuk kelas Anda</p>
    </div>

    <!-- Live Search Filter -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari mata pelajaran atau guru..." data-instant-filter="#daftarMapelGrid">
    </div>
</div>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Mata Pelajaran</h3>
        <p class="small text-muted mb-0">Belum ada mata pelajaran yang diatur oleh kurikulum untuk kelas Anda.</p>
    </div>
<?php else: ?>
    <div class="row g-3" id="daftarMapelGrid">
        <?php foreach ($data as $p): ?>
            <?php
            // Assign decorative accent colors based on subject ID
            $colorClasses = ['squircle-icon-blue', 'squircle-icon-green', 'squircle-icon-amber', 'squircle-icon-purple'];
            $accentClass = $colorClasses[$p['id'] % count($colorClasses)];
            ?>
            <div class="col-12 col-sm-6 col-lg-4 filterable-item">
                <div class="card h-100 shadow-sm border-0 subject-card">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px;">
                                    <?= e($p['mapel_kode']) ?>
                                </span>
                                <div class="squircle-icon <?= $accentClass ?>" style="width: 38px; height: 38px; border-radius: 10px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                        <line x1="8" y1="7" x2="16" y2="7"/>
                                        <line x1="8" y1="11" x2="14" y2="11"/>
                                    </svg>
                                </div>
                            </div>

                            <h2 class="h5 fw-bold mb-2" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                                <?= e($p['mapel_nama']) ?>
                            </h2>

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div style="width: 26px; height: 26px; border-radius: 50%; background: #e2e8f0; color: var(--slate-secondary); display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700;">
                                    <?= strtoupper(substr((string) ($p['guru_nama'] ?? 'G'), 0, 1)) ?>
                                </div>
                                <span class="small fw-semibold" style="color: var(--slate-secondary);">
                                    <?= e($p['guru_nama']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <span class="small fw-bold d-inline-flex align-items-center gap-1.5" style="color: var(--slate-tertiary);">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                                <span><?= e($p['jumlah_materi']) ?> Materi Tersedia</span>
                            </span>

                            <a class="btn btn-sm btn-primary px-3 py-1.5" href="<?= e(url('/siswa/materi/' . $p['id'])) ?>">
                                Buka Materi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
