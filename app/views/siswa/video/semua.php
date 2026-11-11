<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Pembelajaran</span>
            <span>&bull;</span>
            <span>Multimedia & Video</span>
        </div>
        <h1 class="page-title">Semua Video Pembelajaran</h1>
        <p class="page-subtitle">Kumpulan video pembelajaran dari seluruh mata pelajaran di kelas Anda</p>
    </div>

    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari judul video atau mapel..." data-instant-filter="#semuaVideoGrid">
    </div>
</div>

<div class="row g-4" id="semuaVideoGrid">
    <?php if ($data === []): ?>
        <div class="col-12">
            <div class="empty-state-box">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                    </svg>
                </div>
                <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Video Pembelajaran</h3>
                <p class="small text-muted mb-0">Belum ada video pembelajaran yang diunggah oleh guru pengampu di kelas Anda.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($data as $v): ?>
        <div class="col-12 col-md-6 col-lg-4 filterable-item">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
                        <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 8px; font-size: 0.72rem;">
                            <?= e($v['mapel_kode']) ?> &bull; <?= e($v['mapel_nama']) ?>
                        </span>
                        <?php if (($v['durasi'] ?? '') !== ''): ?>
                            <span class="small fw-semibold d-inline-flex align-items-center gap-1 flex-shrink-0" style="color: var(--slate-tertiary); font-size: 0.74rem;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <span><?= e($v['durasi']) ?></span>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h2 class="h5 fw-bold mb-2" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                        <?= e($v['judul']) ?>
                    </h2>

                    <div class="small fw-semibold mb-2" style="color: var(--slate-tertiary); font-size: 0.76rem;">
                        Pengajar: <?= e($v['guru_nama']) ?>
                    </div>

                    <?php if (($v['deskripsi'] ?? '') !== ''): ?>
                        <div class="small flex-grow-1 mb-3" style="color: var(--slate-secondary); line-height: 1.55; font-size: 0.84rem;">
                            <?= e(mb_strimwidth((string) $v['deskripsi'], 0, 110, '...')) ?>
                        </div>
                    <?php else: ?>
                        <div class="flex-grow-1"></div>
                    <?php endif; ?>

                    <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-3" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <span class="small" style="color: var(--slate-muted); font-size: 0.74rem;">
                            <?= e(tanggal_id($v['created_at'])) ?>
                        </span>
                        <a href="<?= e(url('/siswa/video/tonton/' . $v['id'])) ?>" class="btn btn-sm btn-primary px-3 py-1.5 d-inline-flex align-items-center gap-1.5 shadow-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                            <span>Tonton Video</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
