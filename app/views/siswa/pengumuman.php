<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Informasi & Berita</span>
            <span>&bull;</span>
            <span>Pengumuman Sekolah</span>
        </div>
        <h1 class="page-title">Papan Pengumuman</h1>
        <p class="page-subtitle">Informasi penting, instruksi belajar, dan pengumuman dari guru pengampu kelas Anda</p>
    </div>

    <!-- Live filter -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari pengumuman atau mapel..." data-instant-filter="#daftarPengumumanList">
    </div>
</div>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Pengumuman</h3>
        <p class="small text-muted mb-0">Belum ada warta atau informasi penting yang dirilis untuk rombongan belajar Anda.</p>
    </div>
<?php else: ?>
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10" id="daftarPengumumanList">
            <div class="d-flex flex-column gap-3.5">
                <?php foreach ($data as $pm): ?>
                    <div class="card shadow-sm border-0 filterable-item">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px; font-size: 0.72rem;">
                                    <?= e($pm['mapel_kode']) ?> &bull; <?= e($pm['mapel_nama']) ?>
                                </span>
                                <span class="small fw-semibold" style="color: var(--slate-muted); font-size: 0.74rem;">
                                    <?= e(tanggal_id($pm['created_at'])) ?>
                                </span>
                            </div>

                            <h2 class="h5 fw-bold mb-2" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                                <?= e($pm['judul']) ?>
                            </h2>

                            <div class="small mb-3 d-flex align-items-center gap-2" style="color: var(--slate-tertiary);">
                                <span>Pengirim: <strong style="color: var(--slate-secondary);"><?= e($pm['guru_nama']) ?></strong></span>
                            </div>

                            <div class="p-3.5 rounded-3" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.85); color: var(--slate-secondary); line-height: 1.6; font-size: 0.88rem;">
                                <?= nl2br(e($pm['isi'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
