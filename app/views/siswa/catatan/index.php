<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Produktivitas</span>
            <span>&bull;</span>
            <span>Buku Catatan Digital</span>
        </div>
        <h1 class="page-title">Catatan Belajar Saya</h1>
        <p class="page-subtitle">Ruang belajar pribadi untuk menyusun ringkasan materi, rumus penting, dan intisari pembelajaran</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 260px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari catatan..." data-instant-filter="#daftarCatatanGrid">
        </div>
        <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/siswa/catatan/form')) ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tulis Catatan Baru</span>
        </a>
    </div>
</div>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Catatan Belajar</h3>
        <p class="small text-muted mb-3">Mulai susun rangkuman atau intisari materi pelajaran Anda agar lebih mudah dipelajari kapan saja.</p>
        <a href="<?= e(url('/siswa/catatan/form')) ?>" class="btn btn-primary btn-sm px-4 py-2 fw-semibold">
            Mulai Menulis Catatan
        </a>
    </div>
<?php else: ?>
    <div class="row g-4" id="daftarCatatanGrid">
        <?php foreach ($data as $c): ?>
            <div class="col-12 col-md-6 col-xl-4 filterable-item">
                <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 8px; font-size: 0.72rem;">
                                <?= e($c['mapel_nama'] ?? 'Umum') ?>
                            </span>
                            <span class="small fw-semibold" style="color: var(--slate-muted); font-size: 0.74rem;">
                                <?= e(tanggal_id($c['updated_at'])) ?>
                            </span>
                        </div>

                        <h2 class="h5 fw-bold mb-2.5" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                            <?= e($c['judul']) ?>
                        </h2>

                        <div class="small flex-grow-1 mb-4" style="color: var(--slate-secondary); line-height: 1.6; font-size: 0.86rem; white-space: pre-wrap;">
                            <?= e(mb_strimwidth((string) $c['isi'], 0, 150, '...')) ?>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-end gap-2 mt-auto" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <a href="<?= e(url('/siswa/catatan/form/' . $c['id'])) ?>" class="btn btn-sm btn-outline-primary px-3 py-1.5 fw-semibold">
                                Edit Catatan
                            </a>
                            <form method="post" action="<?= e(url('/siswa/catatan/hapus')) ?>" data-konfirmasi="Hapus catatan ini secara permanen?">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= e($c['id']) ?>">
                                <button class="btn btn-sm btn-outline-danger px-3 py-1.5">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
