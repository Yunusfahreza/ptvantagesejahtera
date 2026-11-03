<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Komunikasi & Informasi</span>
            <span>&bull;</span>
            <span>Pengumuman Kelas</span>
        </div>
        <h1 class="page-title">Papan Warta & Pengumuman</h1>
        <p class="page-subtitle">Siarkan warta penting, instruksi jadwal susulan, dan pengumuman kepada rombel kelas</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 250px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari warta..." data-instant-filter="#daftarPengumumanGuruGrid">
        </div>
        <?php if ($pengampu !== []): ?>
            <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/guru/pengumuman/form')) ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                <span>Buat Pengumuman Baru</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun. Silakan hubungi admin sekolah.
    </div>
<?php endif; ?>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Pengumuman</h3>
        <p class="small text-muted mb-0">Klik tombol "Buat Pengumuman Baru" untuk merilis informasi kepada para siswa.</p>
    </div>
<?php else: ?>
    <div class="row g-4" id="daftarPengumumanGuruGrid">
        <?php foreach ($data as $pm): ?>
            <div class="col-12 col-lg-6 filterable-item">
                <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2.5">
                            <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px; font-size: 0.72rem;">
                                Kelas <?= e($pm['kelas_nama']) ?> &bull; <?= e($pm['mapel_nama']) ?>
                            </span>
                            <span class="small fw-semibold" style="color: var(--slate-muted); font-size: 0.74rem;">
                                <?= e(tanggal_id($pm['created_at'])) ?>
                            </span>
                        </div>

                        <h2 class="h5 fw-bold mb-2.5" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                            <?= e($pm['judul']) ?>
                        </h2>

                        <div class="p-3.5 rounded-3 mb-3" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.85); color: var(--slate-secondary); line-height: 1.6; font-size: 0.88rem;">
                            <?= nl2br(e($pm['isi'])) ?>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent p-3 pt-0 border-0 d-flex justify-content-end gap-2">
                        <a class="btn btn-sm btn-outline-primary px-3 py-1.5"
                           href="<?= e(url('/guru/pengumuman/form/' . $pm['id'])) ?>">Ubah</a>
                        <form method="post" action="<?= e(url('/guru/pengumuman/hapus')) ?>"
                              data-konfirmasi="Hapus pengumuman ini secara permanen?">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= e($pm['id']) ?>">
                            <button class="btn btn-sm btn-outline-danger px-3 py-1.5">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
