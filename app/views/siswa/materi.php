<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/siswa/mapel')) ?>">Mata Pelajaran</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= e($pengampu['mapel_nama']) ?></li>
    </ol>
</nav>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1 class="page-title">Materi Pembelajaran</h1>
        <p class="page-subtitle">
            <span class="fw-semibold" style="color: var(--slate-primary);"><?= e($pengampu['mapel_nama']) ?></span>
            &bull; Kelas <?= e($pengampu['kelas_nama']) ?> &bull; Guru Pengampu: <?= e($pengampu['guru_nama']) ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm" href="<?= e(url('/siswa/video/' . $pengampu['id'])) ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="5 3 19 12 5 21 5 3"/>
            </svg>
            <span>Tonton Video Pembelajaran</span>
        </a>
    </div>
</div>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Materi Pelajaran</h3>
        <p class="small text-muted mb-0">Guru pengampu belum mengunggah dokumen modul atau materi untuk bab ini.</p>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($data as $mt): ?>
            <?php
            $ext = strtolower(pathinfo((string) $mt['file_asli'], PATHINFO_EXTENSION));
            $bisaPreview = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'webp'], true);
            $iconColorClass = match ($ext) {
                'pdf' => 'squircle-icon-rose',
                'doc', 'docx' => 'squircle-icon-blue',
                'xls', 'xlsx' => 'squircle-icon-green',
                'ppt', 'pptx' => 'squircle-icon-amber',
                default => 'squircle-icon-purple',
            };
            ?>
            <div class="col-12 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="squircle-icon <?= $iconColorClass ?>" style="width: 40px; height: 40px; border-radius: 10px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="badge px-2 py-1 text-uppercase fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px; font-size: 0.68rem;">
                                            <?= e($ext ?: 'file') ?>
                                        </span>
                                    </div>
                                </div>
                                <span class="small fw-semibold" style="color: var(--slate-muted); font-size: 0.75rem;">
                                    <?= e(tanggal_id($mt['created_at'])) ?>
                                </span>
                            </div>

                            <h2 class="h5 fw-bold mb-2" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                                <?= e($mt['judul']) ?>
                            </h2>

                            <?php if (($mt['deskripsi'] ?? '') !== ''): ?>
                                <p class="small mb-3" style="color: var(--slate-secondary); line-height: 1.55;">
                                    <?= nl2br(e($mt['deskripsi'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="pt-3 border-top d-flex flex-wrap gap-2 align-items-center justify-content-between" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <div class="small text-truncate" style="max-width: 200px; color: var(--slate-muted);" title="<?= e($mt['file_asli']) ?>">
                                <?= e($mt['file_asli']) ?>
                            </div>

                            <div class="d-flex gap-2">
                                <?php if ($bisaPreview): ?>
                                    <a class="btn btn-sm btn-primary px-3 d-inline-flex align-items-center gap-1.5" target="_blank"
                                       href="<?= e(url('/siswa/materi/lihat/' . $mt['id'])) ?>">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <span>Baca Online</span>
                                    </a>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-outline-secondary px-3 d-inline-flex align-items-center gap-1.5"
                                   href="<?= e(url('/siswa/materi/unduh/' . $mt['id'])) ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    <span>Unduh</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
