<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/siswa/mapel')) ?>">Mata Pelajaran</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= e($pengampu['mapel_nama']) ?></li>
    </ol>
</nav>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1 class="page-title">Video Pembelajaran</h1>
        <p class="page-subtitle">
            <span class="fw-semibold" style="color: var(--slate-primary);"><?= e($pengampu['mapel_nama']) ?></span>
            &bull; Kelas <?= e($pengampu['kelas_nama']) ?> &bull; Guru Pengampu: <?= e($pengampu['guru_nama']) ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary btn-sm px-3.5 py-2 fw-semibold" href="<?= e(url('/siswa/materi/' . $pengampu['id'])) ?>">
            Lihat Modul / PDF
        </a>
    </div>
</div>

<div class="row g-4">
    <?php if ($data === []): ?>
        <div class="col-12">
            <div class="empty-state-box">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                    </svg>
                </div>
                <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Video Pembelajaran</h3>
                <p class="small text-muted mb-0">Guru pengampu belum mengunggah rekaman video pembelajaran untuk sesi mata pelajaran ini.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($data as $v): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 8px; font-size: 0.72rem;">
                            <?= $v['tipe'] === 'youtube' ? 'YouTube' : ($v['tipe'] === 'gdrive' ? 'Google Drive' : 'MP4 Studio') ?>
                        </span>
                        <?php if (($v['durasi'] ?? '') !== ''): ?>
                            <span class="small fw-semibold d-inline-flex align-items-center gap-1" style="color: var(--slate-tertiary); font-size: 0.74rem;">
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
